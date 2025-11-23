<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Models\TenantModel;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

/**
 * Payment Controller
 * Handles Midtrans payment integration for booking payments
 */
class PaymentController extends Controller
{
    private $paymentModel;
    private $bookingModel;
    private $tenantModel;
    private $config;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
        $this->tenantModel = new TenantModel();
        
        // Load Midtrans configuration
        $this->config = require __DIR__ . '/../../config/midtrans.php';
        
        // Set Midtrans configuration
        Config::$serverKey = $this->config['server_key'];
        Config::$isProduction = $this->config['is_production'];
        Config::$isSanitized = $this->config['is_sanitized'];
        Config::$is3ds = $this->config['is_3ds'];
    }

    /**
     * Get tenant ID from session
     * 
     * @return int|null
     */
    private function getTenantId()
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        return $tenant ? $tenant['id'] : null;
    }

    /**
     * Create payment transaction and get Snap token
     * 
     * @param int $bookingId Booking ID
     */
    public function createTransaction($bookingId)
    {
        // Validate booking
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            $this->flash('error', 'Booking tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Verify booking belongs to current tenant
        $tenantId = $this->getTenantId();
        if ($booking['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses ke booking ini.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Check if booking is waiting for payment
        if ($booking['status'] !== 'waiting_payment') {
            $this->flash('error', 'Status booking tidak valid untuk pembayaran.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Check if payment already exists
        $existingPayment = $this->paymentModel->findByBookingId($bookingId);
        
        if ($existingPayment && $existingPayment['payment_status'] === 'paid') {
            $this->flash('error', 'Booking ini sudah dibayar.');
            $this->redirect(url('/tenant/bookings/' . $bookingId));
            return;
        }

        try {
            // Get booking details
            $bookingDetail = $this->bookingModel->getDetail($bookingId);
            
            if (!$bookingDetail) {
                throw new \Exception('Detail booking tidak ditemukan.');
            }

            // Generate order ID
            $orderId = $this->paymentModel->generateOrderId($bookingId);

            // Create or update payment record
            if ($existingPayment) {
                // Reuse existing payment - DO NOT RESET expiry time
                $paymentId = $existingPayment['id'];
                
                // Only update if payment is still pending/failed (not paid)
                if (!in_array($existingPayment['payment_status'], ['paid', 'settlement'])) {
                    // Check if payment is expired
                    if ($existingPayment['expires_at'] && strtotime($existingPayment['expires_at']) < time()) {
                        throw new \Exception('Pembayaran sudah expired. Silakan buat booking baru.');
                    }
                    
                    // Keep same order_id and expiry time
                    $orderId = $existingPayment['midtrans_order_id'];
                    $expiryTime = $existingPayment['expires_at'];
                    
                    error_log("Reusing existing payment. Order ID: $orderId, Expires at: $expiryTime");
                }
            } else {
                // Create new payment record with expiry time
                $expiryMinutes = $this->config['expiry_duration'];
                $expiryTime = date('Y-m-d H:i:s', strtotime("+{$expiryMinutes} minutes"));
                
                $paymentId = $this->paymentModel->createPayment([
                    'booking_id' => $bookingId,
                    'amount' => $booking['total_price'],
                    'midtrans_order_id' => $orderId,
                    'payment_status' => 'pending',
                    'expires_at' => $expiryTime
                ]);

                if (!$paymentId) {
                    throw new \Exception('Gagal membuat record pembayaran.');
                }
                
                error_log("Created new payment. Order ID: $orderId, Expires at: $expiryTime");
            }

            // Prepare transaction details for Midtrans
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int)$booking['total_price']
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $bookingDetail['tenant_name'],
                'email' => $bookingDetail['tenant_email'] ?? 'customer@example.com',
                'phone' => $bookingDetail['tenant_phone']
            ];

            // Item details
            $itemDetails = [
                [
                    'id' => 'BOOKING-' . $bookingId,
                    'price' => (int)$booking['total_price'],
                    'quantity' => 1,
                    'name' => 'Sewa ' . $bookingDetail['kost_name'] . ' - ' . $bookingDetail['kamar_name'],
                ]
            ];

            // Transaction parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'enabled_payments' => $this->config['enabled_payments'],
                'expiry' => [
                    'duration' => $this->config['expiry_duration'],
                    'unit' => 'minutes'
                ]
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            // Update payment with snap token
            $this->paymentModel->updateSnapToken($paymentId, $snapToken);

            // Prepare data for view
            $data = [
                'title' => 'Pembayaran - ' . $bookingDetail['kost_name'],
                'booking' => $bookingDetail,
                'payment' => [
                    'order_id' => $orderId,
                    'amount' => $booking['total_price'],
                    'snap_token' => $snapToken
                ],
                'midtrans_client_key' => $this->config['client_key'],
                'midtrans_snap_url' => $this->config['snap_url']
            ];

            $this->view('payment/checkout', $data, 'layouts/main');

        } catch (\Exception $e) {
            error_log('Payment Error: ' . $e->getMessage());
            $this->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect(url('/tenant/bookings'));
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function notification()
    {
        try {
            // Get notification from Midtrans
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            $transactionId = $notification->transaction_id ?? null;

            // Find payment record
            $payment = $this->paymentModel->findByOrderId($orderId);

            if (!$payment) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Order not found']);
                return;
            }

            // Get booking
            $booking = $this->bookingModel->find($payment['booking_id']);

            if (!$booking) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
                return;
            }

            // Update payment response
            $this->paymentModel->updateMidtransResponse($orderId, (array)$notification);

            // Determine payment status and booking status
            $paymentStatus = 'pending';
            $bookingStatus = $booking['status'];

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $paymentStatus = 'paid';
                    $bookingStatus = 'paid';
                }
            } else if ($transactionStatus == 'settlement') {
                $paymentStatus = 'paid';
                $bookingStatus = 'paid';
            } else if ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
            } else if ($transactionStatus == 'deny') {
                $paymentStatus = 'failed';
                $bookingStatus = 'cancelled';
            } else if ($transactionStatus == 'expire') {
                $paymentStatus = 'expired';
                $bookingStatus = 'cancelled';
            } else if ($transactionStatus == 'cancel') {
                $paymentStatus = 'cancelled';
                $bookingStatus = 'cancelled';
            }

            // Update payment record
            $updateData = [
                'payment_status' => $paymentStatus,
                'payment_type' => $paymentType,
                'midtrans_transaction_id' => $transactionId
            ];

            if ($paymentStatus === 'paid') {
                $updateData['paid_at'] = date('Y-m-d H:i:s');
            }

            $this->paymentModel->updateStatus($orderId, $paymentStatus, $updateData);

            // Update booking status
            if ($bookingStatus !== $booking['status']) {
                $this->bookingModel->update($booking['id'], [
                    'status' => $bookingStatus
                ]);
                
                // Update room status to occupied if booking is paid or accepted
                if (in_array($bookingStatus, ['paid', 'accepted', 'active_rent'])) {
                    $kamarModel = new \App\Models\KamarModel();
                    $kamarModel->update($booking['kamar_id'], ['status' => 'occupied']);
                } elseif (in_array($bookingStatus, ['cancelled', 'rejected', 'completed'])) {
                    // Set back to available if booking cancelled/rejected/completed
                    $kamarModel = new \App\Models\KamarModel();
                    $kamarModel->update($booking['kamar_id'], ['status' => 'available']);
                }
                
                // Send email notification
                $this->sendPaymentEmailNotification($payment, $booking, $paymentStatus);
            }

            // Send response to Midtrans
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Notification processed successfully'
            ]);

        } catch (\Exception $e) {
            error_log('Notification Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Payment success page
     * 
     * @param string $orderId Order ID
     */
    public function success($orderId = null)
    {
        if (!$orderId) {
            $orderId = $_GET['order_id'] ?? null;
        }

        error_log("Payment Success - Order ID: " . ($orderId ?? 'NULL'));
        error_log("Payment Success - GET params: " . json_encode($_GET));

        if (!$orderId) {
            $this->flash('error', 'Order ID tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Find payment
        $payment = $this->paymentModel->findByOrderId($orderId);

        if (!$payment) {
            error_log("Payment Success - Payment not found for order: $orderId");
            $this->flash('error', 'Pembayaran tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Get payment detail
        $paymentDetail = $this->paymentModel->getDetail($payment['id']);

        // Verify tenant ownership
        $tenantId = $this->getTenantId();
        if ($paymentDetail['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses ke pembayaran ini.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        error_log("Payment Success - Rendering success page for order: $orderId");

        $data = [
            'title' => 'Pembayaran Berhasil',
            'pageTitle' => 'Pembayaran Berhasil',
            'payment' => $paymentDetail
        ];

        $this->view('payment/success', $data, 'layouts/main');
    }

    /**
     * Payment pending page
     * 
     * @param string $orderId Order ID
     */
    public function pending($orderId = null)
    {
        if (!$orderId) {
            $orderId = $_GET['order_id'] ?? null;
        }

        if (!$orderId) {
            $this->flash('error', 'Order ID tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Find payment
        $payment = $this->paymentModel->findByOrderId($orderId);

        if (!$payment) {
            $this->flash('error', 'Pembayaran tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Get payment detail
        $paymentDetail = $this->paymentModel->getDetail($payment['id']);

        // Verify tenant ownership
        $tenantId = $this->getTenantId();
        if ($paymentDetail['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses ke pembayaran ini.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        $data = [
            'title' => 'Pembayaran Pending',
            'payment' => $paymentDetail
        ];

        $this->view('payment/pending', $data, 'layouts/main');
    }

    /**
     * Payment failed page
     * 
     * @param string $orderId Order ID
     */
    public function failed($orderId = null)
    {
        if (!$orderId) {
            $orderId = $_GET['order_id'] ?? null;
        }

        if (!$orderId) {
            $this->flash('error', 'Order ID tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Find payment
        $payment = $this->paymentModel->findByOrderId($orderId);

        if (!$payment) {
            $this->flash('error', 'Pembayaran tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Get payment detail
        $paymentDetail = $this->paymentModel->getDetail($payment['id']);

        // Verify tenant ownership
        $tenantId = $this->getTenantId();
        if ($paymentDetail['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses ke pembayaran ini.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        $data = [
            'title' => 'Pembayaran Gagal',
            'payment' => $paymentDetail
        ];

        $this->view('payment/failed', $data, 'layouts/main');
    }

    /**
     * Finish payment (redirect from Midtrans)
     */
    public function finish()
    {
        $orderId = $_GET['order_id'] ?? null;
        $statusCode = $_GET['status_code'] ?? null;
        $transactionStatus = $_GET['transaction_status'] ?? null;

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $this->redirect(url('/payment/success/' . $orderId));
        } else if ($transactionStatus === 'pending') {
            $this->redirect(url('/payment/pending/' . $orderId));
        } else {
            $this->redirect(url('/payment/failed/' . $orderId));
        }
    }

    /**
     * Error payment page
     */
    public function error()
    {
        $data = [
            'title' => 'Error Pembayaran'
        ];

        $this->view('payment/error', $data, 'layouts/main');
    }

    /**
     * Unfinish payment (user close payment page)
     */
    public function unfinish()
    {
        $orderId = $_GET['order_id'] ?? null;

        if ($orderId) {
            $this->flash('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
            $this->redirect(url('/tenant/bookings'));
        } else {
            $this->redirect(url('/tenant/bookings'));
        }
    }
    
    /**
     * Send payment email notification
     * 
     * @param array $payment Payment data
     * @param array $booking Booking data
     * @param string $paymentStatus Payment status
     */
    private function sendPaymentEmailNotification($payment, $booking, $paymentStatus)
    {
        try {
            // Get full payment detail for email
            $paymentDetail = $this->paymentModel->getDetail($payment['id']);
            
            if (!$paymentDetail || empty($paymentDetail['tenant_email'])) {
                error_log("Cannot send email: Missing payment detail or email");
                return;
            }
            
            // Load email service
            require_once __DIR__ . '/../../helpers/EmailService.php';
            $emailService = new \Helpers\EmailService();
            
            // Prepare email data
            $emailData = [
                'tenant_name' => $paymentDetail['tenant_name'],
                'tenant_email' => $paymentDetail['tenant_email'],
                'booking_id' => $booking['booking_id'] ?? '#' . $booking['id'],
                'order_id' => $payment['midtrans_order_id'],
                'kost_name' => $paymentDetail['kost_name'],
                'kamar_name' => $paymentDetail['kamar_name'],
                'start_date' => $paymentDetail['start_date'],
                'end_date' => $paymentDetail['end_date'],
                'duration_months' => $paymentDetail['duration_months'],
                'amount' => $payment['amount'],
                'payment_type' => $payment['payment_type'],
                'paid_at' => $payment['paid_at'] ?? date('Y-m-d H:i:s'),
                'owner_name' => $paymentDetail['owner_name'] ?? null,
                'owner_phone' => $paymentDetail['owner_phone'] ?? null,
                'status' => $paymentStatus
            ];
            
            // Send email based on status
            if ($paymentStatus == 'paid') {
                $emailService->sendPaymentSuccessEmail($emailData);
            } elseif (in_array($paymentStatus, ['expired', 'cancelled', 'failed'])) {
                $emailService->sendPaymentCancelledEmail($emailData);
            }
            
        } catch (\Exception $e) {
            error_log("Email notification error: " . $e->getMessage());
        }
    }

    /**
     * Check payment status
     * 
     * @param string $orderId Order ID
     */
    public function checkStatus($orderId)
    {
        try {
            $payment = $this->paymentModel->findByOrderId($orderId);

            if (!$payment) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Payment not found'
                ]);
                return;
            }

            // Get status from Midtrans
            $status = \Midtrans\Transaction::status($orderId);

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'order_id' => $orderId,
                    'transaction_status' => $status->transaction_status ?? '',
                    'payment_type' => $status->payment_type ?? '',
                    'transaction_time' => $status->transaction_time ?? ''
                ]
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}

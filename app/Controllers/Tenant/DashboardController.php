<?php

namespace App\Controllers\Tenant;

use Core\Controller;
use Core\Database;
use Core\Session;
use App\Models\TenantModel;
use App\Models\BookingModel;

/**
 * Tenant Dashboard Controller
 * Handles tenant dashboard and statistics
 */
class DashboardController extends Controller
{
    private $db;
    private $tenantModel;
    private $bookingModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->tenantModel = new TenantModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Show tenant dashboard
     */
    public function index()
    {
        $userId = Session::get('user_id');
        
        // Get tenant data
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get statistics
        $stats = [];

        // Total bookings
        $stats['total_bookings'] = $this->bookingModel->count(['tenant_id' => $tenantId]);

        // Active rental
        $stats['active_rentals'] = $this->bookingModel->countByStatus($tenantId, 'active_rent');

        // Waiting payment
        $stats['waiting_payment'] = $this->bookingModel->countByStatus($tenantId, 'waiting_payment');

        // Completed bookings
        $stats['completed'] = $this->bookingModel->countByStatus($tenantId, 'completed');

        // Get active booking detail
        $activeBooking = $this->bookingModel->findByTenantId($tenantId, 'active_rent');
        $stats['active_booking'] = $activeBooking[0] ?? null;

        // Get recent bookings (last 5)
        $recentBookings = $this->bookingModel->getRecentBookings($tenantId, 5);

        // Get upcoming payments (bookings waiting for payment)
        $upcomingPayments = $this->bookingModel->findByTenantId($tenantId, 'waiting_payment');

        $this->view('tenant/dashboard', [
            'title' => 'Dashboard Tenant',
            'pageTitle' => 'Dashboard Tenant',
            'tenant' => $tenant,
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'upcomingPayments' => $upcomingPayments
        ], 'layouts/dashboard');
    }

    /**
     * Show all tenant bookings
     */
    public function bookings()
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get filter from query string
        $status = $_GET['status'] ?? null;

        // Get bookings
        $bookings = $this->bookingModel->findByTenantId($tenantId, $status);

        $this->view('tenant/booking/index', [
            'title' => 'Daftar Booking Saya',
            'pageTitle' => 'Daftar Booking Saya',
            'tenant' => $tenant,
            'bookings' => $bookings,
            'currentStatus' => $status
        ], 'layouts/dashboard');
    }

    /**
     * Show booking detail
     * 
     * @param int $id Booking ID
     */
    public function bookingDetail($id)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Get booking detail
        $booking = $this->bookingModel->getDetail($id);

        if (!$booking) {
            $this->flash('error', 'Booking tidak ditemukan.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Verify ownership
        if ($booking['tenant_id'] != $tenant['id']) {
            $this->flash('error', 'Anda tidak memiliki akses ke booking ini.');
            $this->redirect(url('/tenant/bookings'));
            return;
        }

        // Get payment info if exists
        $paymentQuery = "SELECT * FROM payments WHERE booking_id = :booking_id ORDER BY created_at DESC LIMIT 1";
        $payment = $this->db->fetchOne($paymentQuery, ['booking_id' => $id]);

        $this->view('tenant/booking/show', [
            'title' => 'Detail Booking',
            'pageTitle' => 'Detail Booking',
            'tenant' => $tenant,
            'booking' => $booking,
            'payment' => $payment
        ], 'layouts/dashboard');
    }
}

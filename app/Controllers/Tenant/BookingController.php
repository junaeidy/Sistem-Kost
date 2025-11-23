<?php

namespace App\Controllers\Tenant;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\TenantModel;
use App\Models\BookingModel;
use App\Models\KamarModel;

/**
 * Tenant Booking Controller
 * Handles booking creation and management
 */
class BookingController extends Controller
{
    private $db;
    private $tenantModel;
    private $bookingModel;
    private $kamarModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->tenantModel = new TenantModel();
        $this->bookingModel = new BookingModel();
        $this->kamarModel = new KamarModel();
    }

    /**
     * Show all bookings for tenant
     */
    public function index()
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

        // Pagination setup
        $perPage = max(1, (int) (config('pagination.per_page') ?: 10));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        // Get total count
        $total = $this->bookingModel->countByTenantId($tenantId, $status);
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;

        // Get bookings with pagination
        $bookings = $this->bookingModel->findByTenantId($tenantId, $status, $perPage, $offset);

        $this->view('tenant/booking/index', [
            'title' => 'Daftar Booking Saya',
            'pageTitle' => 'Daftar Booking Saya',
            'tenant' => $tenant,
            'bookings' => $bookings,
            'currentStatus' => $status,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'per_page' => $perPage
            ]
        ], 'layouts/dashboard');
    }

    /**
     * Show booking form for specific room
     * 
     * @param int $kamarId Room ID
     */
    public function create($kamarId)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Get room detail with kost info
        $kamar = $this->kamarModel->getDetailWithKost($kamarId);

        if (!$kamar) {
            $this->flash('error', 'Kamar tidak ditemukan.');
            $this->redirect(url('/tenant/search'));
            return;
        }

        // Check if room is available
        if ($kamar['status'] !== 'available') {
            $this->flash('error', 'Kamar ini tidak tersedia.');
            $this->redirect(url('/kost/' . $kamar['kost_id']));
            return;
        }
        
        // Double check: verify no active bookings for this room
        $hasActiveBooking = $this->bookingModel->hasActiveBookingForRoom($kamarId);
        if ($hasActiveBooking) {
            $this->flash('error', 'Kamar ini sudah dibooking oleh pengguna lain.');
            $this->redirect(url('/kost/' . $kamar['kost_id']));
            return;
        }

        // Check if tenant already has active booking
        $activeBooking = $this->bookingModel->getActiveBooking($tenant['id']);
        if ($activeBooking) {
            $this->flash('warning', 'Anda sudah memiliki booking aktif.');
            $this->redirect(url('/tenant/bookings/' . $activeBooking['id']));
            return;
        }

        $this->view('tenant/booking/create', [
            'title' => 'Booking Kamar',
            'pageTitle' => 'Booking Kamar',
            'tenant' => $tenant,
            'kamar' => $kamar
        ], 'layouts/dashboard');
    }

    /**
     * Store new booking
     */
    public function store()
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Validate input
        $kamarId = $_POST['kamar_id'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $durationMonths = $_POST['duration_months'] ?? 1;
        $notes = $_POST['notes'] ?? '';

        if (!$kamarId || !$startDate) {
            $this->flash('error', 'Data booking tidak lengkap.');
            $this->redirect(url('/tenant/search'));
            return;
        }

        // Validate duration
        $durationMonths = (int) $durationMonths;
        if ($durationMonths < 1 || $durationMonths > 12) {
            $this->flash('error', 'Durasi sewa harus antara 1-12 bulan.');
            $this->redirect(url('/tenant/kamar/' . $kamarId . '/book'));
            return;
        }

        // Validate start date (must be today or future)
        $today = date('Y-m-d');
        if ($startDate < $today) {
            $this->flash('error', 'Tanggal mulai tidak boleh di masa lalu.');
            $this->redirect(url('/tenant/kamar/' . $kamarId . '/book'));
            return;
        }

        // Get room detail
        $kamar = $this->kamarModel->getDetailWithKost($kamarId);
        
        if (!$kamar || $kamar['status'] !== 'available') {
            $this->flash('error', 'Kamar tidak tersedia.');
            $this->redirect(url('/tenant/search'));
            return;
        }

        // Calculate end date
        $endDate = date('Y-m-d', strtotime($startDate . ' +' . $durationMonths . ' months'));

        // Check if room is available for the period
        if (!$this->bookingModel->isKamarAvailable($kamarId, $startDate, $endDate)) {
            $this->flash('error', 'Kamar sudah dibooking untuk periode tersebut.');
            $this->redirect(url('/tenant/kamar/' . $kamarId . '/book'));
            return;
        }

        // Calculate total price
        $totalPrice = $kamar['price'] * $durationMonths;

        // Create booking
        $bookingData = [
            'tenant_id' => $tenant['id'],
            'kamar_id' => $kamarId,
            'start_date' => $startDate,
            'duration_months' => $durationMonths,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'waiting_payment',
            'notes' => $notes
        ];

        try {
            $bookingId = $this->bookingModel->createBooking($bookingData);

            if ($bookingId) {
                // Log untuk debugging
                error_log("Booking created successfully. ID: $bookingId, Status: waiting_payment");
                
                $this->flash('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
                // Redirect langsung ke halaman pembayaran Midtrans
                $this->redirect(url('/payment/create/' . $bookingId));
            } else {
                error_log("Failed to create booking. Data: " . json_encode($bookingData));
                $this->flash('error', 'Gagal membuat booking. Silakan coba lagi.');
                $this->redirect(url('/tenant/kamar/' . $kamarId . '/book'));
            }
        } catch (\Exception $e) {
            error_log("Booking creation error: " . $e->getMessage());
            $this->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect(url('/tenant/kamar/' . $kamarId . '/book'));
        }
    }

    /**
     * Show booking detail
     * 
     * @param int $id Booking ID
     */
    public function show($id)
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

        // Parse facilities
        if (!empty($booking['kamar_facilities'])) {
            $booking['kamar_facilities_array'] = json_decode($booking['kamar_facilities'], true);
        } else {
            $booking['kamar_facilities_array'] = [];
        }

        if (!empty($booking['kost_facilities'])) {
            $booking['kost_facilities_array'] = json_decode($booking['kost_facilities'], true);
        } else {
            $booking['kost_facilities_array'] = [];
        }

        $this->view('tenant/booking/show', [
            'title' => 'Detail Booking',
            'pageTitle' => 'Detail Booking',
            'tenant' => $tenant,
            'booking' => $booking,
            'payment' => $payment
        ], 'layouts/dashboard');
    }

    /**
     * Cancel booking (only if status is waiting_payment)
     * 
     * @param int $id Booking ID
     */
    public function cancel($id)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Get booking
        $booking = $this->bookingModel->find($id);

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

        // Only allow cancel if status is waiting_payment
        if ($booking['status'] !== 'waiting_payment') {
            $this->flash('error', 'Booking ini tidak dapat dibatalkan.');
            $this->redirect(url('/tenant/bookings/' . $id));
            return;
        }

        // Cancel booking
        if ($this->bookingModel->cancelBooking($id)) {
            $this->flash('success', 'Booking berhasil dibatalkan.');
        } else {
            $this->flash('error', 'Gagal membatalkan booking.');
        }

        $this->redirect(url('/tenant/bookings'));
    }
}

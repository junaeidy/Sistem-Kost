<?php

namespace App\Controllers\Owner;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\OwnerModel;

/**
 * Owner Booking Controller
 * Manage booking requests for owner's kost
 */
class BookingController extends Controller
{
    private $db;
    private $ownerModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ownerModel = new OwnerModel();
    }

    /**
     * Get owner ID from session
     */
    private function getOwnerId()
    {
        $userId = Session::get('user_id');
        $owner = $this->ownerModel->findByUserId($userId);
        return $owner ? $owner['id'] : null;
    }

    /**
     * Display all bookings for owner's kost
     */
    public function index()
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Get filter from query string
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';

        // Build query
        $query = "SELECT 
                    b.*,
                    b.booking_id,
                    t.name as tenant_name,
                    t.phone as tenant_phone,
                    k.name as kamar_name,
                    kost.name as kost_name,
                    kost.id as kost_id,
                    p.payment_status,
                    p.paid_at
                  FROM bookings b
                  JOIN tenants t ON b.tenant_id = t.id
                  JOIN kamar k ON b.kamar_id = k.id
                  JOIN kost ON k.kost_id = kost.id
                  LEFT JOIN payments p ON b.id = p.booking_id
                  WHERE kost.owner_id = :owner_id";

        $params = ['owner_id' => $ownerId];

        // Apply filters
        if ($status !== 'all') {
            $query .= " AND b.status = :status";
            $params['status'] = $status;
        }

        if (!empty($search)) {
            $query .= " AND (t.name LIKE :search OR kost.name LIKE :search OR k.name LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $query .= " ORDER BY b.created_at DESC";

        $bookings = $this->db->fetchAll($query, $params);

        // Count by status
        $countQuery = "SELECT 
                        b.status, 
                        COUNT(*) as count
                       FROM bookings b
                       JOIN kamar k ON b.kamar_id = k.id
                       JOIN kost ON k.kost_id = kost.id
                       WHERE kost.owner_id = :owner_id
                       GROUP BY b.status";
        
        $counts = $this->db->fetchAll($countQuery, ['owner_id' => $ownerId]);
        
        $statusCounts = [
            'all' => 0,
            'waiting_payment' => 0,
            'paid' => 0,
            'accepted' => 0,
            'rejected' => 0,
            'active_rent' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        foreach ($counts as $count) {
            $statusCounts[$count['status']] = $count['count'];
            $statusCounts['all'] += $count['count'];
        }

        $this->view('owner/bookings/index', [
            'title' => 'Kelola Booking',
            'pageTitle' => 'Kelola Booking',
            'bookings' => $bookings,
            'statusCounts' => $statusCounts,
            'currentStatus' => $status,
            'currentSearch' => $search
        ], 'layouts/dashboard');
    }

    /**
     * Show booking detail
     */
    public function show($id)
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Get booking detail
        $query = "SELECT 
                    b.*,
                    b.booking_id,
                    t.name as tenant_name,
                    t.phone as tenant_phone,
                    t.address as tenant_address,
                    t.user_id as tenant_user_id,
                    u.email as tenant_email,
                    k.name as kamar_name,
                    k.price as kamar_price,
                    k.facilities as kamar_facilities,
                    kost.name as kost_name,
                    kost.address as kost_address,
                    kost.id as kost_id,
                    p.amount as payment_amount,
                    p.payment_status,
                    p.payment_type,
                    p.paid_at,
                    p.payment_url,
                    p.midtrans_order_id
                  FROM bookings b
                  JOIN tenants t ON b.tenant_id = t.id
                  JOIN users u ON t.user_id = u.id
                  JOIN kamar k ON b.kamar_id = k.id
                  JOIN kost ON k.kost_id = kost.id
                  LEFT JOIN payments p ON b.id = p.booking_id
                  WHERE b.id = :id AND kost.owner_id = :owner_id";

        $booking = $this->db->fetchOne($query, [
            'id' => $id,
            'owner_id' => $ownerId
        ]);

        if (!$booking) {
            $this->flash('error', 'Booking tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect(url('/owner/bookings'));
            return;
        }

        $this->view('owner/bookings/show', [
            'title' => 'Detail Booking',
            'pageTitle' => 'Detail Booking',
            'booking' => $booking
        ], 'layouts/dashboard');
    }

    /**
     * Accept booking request
     */
    public function accept($id)
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Verify ownership and get booking
        $query = "SELECT b.*, k.kost_id, kost.owner_id
                  FROM bookings b
                  JOIN kamar k ON b.kamar_id = k.id
                  JOIN kost ON k.kost_id = kost.id
                  WHERE b.id = :id AND kost.owner_id = :owner_id";

        $booking = $this->db->fetchOne($query, [
            'id' => $id,
            'owner_id' => $ownerId
        ]);

        if (!$booking) {
            $this->flash('error', 'Booking tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect(url('/owner/bookings'));
            return;
        }

        // Check if booking is in correct status
        if ($booking['status'] !== 'paid') {
            $this->flash('error', 'Booking hanya bisa diterima jika sudah dibayar.');
            $this->back();
            return;
        }

        // Update booking status to accepted
        $updateQuery = "UPDATE bookings SET status = 'accepted', updated_at = NOW() WHERE id = :id";
        $this->db->query($updateQuery, ['id' => $id]);

        // Update kamar status to occupied
        $updateKamarQuery = "UPDATE kamar SET status = 'occupied' WHERE id = :kamar_id";
        $this->db->query($updateKamarQuery, ['kamar_id' => $booking['kamar_id']]);

        $this->flash('success', 'Booking berhasil diterima. Kamar sekarang berstatus terisi.');
        $this->redirect(url('/owner/bookings/' . $id));
    }

    /**
     * Reject booking request
     */
    public function reject($id)
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Verify ownership and get booking
        $query = "SELECT b.*, k.kost_id, kost.owner_id
                  FROM bookings b
                  JOIN kamar k ON b.kamar_id = k.id
                  JOIN kost ON k.kost_id = kost.id
                  WHERE b.id = :id AND kost.owner_id = :owner_id";

        $booking = $this->db->fetchOne($query, [
            'id' => $id,
            'owner_id' => $ownerId
        ]);

        if (!$booking) {
            $this->flash('error', 'Booking tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect(url('/owner/bookings'));
            return;
        }

        // Check if booking can be rejected
        if (in_array($booking['status'], ['accepted', 'active_rent', 'completed'])) {
            $this->flash('error', 'Booking dengan status ini tidak bisa ditolak.');
            $this->back();
            return;
        }

        // Get rejection reason from POST
        $reason = $_POST['reason'] ?? 'Tidak ada alasan yang diberikan';

        // Update booking status to rejected
        $updateQuery = "UPDATE bookings 
                        SET status = 'rejected', 
                            notes = CONCAT(COALESCE(notes, ''), '\n\nAlasan penolakan: ', :reason),
                            updated_at = NOW() 
                        WHERE id = :id";
        
        $this->db->query($updateQuery, [
            'id' => $id,
            'reason' => $reason
        ]);

        $this->flash('success', 'Booking berhasil ditolak.');
        $this->redirect(url('/owner/bookings/' . $id));
    }
}

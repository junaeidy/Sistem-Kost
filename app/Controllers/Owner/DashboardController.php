<?php

namespace App\Controllers\Owner;

use Core\Controller;
use Core\Database;
use Core\Session;
use App\Models\OwnerModel;

/**
 * Owner Dashboard Controller
 * Handles owner dashboard and statistics
 */
class DashboardController extends Controller
{
    private $db;
    private $ownerModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ownerModel = new OwnerModel();
    }

    /**
     * Show owner dashboard
     */
    public function index()
    {
        $userId = Session::get('user_id');
        
        // Get owner data
        $owner = $this->ownerModel->findByUserId($userId);
        
        if (!$owner) {
            $this->flash('error', 'Owner profile tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $ownerId = $owner['id'];

        // Get statistics
        $stats = [];

        // Total kost
        $kostQuery = "SELECT COUNT(*) as total FROM kost WHERE owner_id = :owner_id";
        $kostResult = $this->db->fetchOne($kostQuery, ['owner_id' => $ownerId]);
        $stats['total_kost'] = $kostResult['total'] ?? 0;

        // Total kamar
        $kamarQuery = "SELECT COUNT(*) as total 
                      FROM kamar ka
                      JOIN kost k ON ka.kost_id = k.id
                      WHERE k.owner_id = :owner_id";
        $kamarResult = $this->db->fetchOne($kamarQuery, ['owner_id' => $ownerId]);
        $stats['total_kamar'] = $kamarResult['total'] ?? 0;

        // Available kamar
        $availableQuery = "SELECT COUNT(*) as total 
                          FROM kamar ka
                          JOIN kost k ON ka.kost_id = k.id
                          WHERE k.owner_id = :owner_id AND ka.status = 'available'";
        $availableResult = $this->db->fetchOne($availableQuery, ['owner_id' => $ownerId]);
        $stats['available_kamar'] = $availableResult['total'] ?? 0;

        // Total bookings
        $bookingQuery = "SELECT COUNT(*) as total 
                        FROM bookings b
                        JOIN kamar ka ON b.kamar_id = ka.id
                        JOIN kost k ON ka.kost_id = k.id
                        WHERE k.owner_id = :owner_id";
        $bookingResult = $this->db->fetchOne($bookingQuery, ['owner_id' => $ownerId]);
        $stats['total_bookings'] = $bookingResult['total'] ?? 0;

        // Active rentals
        $activeQuery = "SELECT COUNT(*) as total 
                       FROM bookings b
                       JOIN kamar ka ON b.kamar_id = ka.id
                       JOIN kost k ON ka.kost_id = k.id
                       WHERE k.owner_id = :owner_id AND b.status = 'active_rent'";
        $activeResult = $this->db->fetchOne($activeQuery, ['owner_id' => $ownerId]);
        $stats['active_rentals'] = $activeResult['total'] ?? 0;

        // Total revenue (from successful payments)
        $revenueQuery = "SELECT COALESCE(SUM(p.amount), 0) as total
                        FROM payments p
                        JOIN bookings b ON p.booking_id = b.id
                        JOIN kamar ka ON b.kamar_id = ka.id
                        JOIN kost k ON ka.kost_id = k.id
                        WHERE k.owner_id = :owner_id AND p.payment_status = 'paid'";
        $revenueResult = $this->db->fetchOne($revenueQuery, ['owner_id' => $ownerId]);
        $stats['total_revenue'] = $revenueResult['total'] ?? 0;

        // Recent bookings (last 5)
        $recentBookingsQuery = "SELECT 
                                b.*,
                                ka.name as kamar_name,
                                k.name as kost_name,
                                t.name as tenant_name,
                                u.email as tenant_email
                               FROM bookings b
                               JOIN kamar ka ON b.kamar_id = ka.id
                               JOIN kost k ON ka.kost_id = k.id
                               JOIN tenants t ON b.tenant_id = t.id
                               JOIN users u ON t.user_id = u.id
                               WHERE k.owner_id = :owner_id
                               ORDER BY b.created_at DESC
                               LIMIT 5";
        $recentBookings = $this->db->fetchAll($recentBookingsQuery, ['owner_id' => $ownerId]);

        // Owner's kost list
        $kostListQuery = "SELECT k.*, 
                          (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_kamar,
                          (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_kamar
                         FROM kost k
                         WHERE k.owner_id = :owner_id
                         ORDER BY k.created_at DESC";
        $kostList = $this->db->fetchAll($kostListQuery, ['owner_id' => $ownerId]);

        $this->view('owner/dashboard', [
            'title' => 'Owner Dashboard',
            'pageTitle' => 'Dashboard',
            'owner' => $owner,
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'kostList' => $kostList
        ], 'layouts/dashboard');
    }
}

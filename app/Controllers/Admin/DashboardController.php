<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\UserModel;
use App\Models\OwnerModel;
use App\Models\TenantModel;
use Core\Database;

/**
 * Admin Dashboard Controller
 * Handles admin dashboard and statistics
 */
class DashboardController extends Controller
{
    private $userModel;
    private $ownerModel;
    private $tenantModel;
    private $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ownerModel = new OwnerModel();
        $this->tenantModel = new TenantModel();
        $this->db = Database::getInstance();
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => $this->userModel->count(),
            'total_owners' => $this->ownerModel->count(),
            'total_tenants' => $this->tenantModel->count(),
            'pending_owners' => $this->ownerModel->countByStatus('pending'),
            'active_owners' => $this->ownerModel->countByStatus('active'),
            'rejected_owners' => $this->ownerModel->countByStatus('rejected'),
        ];

        // Get total kost
        $kostQuery = "SELECT COUNT(*) as total FROM kost";
        $kostResult = $this->db->fetchOne($kostQuery);
        $stats['total_kost'] = $kostResult['total'] ?? 0;

        // Get total kamar
        $kamarQuery = "SELECT COUNT(*) as total FROM kamar";
        $kamarResult = $this->db->fetchOne($kamarQuery);
        $stats['total_kamar'] = $kamarResult['total'] ?? 0;

        // Get available kamar
        $availableKamarQuery = "SELECT COUNT(*) as total FROM kamar WHERE status = 'available'";
        $availableKamarResult = $this->db->fetchOne($availableKamarQuery);
        $stats['available_kamar'] = $availableKamarResult['total'] ?? 0;

        // Get total bookings
        $bookingQuery = "SELECT COUNT(*) as total FROM bookings";
        $bookingResult = $this->db->fetchOne($bookingQuery);
        $stats['total_bookings'] = $bookingResult['total'] ?? 0;

        // Get recent pending owners
        $recentPendingOwners = $this->ownerModel->getAllWithUsers(['status' => 'pending'], 5);

        // Get recent users
        $recentUsersQuery = "SELECT id, email, role, status, created_at 
                            FROM users 
                            ORDER BY created_at DESC 
                            LIMIT 5";
        $recentUsers = $this->db->fetchAll($recentUsersQuery);

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'recentPendingOwners' => $recentPendingOwners,
            'recentUsers' => $recentUsers
        ], 'layouts/dashboard');
    }
}

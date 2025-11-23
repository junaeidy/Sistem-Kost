<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;

/**
 * Admin Kost Management Controller
 * Handles kost monitoring and management
 */
class KostController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Show all kost
     */
    public function index()
    {
        $status = $this->get('status', 'all');
        $page = max(1, (int) $this->get('page', 1));
        $perPage = max(1, (int) (config('pagination.per_page') ?: 10)); // Fallback to 10 if not set
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT k.*, o.name as owner_name, u.email as owner_email
                  FROM kost k
                  LEFT JOIN owners o ON k.owner_id = o.id
                  LEFT JOIN users u ON o.user_id = u.id";
        
        $params = [];
        $whereClause = '';
        
        if ($status !== 'all') {
            $whereClause = " WHERE k.status = :status";
            $params['status'] = $status;
        }
        
        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM kost k" . $whereClause;
        $totalResult = $this->db->fetchOne($countQuery, $params);
        $total = (int) ($totalResult['total'] ?? 0);
        
        // Get paginated results
        $query .= $whereClause . " ORDER BY k.created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        $kosts = $this->db->fetchAll($query, $params);
        
        // Calculate pagination
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;

        $this->view('admin/kost/index', [
            'title' => 'Kelola Kost',
            'pageTitle' => 'Kelola Kost',
            'kosts' => $kosts,
            'currentStatus' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ], 'layouts/dashboard');
    }

    /**
     * Show kost detail
     */
    public function show($id)
    {
        $query = "SELECT k.*, o.name as owner_name, o.phone as owner_phone, 
                         u.email as owner_email
                  FROM kost k
                  LEFT JOIN owners o ON k.owner_id = o.id
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE k.id = :id";
        
        $kost = $this->db->fetchOne($query, ['id' => $id]);

        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/admin/kost'));
            return;
        }

        // Get kost photos
        $photosQuery = "SELECT * FROM kost_photos WHERE kost_id = :kost_id ORDER BY is_primary DESC";
        $photos = $this->db->fetchAll($photosQuery, ['kost_id' => $id]);

        // Get kamar
        $kamarQuery = "SELECT * FROM kamar WHERE kost_id = :kost_id ORDER BY name";
        $kamars = $this->db->fetchAll($kamarQuery, ['kost_id' => $id]);

        $this->view('admin/kost/show', [
            'title' => 'Detail Kost',
            'pageTitle' => 'Detail Kost',
            'kost' => $kost,
            'photos' => $photos,
            'kamars' => $kamars
        ], 'layouts/dashboard');
    }

    /**
     * Update kost status
     */
    public function updateStatus($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/kost'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $status = $this->post('status');

        if (!in_array($status, ['active', 'inactive'])) {
            $this->flash('error', 'Status tidak valid.');
            $this->back();
            return;
        }

        $query = "UPDATE kost SET status = :status, updated_at = NOW() WHERE id = :id";
        $updated = $this->db->query($query, ['status' => $status, 'id' => $id]);

        if ($updated) {
            $this->flash('success', 'Status kost berhasil diubah.');
        } else {
            $this->flash('error', 'Gagal mengubah status kost.');
        }

        $this->redirect(url('/admin/kost/' . $id));
    }

    /**
     * Delete kost
     */
    public function delete($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/kost'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        // Check if kost has bookings
        $bookingCheck = "SELECT COUNT(*) as total FROM bookings WHERE kost_id = :kost_id";
        $bookingCount = $this->db->fetchOne($bookingCheck, ['kost_id' => $id]);

        if ($bookingCount['total'] > 0) {
            $this->flash('error', 'Tidak dapat menghapus kost yang memiliki booking aktif.');
            $this->back();
            return;
        }

        // Delete kost (cascade will delete related kamar and photos)
        $query = "DELETE FROM kost WHERE id = :id";
        $deleted = $this->db->query($query, ['id' => $id]);

        if ($deleted) {
            $this->flash('success', 'Kost berhasil dihapus.');
            $this->redirect(url('/admin/kost'));
        } else {
            $this->flash('error', 'Gagal menghapus kost.');
            $this->back();
        }
    }
}

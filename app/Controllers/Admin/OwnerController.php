<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use App\Models\OwnerModel;
use App\Models\UserModel;

/**
 * Admin Owner Verification Controller
 * Handles owner verification (approve/reject)
 */
class OwnerController extends Controller
{
    private $ownerModel;
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->ownerModel = new OwnerModel();
        $this->userModel = new UserModel();
        $this->db = Database::getInstance();
    }

    /**
     * Show all owners
     */
    public function index()
    {
        $status = $this->get('status', 'all');
        $page = max(1, (int) $this->get('page', 1));
        $perPage = max(1, (int) (config('pagination.per_page') ?: 10)); // Fallback to 10 if not set
        $offset = ($page - 1) * $perPage;
        
        // Build query
        $query = "SELECT o.*, u.email, u.status as user_status, u.role, u.created_at as user_created_at
                  FROM owners o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE 1=1";
        
        $params = [];
        if ($status !== 'all') {
            $query .= " AND u.status = :status";
            $params['status'] = $status;
        }
        
        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM owners o LEFT JOIN users u ON o.user_id = u.id WHERE 1=1";
        if ($status !== 'all') {
            $countQuery .= " AND u.status = :status";
        }
        $totalResult = $this->db->fetchOne($countQuery, $params);
        $total = (int) ($totalResult['total'] ?? 0);
        
        // Get paginated results
        $query .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        $owners = $this->db->fetchAll($query, $params);
        
        // Calculate pagination
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;

        $this->view('admin/owners/index', [
            'title' => 'Kelola Owner',
            'pageTitle' => 'Kelola Owner',
            'owners' => $owners,
            'currentStatus' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ], 'layouts/dashboard');
    }

    /**
     * Show owner detail
     */
    public function show($id)
    {
        $owner = $this->ownerModel->getOwnerWithUser($id);

        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Get owner's kost
        $kostQuery = "SELECT * FROM kost WHERE owner_id = :owner_id ORDER BY created_at DESC";
        $kosts = $this->db->fetchAll($kostQuery, ['owner_id' => $id]);

        $this->view('admin/owners/show', [
            'title' => 'Detail Owner',
            'pageTitle' => 'Detail Owner',
            'owner' => $owner,
            'kosts' => $kosts
        ], 'layouts/dashboard');
    }

    /**
     * Approve owner
     */
    public function approve($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $owner = $this->ownerModel->getOwnerWithUser($id);

        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Update user status to active
        $updated = $this->userModel->updateStatus($owner['user_id'], 'active');

        if ($updated) {
            $this->flash('success', 'Owner berhasil disetujui. Akun sudah aktif.');
            
            // TODO: Send email notification to owner
            // $this->sendApprovalEmail($owner['email']);
        } else {
            $this->flash('error', 'Gagal menyetujui owner.');
        }

        $this->redirect(url('/admin/owners/' . $id));
    }

    /**
     * Reject owner
     */
    public function reject($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $reason = $this->post('reason', '');

        $owner = $this->ownerModel->getOwnerWithUser($id);

        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Update user status to rejected
        $updated = $this->userModel->updateStatus($owner['user_id'], 'rejected');

        if ($updated) {
            $this->flash('success', 'Owner berhasil ditolak.');
            
            // TODO: Send email notification to owner with reason
            // $this->sendRejectionEmail($owner['email'], $reason);
        } else {
            $this->flash('error', 'Gagal menolak owner.');
        }

        $this->redirect(url('/admin/owners'));
    }

    /**
     * Suspend owner
     */
    public function suspend($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $reason = $this->post('reason', '');

        $owner = $this->ownerModel->getOwnerWithUser($id);

        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Update user status to suspended
        $updated = $this->userModel->updateStatus($owner['user_id'], 'suspended', $reason);

        if ($updated) {
            $this->flash('success', 'Owner berhasil disuspend.');
            
            // TODO: Send email notification to owner
            // $this->sendSuspensionEmail($owner['email'], $reason);
        } else {
            $this->flash('error', 'Gagal mensuspend owner.');
        }

        $this->redirect(url('/admin/owners/' . $id));
    }

    /**
     * Activate suspended owner
     */
    public function activate($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $owner = $this->ownerModel->getOwnerWithUser($id);

        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/admin/owners'));
            return;
        }

        // Update user status to active
        $updated = $this->userModel->updateStatus($owner['user_id'], 'active');

        if ($updated) {
            $this->flash('success', 'Owner berhasil diaktifkan kembali.');
        } else {
            $this->flash('error', 'Gagal mengaktifkan owner.');
        }

        $this->redirect(url('/admin/owners/' . $id));
    }
}

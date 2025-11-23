<?php

namespace App\Controllers\Admin;

use App\Models\OwnerModel;
use App\Models\UserModel;
use Core\Controller;

class VerificationController extends Controller
{
    private $ownerModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->ownerModel = new OwnerModel();
        $this->userModel = new UserModel();
    }

    /**
     * List owners pending verification
     */
    public function index()
    {
        // Get all owners with verification status
        $owners = $this->ownerModel->getAllWithVerificationStatus();

        $data = [
            'title' => 'Verifikasi Owner',
            'owners' => $owners
        ];

        $this->view('admin/owners/verification', $data);
    }

    /**
     * Show verification detail for a specific owner
     */
    public function show($ownerId)
    {
        $owner = $this->ownerModel->getWithUser($ownerId);
        
        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan');
            return redirect('/admin/verification');
        }

        $data = [
            'title' => 'Detail Verifikasi Owner',
            'owner' => $owner
        ];

        $this->view('admin/owners/verification-detail', $data);
    }

    /**
     * Approve owner verification
     */
    public function approve($ownerId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/admin/verification');
        }

        $owner = $this->ownerModel->find($ownerId);
        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan');
            return redirect('/admin/verification');
        }

        // Update verification status
        $data = [
            'verification_status' => 'approved',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $_SESSION['user_id'],
            'verification_note' => $_POST['note'] ?? 'Verified'
        ];

        if ($this->ownerModel->update($ownerId, $data)) {
            // Also update user status to active
            $this->userModel->update($owner['user_id'], ['status' => 'active']);

            $this->flash('success', 'Owner berhasil diverifikasi');
        } else {
            $this->flash('error', 'Gagal memverifikasi owner');
        }

        return redirect('/admin/verification');
    }

    /**
     * Reject owner verification
     */
    public function reject($ownerId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/admin/verification');
        }

        $owner = $this->ownerModel->find($ownerId);
        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan');
            return redirect('/admin/verification');
        }

        $reason = $_POST['reason'] ?? '';
        if (empty($reason)) {
            $this->flash('error', 'Alasan penolakan harus diisi');
            return redirect("/admin/verification/show/{$ownerId}");
        }

        // Update verification status
        $data = [
            'verification_status' => 'rejected',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $_SESSION['user_id'],
            'verification_note' => $reason
        ];

        if ($this->ownerModel->update($ownerId, $data)) {
            // Update user status to rejected
            $this->userModel->update($owner['user_id'], ['status' => 'rejected']);

            $this->flash('success', 'Verifikasi owner ditolak');
        } else {
            $this->flash('error', 'Gagal menolak verifikasi');
        }

        return redirect('/admin/verification');
    }

    /**
     * Reset verification status (for re-verification)
     */
    public function reset($ownerId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/admin/verification');
        }

        $owner = $this->ownerModel->find($ownerId);
        if (!$owner) {
            $this->flash('error', 'Owner tidak ditemukan');
            return redirect('/admin/verification');
        }

        // Reset verification status
        $data = [
            'verification_status' => 'pending',
            'verified_at' => null,
            'verified_by' => null,
            'verification_note' => null
        ];

        if ($this->ownerModel->update($ownerId, $data)) {
            // Update user status back to pending
            $this->userModel->update($owner['user_id'], ['status' => 'pending']);

            $this->flash('success', 'Status verifikasi berhasil direset');
        } else {
            $this->flash('error', 'Gagal mereset status verifikasi');
        }

        return redirect('/admin/verification');
    }
}

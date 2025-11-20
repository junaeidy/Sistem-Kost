<?php

namespace App\Middleware;

use Core\Session;
use App\Models\UserModel;

/**
 * Owner Middleware
 * Ensures user is authenticated, is an owner, AND status is active
 */
class OwnerMiddleware
{
    public function handle()
    {
        if (!Session::isLoggedIn()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect(url('/login'));
            return false;
        }

        if (!Session::hasRole('owner')) {
            flash('error', 'Akses ditolak. Halaman ini khusus untuk pemilik kost.');
            redirect(url('/'));
            return false;
        }

        // Check owner status
        $userModel = new UserModel();
        $user = $userModel->find(Session::userId());

        if (!$user || $user['status'] !== 'active') {
            $status = $user['status'] ?? 'unknown';
            
            if ($status === 'pending') {
                // Redirect to pending page
                redirect(url('/owner/pending'));
            } elseif ($status === 'rejected') {
                flash('error', 'Akun Anda telah ditolak oleh admin. Silakan hubungi administrator.');
                Session::destroy();
                redirect(url('/login'));
            } else {
                flash('error', 'Akun Anda tidak aktif.');
                Session::destroy();
                redirect(url('/login'));
            }
            
            return false;
        }

        return true;
    }
}

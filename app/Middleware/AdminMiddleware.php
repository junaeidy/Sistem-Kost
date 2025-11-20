<?php

namespace App\Middleware;

use Core\Session;

/**
 * Admin Middleware
 * Ensures user is authenticated AND is an admin
 */
class AdminMiddleware
{
    public function handle()
    {
        if (!Session::isLoggedIn()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect(url('/login'));
            return false;
        }

        if (!Session::hasRole('admin')) {
            flash('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
            redirect(url('/'));
            return false;
        }

        return true;
    }
}

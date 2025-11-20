<?php

namespace App\Middleware;

use Core\Session;

/**
 * Tenant Middleware
 * Ensures user is authenticated AND is a tenant
 */
class TenantMiddleware
{
    public function handle()
    {
        if (!Session::isLoggedIn()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect(url('/login'));
            return false;
        }

        if (!Session::hasRole('tenant')) {
            flash('error', 'Akses ditolak. Halaman ini khusus untuk penyewa.');
            redirect(url('/'));
            return false;
        }

        return true;
    }
}

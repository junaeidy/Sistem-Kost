<?php

namespace App\Middleware;

use Core\Session;

/**
 * Auth Middleware
 * Ensures user is authenticated
 */
class AuthMiddleware
{
    public function handle()
    {
        if (!Session::isLoggedIn()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect(url('/login'));
            return false;
        }

        return true;
    }
}

<?php

namespace App\Middleware;

use Core\Session;

/**
 * Guest Middleware
 * Ensures user is NOT authenticated (for login/register pages)
 */
class GuestMiddleware
{
    public function handle()
    {
        if (Session::isLoggedIn()) {
            // Redirect based on role
            $role = Session::userRole();
            
            switch ($role) {
                case 'admin':
                    redirect(url('/admin/dashboard'));
                    break;
                case 'owner':
                    redirect(url('/owner/dashboard'));
                    break;
                case 'tenant':
                    redirect(url('/tenant/dashboard'));
                    break;
                default:
                    redirect(url('/'));
            }
            
            return false;
        }

        return true;
    }
}

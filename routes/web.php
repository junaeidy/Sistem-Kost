<?php

use Core\Router;

/**
 * Web Routes
 * Define all application routes here
 */

$router = new Router();

// Register middlewares
$router->registerMiddleware('auth', App\Middleware\AuthMiddleware::class);
$router->registerMiddleware('guest', App\Middleware\GuestMiddleware::class);
$router->registerMiddleware('admin', App\Middleware\AdminMiddleware::class);
$router->registerMiddleware('owner', App\Middleware\OwnerMiddleware::class);
$router->registerMiddleware('tenant', App\Middleware\TenantMiddleware::class);

// ================================================================
// PUBLIC ROUTES
// ================================================================

// Home page
$router->get('/', 'HomeController@index');
$router->get('/search', 'HomeController@search');
$router->get('/kost/{id}', 'HomeController@detail');

// Static Pages
$router->get('/privacy-policy', 'PagesController@privacyPolicy');
$router->get('/terms-conditions', 'PagesController@termsConditions');
$router->get('/faq', 'PagesController@faq');

// ================================================================
// AUTHENTICATION ROUTES (Guest only)
// ================================================================

// Login
$router->middleware('guest')->get('/login', 'AuthController@showLogin');
$router->middleware('guest')->post('/login', 'AuthController@login');

// Register Tenant
$router->middleware('guest')->get('/register', 'AuthController@showRegister');
$router->middleware('guest')->post('/register', 'AuthController@register');

// Register Owner
$router->middleware('guest')->get('/register-owner', 'AuthController@showRegisterOwner');
$router->middleware('guest')->post('/register-owner', 'AuthController@registerOwner');

// Logout
$router->middleware('auth')->post('/logout', 'AuthController@logout');

// Owner Pending Page (for pending owners)
$router->middleware('auth')->get('/owner/pending', 'AuthController@showPending');

// ================================================================
// ADMIN ROUTES
// ================================================================

$router->middleware(['auth', 'admin'])->get('/admin/dashboard', 'Admin\DashboardController@index');

// Owner Management
$router->middleware(['auth', 'admin'])->get('/admin/owners', 'Admin\OwnerController@index');
$router->middleware(['auth', 'admin'])->get('/admin/owners/{id}', 'Admin\OwnerController@show');
$router->middleware(['auth', 'admin'])->post('/admin/owners/{id}/approve', 'Admin\OwnerController@approve');
$router->middleware(['auth', 'admin'])->post('/admin/owners/{id}/reject', 'Admin\OwnerController@reject');
$router->middleware(['auth', 'admin'])->post('/admin/owners/{id}/suspend', 'Admin\OwnerController@suspend');
$router->middleware(['auth', 'admin'])->post('/admin/owners/{id}/activate', 'Admin\OwnerController@activate');

// Kost Management (Admin view)
$router->middleware(['auth', 'admin'])->get('/admin/kost', 'Admin\KostController@index');
$router->middleware(['auth', 'admin'])->get('/admin/kost/{id}', 'Admin\KostController@show');
$router->middleware(['auth', 'admin'])->post('/admin/kost/{id}/status', 'Admin\KostController@updateStatus');
$router->middleware(['auth', 'admin'])->post('/admin/kost/{id}/delete', 'Admin\KostController@delete');

// Transaction Management
$router->middleware(['auth', 'admin'])->get('/admin/transactions', 'Admin\TransactionController@index');
$router->middleware(['auth', 'admin'])->get('/admin/transactions/{id}', 'Admin\TransactionController@show');

// Profile Management
$router->middleware(['auth', 'admin'])->get('/admin/profile', 'Admin\ProfileController@index');
$router->middleware(['auth', 'admin'])->post('/admin/profile/update', 'Admin\ProfileController@updateProfile');
$router->middleware(['auth', 'admin'])->post('/admin/profile/update-password', 'Admin\ProfileController@updatePassword');

// KTP Verification Management
$router->middleware(['auth', 'admin'])->get('/admin/verification', 'Admin\VerificationController@index');
$router->middleware(['auth', 'admin'])->get('/admin/verification/show/{id}', 'Admin\VerificationController@show');
$router->middleware(['auth', 'admin'])->post('/admin/verification/approve/{id}', 'Admin\VerificationController@approve');
$router->middleware(['auth', 'admin'])->post('/admin/verification/reject/{id}', 'Admin\VerificationController@reject');
$router->middleware(['auth', 'admin'])->post('/admin/verification/reset/{id}', 'Admin\VerificationController@reset');

// ================================================================
// OWNER ROUTES
// ================================================================

$router->middleware(['auth', 'owner'])->get('/owner/dashboard', 'Owner\DashboardController@index');

// Kost Management
$router->middleware(['auth', 'owner'])->get('/owner/kost', 'Owner\KostController@index');
$router->middleware(['auth', 'owner'])->get('/owner/kost/create', 'Owner\KostController@create');
$router->middleware(['auth', 'owner'])->post('/owner/kost/store', 'Owner\KostController@store');
$router->middleware(['auth', 'owner'])->get('/owner/kost/{id}', 'Owner\KostController@show');
$router->middleware(['auth', 'owner'])->get('/owner/kost/{id}/edit', 'Owner\KostController@edit');
$router->middleware(['auth', 'owner'])->post('/owner/kost/{id}/update', 'Owner\KostController@update');
$router->middleware(['auth', 'owner'])->post('/owner/kost/{id}/delete', 'Owner\KostController@delete');

// Kamar Management
$router->middleware(['auth', 'owner'])->get('/owner/kost/{id}/kamar', 'Owner\KamarController@index');
$router->middleware(['auth', 'owner'])->get('/owner/kost/{id}/kamar/create', 'Owner\KamarController@create');
$router->middleware(['auth', 'owner'])->post('/owner/kost/{id}/kamar/store', 'Owner\KamarController@store');
$router->middleware(['auth', 'owner'])->get('/owner/kamar/{id}/edit', 'Owner\KamarController@edit');
$router->middleware(['auth', 'owner'])->post('/owner/kamar/{id}/update', 'Owner\KamarController@update');
$router->middleware(['auth', 'owner'])->post('/owner/kamar/{id}/delete', 'Owner\KamarController@delete');

// Kost Photo Management
$router->middleware(['auth', 'owner'])->get('/owner/kost/photos/{id}', 'Owner\KostPhotoController@index');
$router->middleware(['auth', 'owner'])->post('/owner/kost/photos/{id}/upload', 'Owner\KostPhotoController@upload');
$router->middleware(['auth', 'owner'])->post('/owner/kost/photos/set-primary/{id}', 'Owner\KostPhotoController@setPrimary');
$router->middleware(['auth', 'owner'])->post('/owner/kost/photos/delete/{id}', 'Owner\KostPhotoController@delete');
$router->middleware(['auth', 'owner'])->post('/owner/kost/photos/reorder/{id}', 'Owner\KostPhotoController@reorder');

// Booking Management
$router->middleware(['auth', 'owner'])->get('/owner/bookings', 'Owner\BookingController@index');
$router->middleware(['auth', 'owner'])->get('/owner/bookings/{id}', 'Owner\BookingController@show');
$router->middleware(['auth', 'owner'])->post('/owner/bookings/{id}/accept', 'Owner\BookingController@accept');
$router->middleware(['auth', 'owner'])->post('/owner/bookings/{id}/reject', 'Owner\BookingController@reject');

// Profile Management
$router->middleware(['auth', 'owner'])->get('/owner/profile', 'Owner\ProfileController@index');
$router->middleware(['auth', 'owner'])->post('/owner/profile/update', 'Owner\ProfileController@updateProfile');
$router->middleware(['auth', 'owner'])->post('/owner/profile/update-password', 'Owner\ProfileController@updatePassword');
$router->middleware(['auth', 'owner'])->post('/owner/profile/delete-photo', 'Owner\ProfileController@deletePhoto');

// ================================================================
// TENANT ROUTES
// ================================================================

// Dashboard
$router->middleware(['auth', 'tenant'])->get('/tenant/dashboard', 'Tenant\DashboardController@index');
$router->middleware(['auth', 'tenant'])->get('/tenant/bookings-list', 'Tenant\DashboardController@bookings');
$router->middleware(['auth', 'tenant'])->get('/tenant/booking-detail/{id}', 'Tenant\DashboardController@bookingDetail');

// Search & Filter
$router->middleware(['auth', 'tenant'])->get('/tenant/search', 'Tenant\SearchController@index');
$router->middleware(['auth', 'tenant'])->get('/tenant/search/{id}', 'Tenant\SearchController@detail');

// Booking Management
$router->middleware(['auth', 'tenant'])->get('/tenant/bookings', 'Tenant\BookingController@index');
$router->middleware(['auth', 'tenant'])->get('/tenant/kamar/{id}/book', 'Tenant\BookingController@create');
$router->middleware(['auth', 'tenant'])->post('/tenant/bookings', 'Tenant\BookingController@store');
$router->middleware(['auth', 'tenant'])->get('/tenant/bookings/{id}', 'Tenant\BookingController@show');
$router->middleware(['auth', 'tenant'])->post('/tenant/bookings/{id}/cancel', 'Tenant\BookingController@cancel');

// Profile Management
$router->middleware(['auth', 'tenant'])->get('/tenant/profile', 'Tenant\ProfileController@index');
$router->middleware(['auth', 'tenant'])->post('/tenant/profile/update', 'Tenant\ProfileController@updateProfile');
$router->middleware(['auth', 'tenant'])->post('/tenant/profile/update-password', 'Tenant\ProfileController@updatePassword');
$router->middleware(['auth', 'tenant'])->post('/tenant/profile/delete-photo', 'Tenant\ProfileController@deletePhoto');

// ================================================================
// PAYMENT ROUTES (Midtrans Integration)
// ================================================================

// Payment Process (Tenant)
$router->middleware(['auth', 'tenant'])->get('/payment/create/{bookingId}', 'PaymentController@createTransaction');
$router->middleware(['auth', 'tenant'])->get('/payment/success', 'PaymentController@success');
$router->middleware(['auth', 'tenant'])->get('/payment/pending', 'PaymentController@pending');
$router->middleware(['auth', 'tenant'])->get('/payment/failed', 'PaymentController@failed');
$router->middleware(['auth', 'tenant'])->get('/payment/finish', 'PaymentController@finish');
$router->middleware(['auth', 'tenant'])->get('/payment/error', 'PaymentController@error');
$router->middleware(['auth', 'tenant'])->get('/payment/unfinish', 'PaymentController@unfinish');
$router->middleware(['auth', 'tenant'])->get('/payment/check-status/{orderId}', 'PaymentController@checkStatus');

// Payment Webhook (no auth required - validated by Midtrans signature)
$router->post('/payment/notification', 'PaymentController@notification');

// Legacy routes - redirect to new payment routes
$router->middleware(['auth', 'tenant'])->get('/tenant/payment/{id}', 'Tenant\PaymentController@create');
$router->middleware(['auth', 'tenant'])->get('/tenant/payment/success', 'Tenant\PaymentController@success');
$router->middleware(['auth', 'tenant'])->get('/tenant/payment/failed', 'Tenant\PaymentController@failed');

// ================================================================
// PROFILE ROUTES (All authenticated users)
// ================================================================

$router->middleware('auth')->get('/profile', 'ProfileController@index');
$router->middleware('auth')->post('/profile/update', 'ProfileController@update');
$router->middleware('auth')->post('/profile/password', 'ProfileController@updatePassword');

// ================================================================
// DISPATCH ROUTER
// ================================================================

return $router;

<?php

/**
 * Global Helper Functions
 */

use Core\Session;
use Core\Router;
use Core\View;

/**
 * Get base URL
 * 
 * @param string $path
 * @return string
 */
function url($path = '')
{
    return Router::url($path);
}

/**
 * Build asset URL
 * 
 * @param string $path
 * @return string
 */
function asset($path)
{
    return View::asset($path);
}

/**
 * Redirect to URL
 * 
 * @param string $url
 */
function redirect($url)
{
    header("Location: {$url}");
    exit;
}

/**
 * Redirect back
 */
function back()
{
    $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($previousUrl);
}

/**
 * Escape HTML
 * 
 * @param string $string
 * @return string
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get old input value (for form repopulation)
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Store old input for next request
 */
function flash_input()
{
    $_SESSION['old'] = $_POST;
}

/**
 * Clear old input
 */
function clear_old_input()
{
    unset($_SESSION['old']);
}

/**
 * Set flash message
 * 
 * @param string $type
 * @param string $message
 */
function flash($type, $message)
{
    Session::flash($type, $message);
}

/**
 * Get flash message
 * 
 * @return array|null
 */
function get_flash()
{
    return Session::getFlash();
}

/**
 * Check if user is authenticated
 * 
 * @return bool
 */
function auth()
{
    return Session::isLoggedIn();
}

/**
 * Get current user ID
 * 
 * @return int|null
 */
function user_id()
{
    return Session::userId();
}

/**
 * Get current user role
 * 
 * @return string|null
 */
function user_role()
{
    return Session::userRole();
}

/**
 * Check if user has role
 * 
 * @param string $role
 * @return bool
 */
function has_role($role)
{
    return Session::hasRole($role);
}

/**
 * Check if user is admin
 * 
 * @return bool
 */
function is_admin()
{
    return has_role('admin');
}

/**
 * Check if user is owner
 * 
 * @return bool
 */
function is_owner()
{
    return has_role('owner');
}

/**
 * Check if user is tenant
 * 
 * @return bool
 */
function is_tenant()
{
    return has_role('tenant');
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate CSRF field for forms
 * 
 * @return string
 */
function csrf_field()
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Validate CSRF token
 * 
 * @param string|null $token
 * @return bool
 */
function validate_csrf($token = null)
{
    $token = $token ?? $_POST['csrf_token'] ?? null;
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Debug dump and die
 * Only declare if not already declared by Symfony var-dumper
 * 
 * @param mixed ...$vars
 */
if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

/**
 * Debug dump
 * Only declare if not already declared by Symfony var-dumper
 * 
 * @param mixed $var
 */
if (!function_exists('dump')) {
    function dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * Format currency (IDR)
 * 
 * @param float $amount
 * @return string
 */
function format_currency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = 'd M Y')
{
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $formatted = date($format, $timestamp);
    
    // Replace English month with Indonesian
    foreach ($months as $num => $name) {
        $formatted = str_replace(date('M', mktime(0, 0, 0, $num, 1)), $name, $formatted);
    }
    
    return $formatted;
}

/**
 * Sanitize input
 * 
 * @param string $input
 * @return string
 */
function sanitize($input)
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 * 
 * @param string $email
 * @return bool
 */
function is_valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indonesian format)
 * 
 * @param string $phone
 * @return bool
 */
function is_valid_phone($phone)
{
    // Remove non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if starts with 08 or +62 or 62
    return preg_match('/^(08|628|\+628)[0-9]{8,11}$/', $phone);
}

/**
 * Generate random string
 * 
 * @param int $length
 * @return string
 */
function random_string($length = 16)
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Check if string contains substring
 * Only declare if not already available (PHP 8+)
 * 
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

/**
 * Get file extension
 * 
 * @param string $filename
 * @return string
 */
function get_extension($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Format file size
 * 
 * @param int $bytes
 * @return string
 */
function format_filesize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Truncate string
 * 
 * @param string $string
 * @param int $length
 * @param string $append
 * @return string
 */
function str_limit($string, $length = 100, $append = '...')
{
    if (strlen($string) <= $length) {
        return $string;
    }
    
    return substr($string, 0, $length) . $append;
}

/**
 * Get config value
 * 
 * @param string $key (e.g., 'app.name' or 'database.host')
 * @param mixed $default
 * @return mixed
 */
function config($key, $default = null)
{
    $keys = explode('.', $key);
    $file = array_shift($keys);
    
    $configFile = dirname(__DIR__) . "/config/{$file}.php";
    
    if (!file_exists($configFile)) {
        return $default;
    }
    
    $config = require $configFile;
    
    foreach ($keys as $k) {
        if (!isset($config[$k])) {
            return $default;
        }
        $config = $config[$k];
    }
    
    return $config;
}

/**
 * Get environment variable
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null)
{
    $value = getenv($key);
    
    if ($value === false) {
        return $default;
    }
    
    // Convert string booleans
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
        case 'empty':
        case '(empty)':
            return '';
    }
    
    return $value;
}

/**
 * Load environment variables from .env file
 */
function load_env()
{
    $envFile = dirname(__DIR__) . '/.env';
    
    if (!file_exists($envFile)) {
        return;
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes
            $value = trim($value, '"\'');
            
            // Set environment variable
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

/**
 * Format payment method from Midtrans to user-friendly name
 * 
 * @param string|null $paymentType
 * @return string
 */
function formatPaymentMethod($paymentType)
{
    if (empty($paymentType)) {
        return '<span class="text-gray-500 italic">Belum dipilih</span>';
    }
    
    $methods = [
        // E-Wallet
        'gopay' => '<i class="fab fa-google text-blue-500"></i> GoPay',
        'shopeepay' => '<i class="fas fa-shopping-bag text-orange-500"></i> ShopeePay',
        'qris' => '<i class="fas fa-qrcode text-purple-600"></i> QRIS',
        
        // Bank Transfer
        'bank_transfer' => '<i class="fas fa-university text-blue-600"></i> Transfer Bank',
        'bca_va' => '<i class="fas fa-university text-blue-700"></i> BCA Virtual Account',
        'bni_va' => '<i class="fas fa-university text-orange-600"></i> BNI Virtual Account',
        'bri_va' => '<i class="fas fa-university text-blue-500"></i> BRI Virtual Account',
        'mandiri_va' => '<i class="fas fa-university text-yellow-600"></i> Mandiri Virtual Account',
        'permata_va' => '<i class="fas fa-university text-green-600"></i> Permata Virtual Account',
        'other_va' => '<i class="fas fa-university text-gray-600"></i> Virtual Account',
        
        // Credit/Debit Card
        'credit_card' => '<i class="fas fa-credit-card text-indigo-600"></i> Kartu Kredit/Debit',
        
        // Convenience Store
        'cstore' => '<i class="fas fa-store text-green-600"></i> Convenience Store',
        'indomaret' => '<i class="fas fa-store text-blue-600"></i> Indomaret',
        'alfamart' => '<i class="fas fa-store text-red-600"></i> Alfamart',
        
        // Others
        'akulaku' => '<i class="fas fa-wallet text-purple-600"></i> Akulaku',
        'kredivo' => '<i class="fas fa-wallet text-blue-600"></i> Kredivo',
    ];
    
    $type = strtolower($paymentType);
    
    return $methods[$type] ?? '<i class="fas fa-money-bill-wave text-gray-600"></i> ' . ucwords(str_replace('_', ' ', $paymentType));
}

/**
 * Generate unique booking ID
 * Format: TRX-XXXXXX (6 random alphanumeric characters)
 * 
 * @return string
 */
function generateBookingId()
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return 'TRX-' . $randomString;
}

/**
 * Check if booking ID is unique
 * 
 * @param string $bookingId
 * @return bool
 */
function isBookingIdUnique($bookingId)
{
    $db = Core\Database::getInstance();
    $query = "SELECT COUNT(*) as count FROM bookings WHERE booking_id = :booking_id";
    $result = $db->fetchOne($query, ['booking_id' => $bookingId]);
    
    return $result['count'] == 0;
}

/**
 * Generate unique booking ID with validation
 * Ensures the generated ID is unique in database
 * 
 * @return string
 */
function generateUniqueBookingId()
{
    do {
        $bookingId = generateBookingId();
    } while (!isBookingIdUnique($bookingId));
    
    return $bookingId;
}

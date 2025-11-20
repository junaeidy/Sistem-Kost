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

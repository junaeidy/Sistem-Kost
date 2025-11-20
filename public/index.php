<?php

/**
 * Application Entry Point
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
load_env();

// Set timezone
date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));

// Start session
Core\Session::start();

// Load routes and dispatch
$router = require __DIR__ . '/../routes/web.php';
$router->dispatch();

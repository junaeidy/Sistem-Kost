<?php
/**
 * Application Configuration
 */

return [
    'name'     => getenv('APP_NAME') ?: 'Sistem Kost',
    'env'      => getenv('APP_ENV') ?: 'development',
    'debug'    => getenv('APP_DEBUG') === 'true',
    'url'      => getenv('APP_URL') ?: 'http://localhost/kost/public',
    'timezone' => getenv('APP_TIMEZONE') ?: 'Asia/Jakarta',
    
    // Session Configuration
    'session' => [
        'lifetime' => (int) getenv('SESSION_LIFETIME') ?: 120,
        'driver'   => getenv('SESSION_DRIVER') ?: 'file',
        'path'     => dirname(__DIR__) . '/storage/sessions',
        'secure'   => getenv('SESSION_SECURE') === 'true',
        'httponly' => getenv('SESSION_HTTPONLY') === 'true',
    ],
    
    // File Upload Configuration
    'upload' => [
        'max_size'      => (int) getenv('MAX_FILE_SIZE') ?: 2097152, // 2MB default
        'allowed_types' => explode(',', getenv('ALLOWED_IMAGE_TYPES') ?: 'jpg,jpeg,png,gif'),
        'path'          => getenv('UPLOAD_PATH') ?: 'uploads/',
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => (int) getenv('ITEMS_PER_PAGE') ?: 10,
    ],
    
    // Security
    'security' => [
        'csrf_token_name' => getenv('CSRF_TOKEN_NAME') ?: 'csrf_token',
    ],
];

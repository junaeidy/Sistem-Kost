<?php

/**
 * Midtrans Payment Gateway Configuration
 * 
 * This file contains all configuration settings for Midtrans integration.
 * Make sure to set the environment variables in .env file
 */

return [
    /**
     * Merchant ID from Midtrans Dashboard
     */
    'merchant_id' => getenv('MIDTRANS_MERCHANT_ID') ?: 'G123456789',

    /**
     * Client Key from Midtrans Dashboard
     * Used for frontend (Snap.js)
     * Default: Midtrans Sandbox Client Key
     */
    'client_key' => getenv('MIDTRANS_CLIENT_KEY') ?: 'SB-Mid-client-XXXXXXXXXXXXXXXX',

    /**
     * Server Key from Midtrans Dashboard
     * Used for backend API calls
     * Default: Midtrans Sandbox Server Key
     */
    'server_key' => getenv('MIDTRANS_SERVER_KEY') ?: 'SB-Mid-server-XXXXXXXXXXXXXXXXXXXXXXXX',

    /**
     * Environment mode
     * - false: Sandbox/Development mode (DEFAULT)
     * - true: Production mode
     */
    'is_production' => getenv('MIDTRANS_IS_PRODUCTION') === 'true' ? true : false,

    /**
     * Enable input sanitization
     * Recommended to keep this enabled
     */
    'is_sanitized' => getenv('MIDTRANS_IS_SANITIZED') === 'false' ? false : true,

    /**
     * Enable 3D Secure authentication
     * Recommended for credit card transactions
     */
    'is_3ds' => getenv('MIDTRANS_IS_3DS') === 'false' ? false : true,

    /**
     * Snap API URL based on environment
     */
    'snap_url' => (getenv('MIDTRANS_IS_PRODUCTION') === 'true')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    /**
     * Payment notification URL (webhook)
     * Midtrans will send notification to this URL
     */
    'notification_url' => (getenv('APP_URL') ?: 'http://localhost:8000') . '/payment/notification',

    /**
     * Return URLs after payment
     */
    'finish_url' => (getenv('APP_URL') ?: 'http://localhost:8000') . '/payment/finish',
    'error_url' => (getenv('APP_URL') ?: 'http://localhost:8000') . '/payment/error',
    'unfinish_url' => (getenv('APP_URL') ?: 'http://localhost:8000') . '/payment/unfinish',

    /**
     * Payment expiry duration (in minutes)
     * Default: 24 hours (1440 minutes)
     */
    'expiry_duration' => 1440,

    /**
     * Enabled payment methods
     * Available: credit_card, bca_va, bni_va, bri_va, permata_va, 
     *            gopay, shopeepay, qris, etc.
     */
    'enabled_payments' => [
        'credit_card',
        'bca_va',
        'bni_va',
        'bri_va',
        'mandiri_va',
        'permata_va',
        'gopay',
        'shopeepay',
        'qris',
    ],
];

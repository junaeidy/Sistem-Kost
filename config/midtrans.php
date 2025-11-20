<?php
/**
 * Midtrans Payment Gateway Configuration
 */

return [
    'server_key'    => getenv('MIDTRANS_SERVER_KEY'),
    'client_key'    => getenv('MIDTRANS_CLIENT_KEY'),
    'is_production' => getenv('MIDTRANS_IS_PRODUCTION') === 'true',
    'is_sanitized'  => getenv('MIDTRANS_IS_SANITIZED') === 'true',
    'is_3ds'        => getenv('MIDTRANS_IS_3DS') === 'true',
];

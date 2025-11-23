<?php
/**
 * Mail Configuration
 * 
 * Email configuration untuk sistem notifikasi
 */

return [
    // Default mailer (smtp, mail, sendmail)
    'mailer' => getenv('MAIL_MAILER') ?: 'smtp',
    
    // SMTP Configuration
    'smtp' => [
        'host'       => getenv('MAIL_HOST') ?: 'smtp.gmail.com',
        'port'       => (int) getenv('MAIL_PORT') ?: 465,
        'username'   => getenv('MAIL_USERNAME') ?: '',
        'password'   => getenv('MAIL_PASSWORD') ?: '',
        'encryption' => getenv('MAIL_ENCRYPTION') ?: 'ssl',
    ],
    
    // From Address
    'from' => [
        'address' => getenv('MAIL_FROM_ADDRESS') ?: 'noreply@kostsystem.com',
        'name'    => getenv('MAIL_FROM_NAME') ?: getenv('APP_NAME') ?: 'Sistem Kost',
    ],
];

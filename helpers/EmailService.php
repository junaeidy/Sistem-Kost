<?php

namespace Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Email Service
 * Handle sending emails for various notifications
 */
class EmailService
{
    private $mailer;
    private $config;
    
    public function __construct()
    {
        $this->config = config('mail');
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }
    
    /**
     * Setup PHPMailer configuration
     */
    private function setupMailer()
    {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->config['smtp']['host'];
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->config['smtp']['username'];
            $this->mailer->Password   = $this->config['smtp']['password'];
            $this->mailer->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mailer->Port       = $this->config['smtp']['port'];
            $this->mailer->CharSet    = 'UTF-8';
            
            // Enable debug only in development mode
            if (config('app.debug') && config('app.env') !== 'production') {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
                $this->mailer->Debugoutput = function($str, $level) {
                    error_log("SMTP Debug level $level: $str");
                };
            } else {
                $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
            }
            
            // Default from address
            $this->mailer->setFrom(
                $this->config['from']['address'],
                $this->config['from']['name']
            );
            
        } catch (Exception $e) {
            error_log("PHPMailer setup error: {$e->getMessage()}");
        }
    }
    
    /**
     * Send payment success email
     * 
     * @param array $data Payment and booking data
     * @return bool
     */
    public function sendPaymentSuccessEmail($data)
    {
        $to = $data['tenant_email'];
        $subject = '✅ Pembayaran Berhasil - ' . $data['booking_id'];
        
        $body = $this->renderTemplate('payment-success', $data);
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Send payment cancelled/expired email
     * 
     * @param array $data Payment and booking data
     * @return bool
     */
    public function sendPaymentCancelledEmail($data)
    {
        $to = $data['tenant_email'];
        $subject = '❌ Pembayaran ' . ($data['status'] == 'expired' ? 'Kadaluarsa' : 'Dibatalkan') . ' - ' . $data['booking_id'];
        
        $body = $this->renderTemplate('payment-cancelled', $data);
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Render email template
     * 
     * @param string $template Template name
     * @param array $data Template data
     * @return string
     */
    private function renderTemplate($template, $data)
    {
        extract($data);
        
        ob_start();
        include __DIR__ . "/../resources/views/emails/{$template}.php";
        return ob_get_clean();
    }
    
    /**
     * Send email using PHPMailer with SMTP
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @return bool
     */
    private function send($to, $subject, $body)
    {
        try {
            // Recipients
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            
            // Alternative plain text body
            $this->mailer->AltBody = strip_tags($body);
            
            // Send
            $success = $this->mailer->send();
            
            if ($success) {
                error_log("✅ Email sent successfully to: $to - Subject: $subject");
            }
            
            return $success;
            
        } catch (Exception $e) {
            error_log("❌ Failed to send email to: $to - Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}

<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Facade;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function send($to, $subject, $body, $isHtml = true, $attachments = [])
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = config('mail.mailers.smtp.host');
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = config('mail.mailers.smtp.username');
            $this->mail->Password   = config('mail.mailers.smtp.password');
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = config('mail.mailers.smtp.port');

            // Recipients
            $this->mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $this->mail->addAddress($to);

            // Content
            $this->mail->isHTML($isHtml);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            // Attachments
            foreach ($attachments as $attachment) {
                $this->mail->addAttachment($attachment);
            }

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
            return false;
        }
    }

    // Additional methods for more complex email scenarios
    public function sendWithCC($to, $cc, $subject, $body, $isHtml = true, $attachments = [])
    {
        try {
            // Similar to send() method, but add CC
            $this->mail->addCC($cc);
            
            // Rest of the send logic
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
            return false;
        }
    }
}
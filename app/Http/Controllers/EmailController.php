<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PHPMailerService;

class EmailController extends Controller
{
    protected $mailerService;

    public function __construct(PHPMailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function sendSimpleEmail()
    {
        $to = 'genesisroxas4@gmail.com';
        $subject = 'Test Email';
        $body = '<p>This is a test email sent using PHPMailer in Laravel.</p>';

        $result = $this->mailerService->send($to, $subject, $body);

        if ($result) {
            return 'Email sent successfully!';
        } else {
            return 'Email sending failed.';
        }
    }

    public function sendEmailWithAttachment()
    {
        $to = 'recipient@example.com';
        $subject = 'Email with Attachment';
        $body = '<p>This email includes an attachment.</p>';
        $attachments = [
            storage_path('app/attachments/document.pdf')
        ];

        $result = $this->mailerService->send($to, $subject, $body, true, $attachments);

        if ($result) {
            return 'Email with attachment sent successfully!';
        } else {
            return 'Email sending failed.';
        }
    }
}

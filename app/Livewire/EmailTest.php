<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\PHPMailerService;

class EmailTest extends Component
{
    public $status = '';
    public $email = '';
    public $sending = false;

    protected $rules = [
        'email' => 'required|email'
    ];

    public function render()
    {
        return view('livewire.email-test');
    }

    public function sendTestEmail(PHPMailerService $mailerService)
    {
        // Validate email
        $this->validate();

        // Prevent multiple submissions
        if ($this->sending) {
            return;
        }

        // Set sending state
        $this->sending = true;
        $this->status = '';

        try {
            $subject = 'PHPMailer Test Email';
            $body = '<h1>Email Test Successful!</h1>
                     <p>This is a test email sent from your Laravel application using PHPMailer.</p>
                     <p>Sent at: ' . now()->format('Y-m-d H:i:s') . '</p>';

            // Send email
            $result = $mailerService->send($this->email, $subject, $body);

            // Set status based on result
            if ($result) {
                $this->status = 'Email sent successfully!';
                // Optional: reset email field after successful send
                $this->email = '';
            } else {
                $this->status = 'Email sending failed. Please check logs.';
            }
        } catch (\Exception $e) {
            $this->status = 'Error: ' . $e->getMessage();
        } finally {
            // Always reset sending state
            $this->sending = false;
        }
    }
}
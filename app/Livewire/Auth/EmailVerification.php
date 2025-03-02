<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\PHPMailerService;
use Livewire\Attributes\Layout;

class EmailVerification extends Component
{
    public $email = '';
    public $otp = '';

    public function mount($email)
    {
        $this->email = $email;
    }

    protected $rules = [
        'otp' => 'required|numeric|digits:6'
    ];

    public function verifyOtp()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('otp', 'User not found');
            return;
        }

        if ($user->verifyOTP($this->otp)) {
            // Mark email as verified
            $user->markEmailAsVerified();

            // Log in the user
            Auth::login($user);

            // Redirect to profile completion or dashboard
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                'instructor' => redirect()->route('instructor.dashboard'),
                'supervisor' => redirect()->route('supervisor.dashboard'),
                default => redirect('/unauthorized'),
            };
        }

        $this->addError('otp', 'Invalid or expired OTP');
    }

    public function resendOtp()
    {
        $user = User::where('email', $this->email)->first();

        if ($user) {
            $otp = $user->generateOTP();

            // Use PHPMailerService to send OTP
            $mailer = new PHPMailerService();
            $sent = $mailer->send(
                $user->email,
                'Email Verification',
                "Your new OTP for email verification is: {$otp}"
            );

            if ($sent) {
                session()->flash('status', 'New OTP has been sent to your email');
            } else {
                session()->flash('error', 'Failed to send OTP. Please try again.');
            }
        }
    }
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.email-verification');
    }
}

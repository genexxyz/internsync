<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPVerification extends Mailable
{
    use SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->markdown('emails.otp-verification')
                    ->subject('Email Verification OTP');
    }
}
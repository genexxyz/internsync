<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCredentials extends Mailable
{
    use SerializesModels;

    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Student Account Credentials')
                    ->markdown('emails.student-credentials');
    }
}
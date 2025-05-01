<?php
namespace App\Mail; 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function build()
    {
        return $this->subject('Your Instructor Account Credentials')
                    ->markdown('emails.instructor-credentials')
                    ->with([
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);
    }

    
}

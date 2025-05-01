<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupervisorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $supervisor_name;
    public $company;
    public $department;
    public $address;
    public $reference_link;

    public function __construct($supervisor_name, $company, $department, $address, $reference_link)
    {
        $this->supervisor_name = $supervisor_name;
        $this->company = $company;
        $this->department = $department;
        $this->address = $address;
        $this->reference_link = $reference_link;
    }

    public function build()
    {
        return $this->markdown('emails.supervisor-mail')
                    ->subject('Supervisor Account Creation - InternSync');
    }
}
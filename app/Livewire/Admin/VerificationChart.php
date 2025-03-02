<?php

namespace App\Livewire\Admin;


use Livewire\Component;
use App\Models\User;


class VerificationChart extends Component
{
    public $instructorsVerified;
    public $instructorsPending;
    public $supervisorsVerified;
    public $supervisorsPending;
    public $studentsVerified;
    public $studentsPending;

    public function mount()
{
    $this->instructorsVerified = User::where('role', 'instructor')->where('is_verified', 1)->count() ?: 0;
    $this->instructorsPending = User::where('role', 'instructor')->where('is_verified', 0)->count() ?: 0;

    $this->supervisorsVerified = User::where('role', 'supervisor')->where('is_verified', 1)->count() ?: 0;
    $this->supervisorsPending = User::where('role', 'supervisor')->where('is_verified', 0)->count() ?: 0;

    $this->studentsVerified = User::where('role', 'student')->where('is_verified', 1)->count() ?: 0;
    $this->studentsPending = User::where('role', 'student')->where('is_verified', 0)->count() ?: 0;
}


    public function render()
    {
        return view('livewire.admin.verification-chart');
    }
}


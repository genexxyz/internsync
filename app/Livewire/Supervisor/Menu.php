<?php

namespace App\Livewire\Supervisor;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Menu extends Component
{
    public $supervisor;
    public function mount()
    {
        $this->supervisor = Auth::user()->supervisor;
    }
    public function render()
    {
        
        return view('livewire.supervisor.menu');
    }
}

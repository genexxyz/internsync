<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Menu extends Component
{
    public $isOpen = false;

    public function triggerAlert()
    {
        $this->dispatch('alert', type:'info', text:'Alert');


    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('livewire.admin.menu');
    }
}


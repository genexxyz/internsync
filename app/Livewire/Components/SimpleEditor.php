<?php

namespace App\Livewire\Components;

use Livewire\Component;

class SimpleEditor extends Component
{
    public $content = '';
    public $name;

    protected $listeners = ['contentUpdated'];

    public function mount($name = null)
    {
        $this->name = $name;
    }

    public function contentUpdated($value)
    {
        $this->content = $value;
        $this->dispatch('input', $value);
    }

    public function render()
    {
        return view('livewire.components.simple-editor');
    }
}
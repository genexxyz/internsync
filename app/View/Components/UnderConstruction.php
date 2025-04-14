<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UnderConstruction extends Component
{
    /**
     * Create a new component instance.
     */
    public $message;
    public $submessage;

    public function __construct(
        $message = 'This page is currently under construction.',
        $submessage = "We're working hard to bring you something amazing!"
    ) {
        $this->message = $message;
        $this->submessage = $submessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.under-construction');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlashMessage extends Component
{
    public $message = '';
    public $type;
    public $timeout;

    public function __construct($message, $type = 'success', $timeout = 5000)
    {
        $this->message = $message;
        $this->type = $type;
        $this->timeout = $timeout;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.flash-message');
    }
}

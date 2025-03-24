<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class DailyEntry extends Component
{
    public $journal;
    public $date;
    
    public function __construct($journal, $date)
    {
        $this->journal = $journal;
        $this->date = $date;
    }

    public function render()
    {
        return view('components.daily-entry');
    }
}
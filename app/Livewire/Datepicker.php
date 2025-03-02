<?php

namespace App\Livewire;

use Livewire\Component;

class Datepicker extends Component
{
    public $selectedDate;
    public $minDate;
    public $maxDate;
    public $placeholder;
    public $initialDate;

    protected $listeners = ['updateSelectedDate' => 'updateDate'];

    public function mount($initialDate = null, $minDate = null, $maxDate = null, $placeholder = 'Select a date')
    {
        $this->initialDate = $initialDate ?? now()->format('Y-m-d'); // Default to current date
        $this->minDate = $minDate ?? now()->subYears(1)->format('Y-m-d'); // Default minimum date
        $this->maxDate = $maxDate ?? now()->addYears(1)->format('Y-m-d'); // Default maximum date
        $this->placeholder = $placeholder;
        $this->selectedDate = $this->initialDate;
    }

    public function updatedSelectedDate($value)
    {
        // Emit an event whenever the selected date changes
        $this->dispatch('journalDateUpdated', $value);
    }

    public function updateDate($date)
    {
        // Update the selected date when receiving an event
        $this->selectedDate = $date;
    }

    public function render()
    {
        return view('livewire.datepicker');
    }
}

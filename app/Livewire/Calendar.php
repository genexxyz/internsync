<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Journal;
use App\Models\Attendance;
use Carbon\Carbon;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $daysInMonth;
    public $events = [];
    public $selectedDate;
    public $selectedTasks = [];
    public $showModal = false;

    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->updateCalendar();
    }

    public function updateCalendar()
    {
        $this->daysInMonth = Carbon::create($this->currentYear, $this->currentMonth)->daysInMonth;

        $this->events = Journal::select('date', 'text', 'remarks')
            ->whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->get()
            ->groupBy('date')
            ->toArray();

        $attendance = Attendance::select('date', 'status')
            ->whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->get()
            ->groupBy('date')
            ->toArray();

        foreach ($attendance as $date => $entries) {
            $this->events[$date][] = $entries;
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTasks = $this->events[$date] ?? [];
        $this->showModal = true;
    }

    public function changeMonth($offset)
    {
        $this->currentMonth += $offset;

        if ($this->currentMonth < 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } elseif ($this->currentMonth > 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }

        $this->updateCalendar();
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}


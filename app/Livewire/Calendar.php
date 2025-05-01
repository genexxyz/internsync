<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Journal;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $daysInMonth;
    public $events = [];
    public $selectedDate;
    public $selectedTask = null;
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
    $studentId = Auth::user()->student->id;

    // Get journals with tasks for current month
    $journals = Journal::whereMonth('date', $this->currentMonth)
        ->whereYear('date', $this->currentYear)
        ->where('student_id', $studentId)
        ->with(['tasks' => function($query) {
            $query->orderBy('order', 'asc');
        }])
        ->get();

    // Get attendances for current month
    $attendances = Attendance::whereMonth('date', $this->currentMonth)
        ->whereYear('date', $this->currentYear)
        ->where('student_id', $studentId)
        ->get();

    $attendanceLookup = $attendances->keyBy(fn($attendance) => $attendance->date->format('Y-m-d'));

    // Organize events by date
    foreach ($journals as $journal) {
        $date = $journal->date->format('Y-m-d');
        $attendance = $attendanceLookup->get($date);
        
        // Format times
        $baseDate = date('Y-m-d ');
        $timeIn = $attendance?->time_in ? Carbon::parse($baseDate . $attendance->time_in)->format('h:i A') : null;
        $timeOut = $attendance?->time_out ? Carbon::parse($baseDate . $attendance->time_out)->format('h:i A') : null;
        $startBreak = $attendance?->start_break ? Carbon::parse($baseDate . $attendance->start_break)->format('h:i A') : null;
        $endBreak = $attendance?->end_break ? Carbon::parse($baseDate . $attendance->end_break)->format('h:i A') : null;

        $this->events[$date] = [
            'text' => $journal->text,
            'is_approved' => $journal->is_approved,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'start_break' => $startBreak,
            'end_break' => $endBreak,
            'total_hours' => $attendance?->total_hours ?? '00:00',
            'status' => $attendance?->status ?? 'No Attendance',
            'tasks' => $journal->tasks->map(fn($task) => [
                'description' => $task->description,
                'order' => $task->order,
            ])->toArray()
        ];
    }
}
    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTask = $this->events[$date] ?? null;
        $this->showModal = true;
    }

    public function changeMonth($offset)
    {
        $currentDate = Carbon::create($this->currentYear, $this->currentMonth)->addMonths($offset);
        $this->currentMonth = $currentDate->month;
        $this->currentYear = $currentDate->year;
        $this->updateCalendar();
    }

    public function generateDtr()
{
    return redirect()->route('student.dtr.generate', [
        'month' => $this->currentMonth,
        'year' => $this->currentYear
    ]);
}

    public function render()
    {
        return view('livewire.calendar');
    }
}
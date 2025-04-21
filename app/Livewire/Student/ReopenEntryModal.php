<?php
namespace App\Livewire\Student;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Models\ReopenRequest;
use App\Models\Journal;
use App\Models\Attendance;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskHistory;
use Carbon\Carbon;

class ReopenEntryModal extends ModalComponent
{
    public $selectedDate = null;
    public $reopenRequests = [];
    public $journal;
    public $attendance;
    
    public $timeIn;
    public $timeOut;
    public $startBreak;
    public $endBreak;
    
    public $tasks = [];
    public $newTask = '';

    protected $rules = [
        'timeIn' => 'required|date_format:H:i',
        'timeOut' => 'required|date_format:H:i|after:timeIn',
        'startBreak' => 'nullable|date_format:H:i|after:timeIn',
        'endBreak' => 'nullable|date_format:H:i|after:startBreak|before:timeOut',
    ];

    public function mount()
    {
        $this->reopenRequests = ReopenRequest::where('student_id', Auth::user()->student->id)
            ->where('status', 'PENDING')
            ->where('expires_at', '>', now())
            ->get();
    }

    public function updatedSelectedDate($value)
    {
        if ($value) {
            $this->loadEntryData($value);
        }
    }

    protected function loadEntryData($date)
    {
        // Load journal and tasks
        $this->journal = Journal::with('taskHistories', 'tasks')->where('student_id', Auth::user()->student->id)
            ->where('date', $date)
            ->first();

        // Load attendance
        $this->attendance = Attendance::where('student_id', Auth::user()->student->id)
            ->where('date', $date)
            ->first();

        if ($this->attendance) {
            $this->timeIn = $this->attendance->time_in ? Carbon::parse($this->attendance->time_in)->format('H:i') : null;
            $this->timeOut = $this->attendance->time_out ? Carbon::parse($this->attendance->time_out)->format('H:i') : null;
            $this->startBreak = $this->attendance->start_break ? Carbon::parse($this->attendance->start_break)->format('H:i') : null;
            $this->endBreak = $this->attendance->end_break ? Carbon::parse($this->attendance->end_break)->format('H:i') : null;
        }

        // Load tasks
        $this->tasks = $this->journal ? $this->journal->tasks->map(function($task) {
            return [
                'id' => $task->id,
                'description' => $task->description,
                'status' => $task->histories->first()->status ?? 'pending'
            ];
        })->toArray() : [];
    }

    public function addTask()
    {
        if (empty($this->newTask)) return;

        $this->tasks[] = [
            'description' => $this->newTask,
            'status' => 'pending'
        ];

        $this->newTask = '';
    }

    public function removeTask($index)
    {
        unset($this->tasks[$index]);
        $this->tasks = array_values($this->tasks);
    }

    public function updateTaskStatus($index, $status)
    {
        $this->tasks[$index]['status'] = $status;
    }

    public function saveChanges()
    {
        $this->validate();

        try {
            // Save attendance
            $attendanceData = [
                'student_id' => Auth::user()->student->id,
                'date' => $this->selectedDate,
                'time_in' => $this->timeIn,
                'time_out' => $this->timeOut,
                'start_break' => $this->startBreak,
                'end_break' => $this->endBreak,
                'total_hours' => $this->calculateTotalHours($this->timeIn, $this->timeOut),
                'is_approved' => 0
            ];

            if ($this->attendance) {
                $this->attendance->update($attendanceData);
            } else {
                $this->attendance = Attendance::create($attendanceData);
            }

            // Save journal and tasks
            if (!$this->journal) {
                $this->journal = Journal::create([
                    'student_id' => Auth::user()->student->id,
                    'date' => $this->selectedDate,
                    'is_approved' => 0
                ]);
            }

            // Update task histories
            foreach ($this->tasks as $task) {
                $taskModel = Task::updateOrCreate(
                    ['id' => $task['id'] ?? null],
                    ['description' => $task['description']]
                );

                TaskHistory::create([
                    'task_id' => $taskModel->id,
                    'journal_id' => $this->journal->id,
                    'status' => $task['status'],
                    'changed_at' => now()
                ]);
            }

            // Mark reopen request as completed
        $reopenRequest = ReopenRequest::where('student_id', Auth::user()->student->id)
        ->where('reopened_date', $this->selectedDate)
        ->where('status', 'PENDING')
        ->first();

    if ($reopenRequest) {
        $reopenRequest->update(['status' => 'COMPLETED']);
        
        // Send notification with better context
        $fullName = Auth::user()->student->name();
        $formattedDate = Carbon::parse($this->selectedDate)->format('F d, Y');
        
        Notification::send(
            $reopenRequest->supervisor->user_id,
            'reopen_entry_completed',
            'Reopened Entry Completed',
            "{$fullName} has updated their entry for {$formattedDate}. Please review the changes.",
            'supervisor.dailyReports',
            'fa-calendar-check'
        );

    }

    $this->dispatch('alert', type: 'success', text: 'Entry updated successfully!');
    $this->dispatch('dailyEntryUpdated');
    $this->dispatch('closeModal');
        
} catch (\Exception $e) {
    logger()->error('Error updating reopened entry', [
        'error' => $e->getMessage(),
        'student_id' => Auth::user()->student->id,
        'date' => $this->selectedDate
    ]);
    
    $this->dispatch('alert', type: 'error', text: 'Error saving changes: ' . $e->getMessage());
}
    }
    private function calculateTotalHours($timeIn, $timeOut)
    {
        try {
            // Convert strings to Carbon instances if they aren't already
            $timeIn = $timeIn instanceof Carbon ? $timeIn : Carbon::parse($timeIn);
            $timeOut = $timeOut instanceof Carbon ? $timeOut : Carbon::parse($timeOut);

            // Use diffInMinutes with absolute value to get correct duration
            $totalMinutes = abs($timeOut->diffInMinutes($timeIn));
            
            logger()->info('Time calculation', [
                'timeIn' => $timeIn->format('H:i:s'),
                'timeOut' => $timeOut->format('H:i:s'),
                'totalMinutes' => $totalMinutes
            ]);

            // Calculate break duration if break was taken
            if ($this->startBreak && $this->endBreak) {
                $breakStart = Carbon::parse($this->startBreak);
                $breakEnd = Carbon::parse($this->endBreak);
                $breakMinutes = abs($breakEnd->diffInMinutes($breakStart));
                
                logger()->info('Break calculation', [
                    'breakStart' => $breakStart->format('H:i:s'),
                    'breakEnd' => $breakEnd->format('H:i:s'),
                    'breakMinutes' => $breakMinutes
                ]);

                $totalMinutes = max(0, $totalMinutes - $breakMinutes);
            }

            // Calculate hours and minutes
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            logger()->info('Final calculation', [
                'totalMinutes' => $totalMinutes,
                'hours' => $hours,
                'minutes' => $minutes
            ]);

            return sprintf('%02d:%02d:00', $hours, $minutes);
        } catch (\Exception $e) {
            logger()->error('Error calculating total hours', [
                'error' => $e->getMessage(),
                'timeIn' => $timeIn ?? 'null',
                'timeOut' => $timeOut ?? 'null',
                'attendance' => $this->attendance
            ]);
            
            return '00:00:00';
        }
    }
    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function render()
    {
        return view('livewire.student.reopen-entry-modal');
    }
}
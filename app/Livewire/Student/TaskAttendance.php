<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Notification;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskAttendance extends Component
{
    // Time tracking properties
    public $dayToday;
    public $currentTime;
    public $attendance;
    public $isOnBreak = false;
    public $showJournalModal = false;
    public $journalText = '';
    public $remarks = '';
    public $isSubmitted = false;
    public $isEditing = false;
    public $journalId = null;
    public $existingJournal = null;
    public $deployment;
    public $attendanceStatus = 'regular';

    protected $listeners = ['refreshTaskAttendance' => 'refreshTask'];

public function refreshTask()
{
    $this->loadAttendance();
    $this->loadJournal();
}
   
    public function mount()
    {
        $this->deployment = Auth::user()->student->deployment;

        if ($this->deployment && 
            $this->deployment->starting_date && 
            Carbon::parse($this->deployment->starting_date)->lte(now())) {
                $this->dayToday = now()->format('l, F j, Y');
                $this->updateCurrentTime();
                $this->loadAttendance();
                $this->loadJournal();
        }
    }

    public function updateCurrentTime()
    {
        $this->currentTime = now()->format('h:i A');
    }

    public function timeIn()
    {
        // Check if already timed in today
        if ($this->attendance) {
            $this->addError('all', 'Already timed in for today');
            return;
        }

        // Create attendance record
        $attendance = Attendance::create([
            'student_id' => Auth::user()->student->id,
            'date' => now()->toDateString(),
            'time_in' => now()->format('H:i:s'),
            'status' => $this->attendanceStatus,
        ]);

        // Handle absent status automatically
        if ($this->attendanceStatus === 'absent') {
            $attendance->update([
                'time_out' => now()->format('H:i:s'),
                'total_hours' => '00:00:00'
            ]);

            // Automatically create a journal entry with "absent" text
            Journal::create([
                'student_id' => Auth::user()->student->id,
                'date' => now()->toDateString(),
                'text' => 'Absent',
                'remarks' => 'absent',
                'is_submitted' => false
            ]);

            $this->loadJournal();
        }

        // Reload attendance to update the component state
        $this->loadAttendance();
    }

    public function startBreak()
    {
        // Add logging for debugging
        logger()->info('startBreak called', [
            'attendance' => $this->attendance,
            'isOnBreak' => $this->isOnBreak
        ]);

        if (!$this->attendance) {
            $this->addError('all', 'Must time in first');
            return;
        }

        if ($this->attendance->time_out) {
            $this->addError('all', 'Cannot start break after time out');
            return;
        }

        if ($this->attendance->start_break) {
            $this->addError('all', 'Break already started');
            return;
        }

        try {
            // Update attendance to mark break started
            $this->attendance->update([
                'start_break' => now()->format('H:i:s'),
            ]);

            $this->isOnBreak = true;
            $this->loadAttendance();
        } catch (\Exception $e) {
            logger()->error('Error starting break', [
                'error' => $e->getMessage()
            ]);
            $this->addError('all', 'Failed to start break');
        }
    }

    public function endBreak()
    {
        if (!$this->attendance->start_break || $this->attendance->end_break) {
            $this->addError('all', 'No break in progress');
            return;
        }

        // Update attendance with break end time
        $this->attendance->update([
            'end_break' => now()->format('H:i:s'),
        ]);

        $this->isOnBreak = false;
        $this->loadAttendance();
    }

    public function timeOut()
{
    if (!$this->attendance) {
        $this->addError('all', 'Must time in first');
        return;
    }

    if ($this->attendance->time_out) {
        $this->addError('all', 'Already timed out for today');
        return;
    }

    if ($this->isOnBreak) {
        $this->addError('all', 'Please end your break first');
        return;
    }

    // Get minimum minutes from settings
    $minimumMinutes = Setting::first()->minimum_minutes ?? 240; // Default to 4 hours (240 minutes)

    // Calculate elapsed minutes
    $timeIn = Carbon::parse($this->attendance->time_in);
    $now = now();
    $elapsedMinutes = $timeIn->diffInMinutes($now);

    // Consider break time if taken
    if ($this->attendance->start_break && $this->attendance->end_break) {
        $breakStart = Carbon::parse($this->attendance->start_break);
        $breakEnd = Carbon::parse($this->attendance->end_break);
        $breakMinutes = $breakStart->diffInMinutes($breakEnd);
        $elapsedMinutes -= $breakMinutes;
    }

    // Check if minimum duration is met
    if ($elapsedMinutes < $minimumMinutes) {
        $remainingMinutes = $minimumMinutes - $elapsedMinutes;
        $remainingHours = ceil($remainingMinutes / 60);
        $remainingMinutes = $remainingMinutes % 60;
        $this->addError('all', 
            "You must complete at least {$minimumMinutes} minutes of work. " . 
            "Please work for {$remainingMinutes} more minutes."
        );
        return;
    }

    $timeOut = now();
    $totalHours = $this->calculateTotalHours(
        $this->attendance->time_in,
        $timeOut->format('H:i:s')
    );

    // Update attendance record
    $this->attendance->update([
        'time_out' => $timeOut->format('H:i:s'),
        'total_hours' => $totalHours,
    ]);

    $this->loadAttendance();
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
            if ($this->attendance->start_break && $this->attendance->end_break) {
                $breakStart = Carbon::parse($this->attendance->start_break);
                $breakEnd = Carbon::parse($this->attendance->end_break);
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

    private function loadAttendance()
    {
        $this->attendance = Attendance::where('student_id', Auth::user()->student->id)
            ->where('date', now()->toDateString())
            ->first();

        if ($this->attendance) {
            $this->isOnBreak = $this->attendance->start_break && !$this->attendance->end_break;
        }
    }

    private function loadJournal()
    {
        $this->existingJournal = Journal::where('student_id', Auth::user()->student->id)
            ->where('date', now()->toDateString())
            ->first();

        if ($this->existingJournal) {
            $this->journalId = $this->existingJournal->id;
            $this->journalText = $this->existingJournal->text;
            $this->remarks = $this->existingJournal->remarks;
            $this->isEditing = true;
            $this->isSubmitted = $this->existingJournal->is_submitted;
        } else {
            $this->reset(['journalId', 'journalText', 'remarks', 'isEditing', 'isSubmitted']);
        }
    }

    public function toggleJournalModal()
    {
        
        if (!$this->attendance) {
            return;
        }

        // Check for existing journal
        $this->existingJournal = Journal::where('student_id', Auth::user()->student->id)
            ->where('date', now()->toDateString())
            ->first();

        if ($this->existingJournal) {
            $this->isEditing = true;
            $this->journalId = $this->existingJournal->id;
            $this->journalText = $this->existingJournal->text;
            $this->remarks = $this->existingJournal->remarks;
        }
        $this->loadJournal();
        $this->showJournalModal = true;
    }

    public function saveJournal()
    {
        $this->validate([
            'journalText' => 'required|string|min:10',
            'remarks' => 'required|in:done,pending'
        ]);

        try {
            if ($this->isEditing && $this->journalId) {
                Journal::find($this->journalId)->update([
                    'text' => $this->journalText,
                    'remarks' => $this->remarks,
                    'updated_at' => now()
                ]);
                $message = 'Journal updated successfully.';
            } else {
                Journal::create([
                    'student_id' => Auth::user()->student->id,
                    'date' => now()->toDateString(),
                    'text' => $this->journalText,
                    'remarks' => $this->remarks,
                    'is_submitted' => false
                ]);
                $message = 'Journal saved successfully.';
            }

            $this->reset(['journalText', 'remarks', 'isEditing', 'journalId']);
            $this->showJournalModal = false;
            $this->loadJournal();
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save journal.');
        }
    }

    public function submitJournal()
    {
        if (!$this->attendance || !$this->attendance->time_out || !$this->existingJournal) {
            session()->flash('error', 'Cannot submit journal. Please ensure all required information is provided.');
            return;
        }

        try {
            // Update the journal to mark it as submitted
            $this->existingJournal->update([
                'is_submitted' => true,
                'updated_at' => now()
            ]);

            $this->isSubmitted = true;
$fullname = Auth::user()->student->name();
$currentDate = now()->format('F j, Y');
            Notification::send(
                $this->deployment->supervisor->user_id,
                'journal_submitted',
                'Daily Journal Entry Submitted',
                'Journal entry of ' . $fullname . ' for ' . $currentDate . ' has been submitted for review.',
                'supervisor.dailyReports',
                'fa-calendar-days'
            );
            $this->dispatch('alert', type: 'success', text: 'Journal submitted successfully.');
            
            $this->loadJournal(); // Reload journal to refresh the state
            
        } catch (\Exception $e) {
            logger()->error('Error submitting journal', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error submitting.');
            session()->flash('error', 'Failed to submit journal.');
        }
    }

    public function render()
    {
        $this->updateCurrentTime();
        return view('livewire.student.task-attendance');
    }
}
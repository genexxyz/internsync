<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Journal;
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
    // Validation rules

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
            $this->addError('timeIn', 'Already timed in for today');
            return;
        }

        // Create attendance record
        Attendance::create([
            'student_id' => Auth::user()->student->id,
            'date' => now()->toDateString(),
            'time_in' => now()->format('H:i:s'),
            'status' => 'regular'
        ]);

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
            $this->addError('break', 'Must time in first');
            return;
        }

        if ($this->attendance->time_out) {
            $this->addError('break', 'Cannot start break after time out');
            return;
        }

        if ($this->attendance->start_break) {
            $this->addError('break', 'Break already started');
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
            $this->addError('break', 'Failed to start break');
        }
    }

    public function endBreak()
    {
        if (!$this->attendance->start_break || $this->attendance->end_break) {
            $this->addError('break', 'No break in progress');
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
            $this->addError('timeOut', 'Must time in first');
            return;
        }

        if ($this->attendance->time_out) {
            $this->addError('timeOut', 'Already timed out for today');
            return;
        }

        if ($this->isOnBreak) {
            $this->addError('timeOut', 'Please end your break first');
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
                'submitted_at' => now()
            ]);

            $this->isSubmitted = true;
            session()->flash('message', 'Journal submitted successfully.');
            $this->loadJournal(); // Reload journal to refresh the state
            
        } catch (\Exception $e) {
            logger()->error('Error submitting journal', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'Failed to submit journal.');
        }
    }
    public function render()
    {
        $this->updateCurrentTime();
        return view('livewire.student.task-attendance');
    }
}

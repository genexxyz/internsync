<?php


namespace App\Livewire\Supervisor;

use App\Models\Journal;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReopenRequest;
use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class DailyReportReview extends ModalComponent
{
    public $journalId;
    public $journal;
    public $student;
    public $feedbackNote;
    public $reopenEntry = true;
    public $showFeedbackForm = false;
    public $isInApprovedWeeklyReport = false;

    public function mount($journal)
    {
        $this->journalId = $journal;
        $this->loadJournalData();
    }

    public function loadJournalData()
{
    $this->journal = Journal::with([
        'student',
        'attendance',
        'tasks' => function($query) {
            $query->orderBy('order', 'asc');
        }
    ])->findOrFail($this->journalId);
    
    $this->student = $this->journal->student;
    
    // Check if journal date is in an approved weekly report
    $this->isInApprovedWeeklyReport = Report::where('student_id', $this->student->id)
        ->where('status', 'approved')
        ->where('start_date', '<=', $this->journal->date)
        ->where('end_date', '>=', $this->journal->date)
        ->exists();
}

    

    public function approveEntry()
    {
        if ($this->journal) {
            // Update journal status
            $this->journal->update([
                'is_approved' => 1,
                'feedback' => $this->feedbackNote,
                'reviewed_at' => now()
            ]);

            // Update attendance status if exists
            if ($this->journal->attendance) {
                $this->journal->attendance->update([
                    'is_approved' => 1,
                ]);
            }
            Notification::send(
                $this->journal->student->user->id,
                'approved_entry',
                'Entry Approved',
                "Your journal entry for " . Carbon::parse($this->journal->date)->format('F d, Y') . " has been approved.",
                'student.taskAttendance',
                'fa-check-circle',
            );
            $this->dispatch('alert', type: 'success', text: 'Entry approved!');
            $this->dispatch('dailyEntryUpdated');
            $this->closeModal();
        }
    }

    
    public function rejectEntry()
    {
        $this->validate([
            'feedbackNote' => 'required|min:10',
        ], [
            'feedbackNote.required' => 'Please provide feedback before rejecting the entry.',
            'feedbackNote.min' => 'Feedback should be at least 10 characters long.'
        ]);
    
        if ($this->journal) {
            try {
                // Check for existing pending or expired request
                $existingRequest = ReopenRequest::where('student_id', $this->journal->student_id)
                    ->where('reopened_date', $this->journal->date)
                    ->whereIn('status', ['PENDING', 'EXPIRED'])
                    ->first();
    
                if ($existingRequest) {
                    $statusMessage = $existingRequest->status === 'PENDING' 
                        ? 'There is already a pending reopen request for this date.'
                        : 'This date has an expired reopen request and cannot be reopened again.';
                    
                    $this->addError('feedbackNote', $statusMessage);
                    return;
                }
    
                // Update journal status
                $this->journal->update([
                    'is_approved' => 2,
                    'feedback' => $this->feedbackNote,
                    'reviewed_at' => now()
                ]);
    
                // Update attendance status if exists
                if ($this->journal->attendance) {
                    $this->journal->attendance->update([
                        'is_approved' => 2,
                    ]);
                }
    
                // Create reopen request if reopenEntry is true
                if ($this->reopenEntry) {
                    ReopenRequest::create([
                        'student_id' => $this->journal->student_id,
                        'supervisor_id' => Auth::user()->supervisor->id,
                        'reopened_date' => $this->journal->date,
                        'expires_at' => now()->addHours(24),
                        'message' => $this->feedbackNote,
                        'status' => 'PENDING'
                    ]);
                    Notification::send(
                        $this->journal->student->user->id,
                        'reopen_rejected_entry',
                        'Entry Rejected',
                        "Your journal entry for " . Carbon::parse($this->journal->date)->format('F d, Y') . "has been rejected. Please review the feedback provided and take action immediately.",
                        'student.taskAttendance',
                        'fa-circle-exclamation',
                    );
                }
                else {
                    Notification::send(
                        $this->journal->student->user->id,
                        'rejected_entry',
                        'Entry Rejected',
                        "Your journal entry for " . Carbon::parse($this->journal->date)->format('F d, Y') . " has been rejected. Please review the feedback provided.",
                        'student.taskAttendance',
                        'fa-xmark',
                    );
                }
    $this->dispatch('alert', type: 'success', text: 'Entry rejected!');
                $this->dispatch('dailyEntryUpdated');
                $this->closeModal();
    
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to reject entry. Please try again.');
            }
        }
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render()
    {
        return view('livewire.supervisor.daily-report-review');
    }
}
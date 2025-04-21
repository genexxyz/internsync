<?php

namespace App\Livewire\Supervisor;

use App\Models\Notification;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\ReopenRequest;
use App\Models\Report;


class JournalReopenModal extends ModalComponent
{
    public $date;
    public $selectedStudent = '';
    public $expiry;
    public $message = '';
    public $students = [];

    public function mount($date)
    {
        $this->date = $date;
        
        // Get students assigned to the supervisor
        $this->students = Student::whereHas('deployment', function($query) {
            $query->where('supervisor_id', Auth::user()->supervisor->id)
                  ;
        })
        ->with(['user', 'deployment.department'])
        ->get()
        ->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
            ];
        });
    }

    
    
public function reopen()
{
    $this->validate([
        'selectedStudent' => 'required|exists:students,id',
        'date' => 'required|date|before:today',
        'message' => 'nullable|string|max:500'
    ]);

    try {
        // Check if date is in an approved weekly report
        $isInApprovedReport = Report::where('student_id', $this->selectedStudent)
            ->where('status', 'approved')
            ->where(function($query) {
                $query->whereDate('start_date', '<=', $this->date)
                      ->whereDate('end_date', '>=', $this->date);
            })
            ->exists();

        if ($isInApprovedReport) {
            $this->addError('date', 'This date is part of an approved weekly report and cannot be reopened.');
            return;
        }

        // Check for existing pending or expired request
        $existingRequest = ReopenRequest::where('student_id', $this->selectedStudent)
            ->where('reopened_date', $this->date)
            ->whereIn('status', ['PENDING', 'EXPIRED'])
            ->first();

        if ($existingRequest) {
            $statusMessage = $existingRequest->status === 'PENDING' 
                ? 'There is already a pending reopen request for this date.'
                : 'This date has an expired reopen request and cannot be reopened again.';
            
            $this->addError('date', $statusMessage);
            return;
        }

        // Create new reopen request
        ReopenRequest::create([
            'student_id' => $this->selectedStudent,
            'supervisor_id' => Auth::user()->supervisor->id,
            'reopened_date' => $this->date,
            'expires_at' => now()->addHours(24),
            'message' => $this->message,
            'status' => 'PENDING'
        ]);
Notification::send(
            $this->selectedStudent,
            'reopen_request',
            'Reopen Request Submitted',
            "Your request to reopen the journal entry for {$this->date} has been submitted. Please wait for the supervisor's approval.",
            'student.journal',
            'fa-calendar-check'
        );
        $this->dispatch('dailyEntryUpdated');
        $this->dispatch('closeModal');
        
        session()->flash('message', 'Journal entry has been reopened for editing.');
        
    } catch (\Exception $e) {
        $this->addError('general', 'Failed to reopen journal entry. Please try again.');
    }
}
}
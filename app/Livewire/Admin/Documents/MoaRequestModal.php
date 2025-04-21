<?php
namespace App\Livewire\Admin\Documents;

use LivewireUI\Modal\ModalComponent;
use App\Models\MoaRequest;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class MoaRequestModal extends ModalComponent
{
    public MoaRequest $request;
    public $adminRemarks;
    public $receivedByStudent;
    public $companyStudents = [];
    
    public function mount(MoaRequest $request)
    {
        $this->request = $request;
        $this->adminRemarks = $request->admin_remarks;
        $this->receivedByStudent = $request->received_by_student;
        
        // Load all active students from this company
        $this->companyStudents = Student::whereHas('deployment', function($query) use ($request) {
            $query->where('company_id', $request->company_id)
                  ;
        })->with(['section.course'])->get();
    }

    public function updateStatus($newStatus)
    {
        $this->validate([
            'receivedByStudent' => $newStatus === 'picked_up' ? 'required|string|min:3' : '',
        ], [
            'receivedByStudent.required' => 'Please enter the name of the student who picked up the MOA',
            'receivedByStudent.min' => 'Student name must be at least 3 characters',
        ]);

        $statusTimestamp = match($newStatus) {
            'for_pickup' => ['for_pickup_at' => now()],
            'picked_up' => [
                'picked_up_at' => now(),
                'received_by_student' => $this->receivedByStudent
            ],
            default => []
        };

        $this->request->update([
            'status' => $newStatus,
            'admin_remarks' => $this->adminRemarks,
            'admin_id' => Auth::user()->admin->id,
            ...$statusTimestamp
        ]);

        // Create notification
        $message = match($newStatus) {
            
            'for_pickup' => "Great news! Your MOA is now ready for pickup at the OJT Office. Please bring a valid ID when collecting the document.",
            'picked_up' => "Your MOA has been successfully picked up by {$this->receivedByStudent}. Make sure to submit it to your company supervisor promptly.",
            default => "Your MOA request status has been updated to " . str_replace('_', ' ', $newStatus)
        };

        $icon = match($newStatus) {
            'for_pickup' => 'fa-envelope',
            'picked_up' => 'fa-check-circle',
            default => 'fa-file-alt'
        };

        $title = match($newStatus) {
            'for_pickup' => 'MOA Ready for Pickup',
            'picked_up' => 'MOA Picked Up',
            default => 'MOA Status Update'
        };

        // Send notification to student
        Notification::send(
            $this->request->student->user_id,
            'moa_' . $newStatus,
            $title,
            $message,
            'student.document',
            $icon
        );
$this->dispatch('alert', 
            type:'success',
            text: "MOA request status updated to " . str_replace('_', ' ', $newStatus)
        );
        $this->dispatch('moaStatusUpdated');
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.admin.documents.moa-request-modal');
    }
}
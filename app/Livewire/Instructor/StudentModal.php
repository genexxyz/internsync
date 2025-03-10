<?php

namespace App\Livewire\Instructor;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class StudentModal extends ModalComponent
{
    public Student $user;
    public $company;
    public $supervisor;
    public $isProgramHead = false;
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function getDocumentUrl()
    {
        try {
            if ($this->user->supporting_doc) {
                $path = $this->user->supporting_doc;
                // Check if the file exists in the public disk
                if (Storage::disk('public')->exists($path)) {
                    return URL::temporarySignedRoute(
                        'document.preview',
                        now()->addMinutes(30),
                        ['path' => $path]
                    );
                }
            }
        } catch (\Exception $e) {
            logger()->error('Error generating document URL', [
                'error' => $e->getMessage(),
                'document' => $this->user->supporting_doc ?? 'No document'
            ]);
        }
        return null;
    }
    public function mount(Student $student)
    {
        $this->user = $student->load([
            'deployment',
            'user',
            'yearSection',
        ]);

        // Check if current user is program head
        $this->isProgramHead = Auth::user()->instructor->is_program_head ?? false;

        if ($this->user->deployment) {
            $this->company = Company::find($this->user->deployment->company_id);
            $this->supervisor = Supervisor::find($this->user->deployment->supervisor_id);
        } else {
            $this->company = null;
            $this->supervisor = null;
        }
    }

    public function verifyStudent()
    {
        if (!$this->user->user) {
            $this->dispatch('alert', type: 'error', text: 'Error verifying!');
            return;
        }

        $this->user->user->update(['is_verified' => 1]);
        $this->dispatch('alert', type: 'success', text: 'The student has been verified!');
    }

    public function deleteStudent()
    {
        // Ensure the user and instructor exist
        if ($this->user->user) {
            // Delete the user record
            $this->user->user->delete();
        }

        // Delete the instructor record
        $this->user->delete();

        // Dispatch success notification
        $this->dispatch('closeModal');
        $this->dispatch('alert', type: 'success', text: 'The student has been deleted successfully!');
    }

    public function render()
    {
        return view('livewire.instructor.student-modal', [
            'student' => $this->user,
            'company' => $this->company,  // Pass the company to the view
            'supervisor' => $this->supervisor,
        ]);
    }
}

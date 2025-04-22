<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Notification;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class InstructorModal extends ModalComponent
{
    public Instructor $user;
    public $isEditing = false;
    public $editableData = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'instructor_id' => '',
        'contact' => '',
    ];

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

// // Get all sections for an instructor with their courses
// $sections = Instructor::find(1)->sections()->with('course')->get();

// // Get all instructors for a section
// $instructors = Section::find(1)->instructors;

// // Get the course for a section
// $course = Section::find(1)->course;


public function mount(Instructor $instructor)
    {
        $this->user = $instructor->load([
            'sections.course',
            'instructorCourse.course',
            'handles',
            'user'
        ]);
        
        $this->editableData = [
            'first_name' => $this->user->first_name,
            'middle_name' => $this->user->middle_name,
            'last_name' => $this->user->last_name,
            'suffix' => $this->user->suffix,
            'instructor_id' => $this->user->instructor_id,
            'contact' => $this->user->contact,
        ];
    }
    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveChanges()
{
    $this->validate([
        'editableData.first_name' => 'required|string|max:255',
        'editableData.last_name' => 'required|string|max:255',
        'editableData.instructor_id' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) {
                $exists = Instructor::where('instructor_id', $value)
                    ->where('id', '!=', $this->user->id)
                    ->exists();
                
                if ($exists) {
                    $fail('This instructor ID is already taken.');
                }
            },
        ],
        'editableData.contact' => 'required|string|max:255',
    ]);

    try {
        $this->user->update([
            'first_name' => $this->editableData['first_name'],
            'middle_name' => $this->editableData['middle_name'],
            'last_name' => $this->editableData['last_name'],
            'suffix' => $this->editableData['suffix'],
            'instructor_id' => $this->editableData['instructor_id'],
            'contact' => $this->editableData['contact'],
        ]);
        
        $this->isEditing = false;
        $this->dispatch('alert', type: 'success', text: 'Profile updated successfully!');
        $this->dispatch('refreshInstructors');
        
    } catch (\Exception $e) {
        logger()->error('Error updating instructor', [
            'error' => $e->getMessage(),
            'instructor' => $this->user->id,
            'data' => $this->editableData
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error updating profile.');
    }
}
public function verifyInstructor()
{
    if (!$this->user->user) {
        $this->dispatch('alert', type: 'error', text: 'Error verifying!');
        return;
    }
 // Add more specific validation
 if (!$this->user || !$this->user->user) {
    $this->dispatch('alert', 
        type: 'error', 
        text: 'Cannot verify instructor: User account not found!'
    );
    return;
}



try {
        // Begin transaction
        DB::beginTransaction();

        // Update instructor course verification
        if ($this->user->instructorCourse) {
            $this->user->instructorCourse->update(['is_verified' => 1]);
        DB::table('instructor_courses')
            ->where('course_id', $this->user->instructorCourse->course_id)
            ->where('instructor_id', '!=', $this->user->id)
            ->delete();
        }
        
        // Update user verification
        $this->user->user->update(['is_verified' => 1]);

        // Get all sections handled by this instructor
        $handledSections = $this->user->handles;

        foreach ($handledSections as $section) {
            // Verify this instructor's section
            $section->update(['is_verified' => 1]);

            // Delete duplicate section assignments for other instructors
            DB::table('instructor_sections')
                ->where('year_section_id', $section->year_section_id)
                ->where('instructor_id', '!=', $this->user->id)
                ->delete();
        }

        DB::commit();
Notification::send(
            $this->user->user_id,
            'instructor_verified',
            'Account Verified',
            'Your instructor account has been verified successfully.',
            '',
            'fa-user-check'
        );
        $this->dispatch('refreshInstructors');
        $this->dispatch('alert', type: 'success', text: 'The instructor has been verified!');
        $this->dispatch('closeModal');

    } catch (\Exception $e) {
        DB::rollBack();
        logger()->error('Error verifying instructor', [
            'error' => $e->getMessage(),
            'instructor_id' => $this->user->id,
            'user_id' => $this->user->user->id ?? null,
            'course_id' => $this->user->instructorCourse->course_id ?? null
        ]);
        $this->dispatch('alert', 
            type: 'error', 
            text: 'Error verifying instructor: ' . $e->getMessage()
        );
    }
}



    
    public function deleteInstructor()
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
        $this->dispatch('refreshInstructors');
        $this->dispatch('alert', type: 'success', text: 'The instructor has been deleted successfully!');

    }
    public function render()
    {
        return view('livewire.admin.instructor-modal', [
            'instructor' => $this->user
        ]);
    }
}

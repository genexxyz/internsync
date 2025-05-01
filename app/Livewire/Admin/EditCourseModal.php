<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Academic;
use App\Models\Instructor;
use App\Models\Program;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\DB;

class EditCourseModal extends ModalComponent
{
    public Course $course;
    public $courseCode;
    public $courseName;
    public $instructorId;
    public $currentInstructor;
    public $availableInstructors;
    
    public function rules()
    {
        return [
            'courseCode' => [
                'required',
                'string',
                'max:20',
                "unique:courses,course_code,{$this->course->id}"
            ],
            'courseName' => [
                'required',
                'string',
                'max:255',
                "unique:courses,course_name,{$this->course->id}"
            ],
            'instructorId' => 'nullable|exists:instructors,id'
        ];
    }

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->courseCode = $course->course_code;
        $this->courseName = $course->course_name;
        
        // Get current academic year
        $currentAcademic = Academic::where('ay_default', true)->first();
        
        // Get current program head if exists
        $currentProgram = Program::where('course_id', $course->id)
            ->where('academic_year_id', $currentAcademic->id)
            ->with('instructor')
            ->first();
            
        $this->currentInstructor = $currentProgram?->instructor;
        $this->instructorId = $currentProgram?->instructor_id;
        
        // Get all available instructors
        $this->availableInstructors = Instructor::orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => "{$instructor->last_name}, {$instructor->first_name}"
                ];
            });
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update course details
            $this->course->update([
                'course_code' => $this->courseCode,
                'course_name' => $this->courseName,
            ]);

            // Handle program head assignment
            $currentAcademic = Academic::where('ay_default', true)->first();
            
            // Remove current program head if exists
            Program::where('course_id', $this->course->id)
                ->where('academic_year_id', $currentAcademic->id)
                ->delete();
            
            // Assign new program head if selected
            if ($this->instructorId) {
                Program::create([
                    'instructor_id' => $this->instructorId,
                    'course_id' => $this->course->id,
                    'academic_year_id' => $currentAcademic->id,
                    'is_verified' => true
                ]);
            }

            DB::commit();
            
            $this->dispatch('alert', type: 'success', text: 'Course updated successfully.');
            $this->dispatch('refreshCourses');
            $this->dispatch('closeModal');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', text: 'Error updating course.');
        }
    }

    public function deleteCourse()
{
    try {
        DB::beginTransaction();

        // Check if course exists and is accessible
        $course = Course::findOrFail($this->course->id);

        // Check if course has active sections with students
        if ($course->sections()->whereHas('students')->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete course with active students.');
            return;
        }

        // Delete all program head assignments
        Program::where('course_id', $course->id)->delete();

        // Delete all sections
        $course->sections()->delete();

        // Finally delete the course
        $course->delete();

        DB::commit();

        $this->dispatch('alert', type: 'success', text: 'Course deleted successfully.');
        redirect()->route('admin.courses');
        $this->dispatch('closeModal');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        $this->dispatch('alert', type: 'error', text: 'Course not found.');
        $this->dispatch('closeModal');
    } catch (\Exception $e) {
        DB::rollBack();
        logger()->error('Error deleting course:', [
            'course_id' => $this->course->id,
            'error' => $e->getMessage()
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting course.');
    }
}

    public function render()
    {
        return view('livewire.admin.edit-course-modal');
    }
}
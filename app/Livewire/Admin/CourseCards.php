<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Livewire\Attributes\On;

class CourseCards extends Component
{
    public $search = '';
    public $query;
    public $editingCourse = null;
    public $editableData = [
        'course_code' => '',
        'course_name' => '',
    ];

    #[On('refreshCourses')] 
    public function refresh()
    {
        // Reset the search term
        $this->search = '';
        
        // This will trigger a re-render of the component
        $this->dispatch('coursesRefreshed');
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function startEditing($courseId)
    {
        $course = Course::find($courseId);
        $this->editingCourse = $courseId;
        $this->editableData = [
            'course_code' => $course->course_code,
            'course_name' => $course->course_name,
        ];
    }

    public function cancelEditing()
    {
        $this->editingCourse = null;
        $this->editableData = [
            'course_code' => '',
            'course_name' => '',
        ];
    }

    public function saveCourseChanges()
    {
        $this->validate([
            'editableData.course_code' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $exists = Course::where('course_code', $value)
                        ->where('id', '!=', $this->editingCourse)
                        ->exists();
                    
                    if ($exists) {
                        $fail('This course code is already taken.');
                    }
                },
            ],
            'editableData.course_name' => 'required|string|max:255',
        ]);

        try {
            $course = Course::find($this->editingCourse);
            $course->update([
                'course_code' => $this->editableData['course_code'],
                'course_name' => $this->editableData['course_name'],
            ]);

            $this->cancelEditing();
            $this->dispatch('alert', type: 'success', text: 'Course updated successfully!');
            $this->dispatch('refreshCourses');
            
        } catch (\Exception $e) {
            logger()->error('Error updating course', [
                'error' => $e->getMessage(),
                'course_id' => $this->editingCourse
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error updating course.');
        }
    }
    public function deleteCourse($courseId)
{
    try {
        $course = Course::findOrFail($courseId);
        
        // Check if course has any sections
        if ($course->sections()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete course with existing sections.');
            return;
        }
        
        $course->delete();
        $this->cancelEditing();
        $this->dispatch('alert', type: 'success', text: 'Course deleted successfully!');
        $this->dispatch('refreshCourses');
        
    } catch (\Exception $e) {
        logger()->error('Error deleting course', [
            'error' => $e->getMessage(),
            'course_id' => $courseId
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting course.');
    }
}

    public function render()
    {
        // Start the query to order by course_name with sections and students count
        $query = Course::query()
            ->orderBy('course_name', 'asc')
            ->with('sections.students')
            ->withCount('students');

        // Apply search filter if search term is provided
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('course_name', 'like', '%' . $this->search . '%')
                  ->orWhere('course_code', 'like', '%' . $this->search . '%');
            });
        }

        // Fetch the results
        $courses = $query->get();

        return view('livewire.admin.course-cards', [
            'courses' => $courses
        ]);
    }
}

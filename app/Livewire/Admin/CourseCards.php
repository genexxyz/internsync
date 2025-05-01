<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Livewire\Attributes\On;
use App\Imports\CourseSectionImport;

class CourseCards extends Component
{
    public $search = '';
    public $query;
    public $editingCourse = null;
    public $showImportModal = false;
    public $courses;
public $importFile;
public $academic_year_id;
    public $editableData = [
        'course_code' => '',
        'course_name' => '',
    ];

    protected $listeners = [
        'refreshCourses' => 'refresh',
        'coursesRefreshed' => 'loadCourses'
    ];

    public function mount()
    {
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $query = Course::query()
            ->withCount('students')
            ->with(['sections.students', 'instructorCourses' => function($query) {
                $query->whereHas('academic_year', function($q) {
                    $q->where('ay_default', true);
                })->with('instructor');
            }])
            ->orderBy('course_code');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('course_name', 'like', '%' . $this->search . '%')
                  ->orWhere('course_code', 'like', '%' . $this->search . '%');
            });
        }

        $this->courses = $query->get();
    }
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
    public function importCourses()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt|max:2048',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);
    
        try {
            $path = $this->importFile->getRealPath();
            $importer = new CourseSectionImport($this->academic_year_id);
            $result = $importer->import($path);
    
            if ($result['success']) {
                $this->dispatch('alert', type: 'success', text: 'Courses imported successfully!');
            } else {
                $this->dispatch('alert', type: 'error', text: implode("\n", $result['errors']));
            }
    
            $this->showImportModal = false;
            $this->dispatch('refreshCourses');
    
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Failed to import courses: ' . $e->getMessage());
        }
    }
    
//     public function deleteCourse($courseId)
// {
//     try {
//         $course = Course::findOrFail($courseId);
        
//         // Check if course has any sections
//         if ($course->sections()->exists()) {
//             $this->dispatch('alert', type: 'error', text: 'Cannot delete course with existing sections.');
//             return;
//         }
        
//         $course->delete();
//         $this->cancelEditing();
//         $this->dispatch('alert', type: 'success', text: 'Course deleted successfully!');
//         $this->dispatch('refreshCourses');
        
//     } catch (\Exception $e) {
//         logger()->error('Error deleting course', [
//             'error' => $e->getMessage(),
//             'course_id' => $courseId
//         ]);
//         $this->dispatch('alert', type: 'error', text: 'Error deleting course.');
//     }
// }

public function updatedSearch()
{
    $this->loadCourses();
}

public function refresh()
{
    $this->search = '';
    $this->loadCourses();
    $this->dispatch('coursesRefreshed');
}

public function render()
{
    return view('livewire.admin.course-cards');
}
}

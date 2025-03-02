<?php

namespace App\Livewire\Instructor;

use App\Models\Instructor;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AllStudents extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';

    public function render()
{
    $instructor = Instructor::where('user_id', Auth::id())
        ->with([
            'sections.course',
            'instructorCourse.course'
        ])
        ->firstOrFail();
    
    $query = Student::query()
        ->with(['yearSection.course', 'acceptance_letter', 'user']);

    // Check if instructor is program head
    if ($instructor->instructorCourse) {
        // For program head - get all students in the course
        $query->whereHas('yearSection.course', function($query) use ($instructor) {
            $query->where('id', $instructor->instructorCourse->course_id);
        });
    } else {
        // For regular instructor - get only students in assigned sections
        $query->whereHas('yearSection.course', function($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id);
        });
    }

    if ($this->search) {
        $query->where(function($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('student_id', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->filter === 'with_letter') {
        $query->whereHas('acceptance_letter');
    } elseif ($this->filter === 'no_letter') {
        $query->whereDoesntHave('acceptance_letter');
    }

    $students = $query->orderBy('created_at', 'desc')
        ->paginate(9);

    return view('livewire.instructor.all-students', [
        'students' => $students,
        'isProgramHead' => (bool) $instructor->instructorCourse
    ]);
}
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }
}
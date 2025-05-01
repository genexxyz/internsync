<?php

namespace App\Livewire\Admin;

use App\Models\Academic;
use App\Models\Student;
use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
{
    use WithPagination;

    public $search = '';
    public $courseFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $academicFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
protected $listeners = [
        'refreshStudents' => 'render',
    ];
    public function mount()
    {
        // Set default academic year to current
        $this->academicFilter = Academic::where('ay_default', true)->first()?->id ?? '';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $academics = Academic::orderBy('academic_year', 'desc')
                           ->orderBy('semester', 'desc')
                           ->get();

        $courses = Course::orderBy('course_name')->get();

        $students = Student::query()
            ->with(['section.course', 'deployment', 'user'])
            ->when($this->academicFilter, function($query) {
                $query->whereHas('deployment', function($q) {
                    $q->where('academic_id', $this->academicFilter);
                });
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                      ->orWhere('last_name', 'like', '%'.$this->search.'%')
                      ->orWhere('student_id', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->courseFilter, function($query) {
                $query->whereHas('section', function($q) {
                    $q->where('course_id', $this->courseFilter);
                });
            })
            ->when($this->sectionFilter, function($query) {
                $query->where('year_section_id', $this->sectionFilter);
            })
            
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.students', compact('students', 'courses', 'academics'));
    }
}
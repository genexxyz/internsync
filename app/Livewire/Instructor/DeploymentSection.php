<?php

namespace App\Livewire\Instructor;

use App\Models\Section;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DeploymentSection extends Component
{
    use WithPagination;

    public $sections;
    public $instructor;
    public $isProgramHead;
    public $viewMode = 'section';
    public $search = '';
    public $filter = 'all';
    public $sortDirection = 'asc';
    public $showDateSort = false;

    public function paginationView()

    {

        return 'vendor.pagination.tailwind';

    }
    public function mount()
    {
        $this->instructor = $this->getInstructor();
        $this->loadSections();
        $this->isProgramHead = (bool) ($this->instructor->instructorCourse?->is_verified);
    }

    private function getInstructor()
    {
        return Instructor::where('user_id', Auth::id())
            ->with([
                'instructorCourse.course',
                'handles.section.course',
                'handles.section.students'
            ])
            ->firstOrFail();
    }

    private function loadSections()
    {
        $sectionsQuery = Section::query()
            ->with(['course', 'students']);

        if ($this->instructor->instructorCourse && $this->instructor->instructorCourse->is_verified) {
            $sectionsQuery->where('course_id', $this->instructor->instructorCourse->course_id);
        } else {
            $sectionsQuery->whereHas('handles', function($query) {
                $query->where('instructor_id', $this->instructor->id)
                    ->where('is_verified', true);
            });
        }

        $this->sections = $sectionsQuery->get();
    }

    private function loadStudents()
{
    $query = Student::query()
        ->with(['yearSection.course', 'acceptance_letter', 'user', 'deployment']);

    // Filter students based on instructor role
    if ($this->instructor->instructorCourse && $this->instructor->instructorCourse->is_verified) {
        // Program head can see all students in their course
        $query->whereHas('yearSection', function($query) {
            $query->where('course_id', $this->instructor->instructorCourse->course_id);
        });
    } else {
        // Regular instructor can only see students from their handled sections
        $query->whereHas('yearSection', function($query) {
            $query->whereHas('handles', function($subQuery) {
                $subQuery->where('instructor_id', $this->instructor->id)
                    ->where('is_verified', true);
            });
        });
    }

    // Search functionality
    if ($this->search) {
        $query->where(function($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('student_id', 'like', '%' . $this->search . '%');
        });
    }

    // Filter by status
    switch($this->filter) {
        case 'with_letter':
            $query->whereHas('acceptance_letter')
                ->join('acceptance_letters', 'students.id', '=', 'acceptance_letters.student_id')
                ->select('students.*');
            $this->showDateSort = true;
            if ($this->showDateSort) {
                $query->orderBy('acceptance_letters.updated_at', $this->sortDirection);
            }
            break;
        
        case 'no_letter':
            $query->whereDoesntHave('acceptance_letter');
            $this->showDateSort = false;
            break;

        case 'deployed':
            $query->whereHas('deployment', function($query) {
                $query->whereNotNull('company_id');
            })
            ->join('deployments', 'students.id', '=', 'deployments.student_id')
            ->select('students.*');
            $this->showDateSort = true;
            if ($this->showDateSort) {
                $query->orderBy('deployments.updated_at', $this->sortDirection);
            }
            break;

        default:
            $this->showDateSort = false;
            $query->orderBy('students.first_name', $this->sortDirection);
            break;
    }

    return $query->paginate(9);
}

    public function toggleView($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    public function toggleSort()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter($value)
    {
        $this->resetPage();
        $this->showDateSort = in_array($value, ['with_letter', 'deployed']);
    }

    

    public function render()
    {
        return view('livewire.instructor.deployment-section', [
            'sections' => $this->sections,
            'students' => $this->viewMode === 'all' ? $this->loadStudents() : null,
            'instructor' => $this->instructor,
            'isProgramHead' => $this->isProgramHead
        ]);
    }
}
<?php

namespace App\Livewire\Instructor;

use App\Models\Section;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DeploymentSection extends Component
{
    use WithPagination;

    public $instructor;
    public $isProgramHead;
    public $search = '';
    public $courseFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    #[On('refreshStudents')] 
    public function refresh()
    {
        $this->loadStudents();
    }

    public function mount()
    {
        $this->instructor = $this->getInstructor();
        $this->isProgramHead = (bool) ($this->instructor->instructorCourse?->is_verified);
        
        // If program head, set initial course filter
        if ($this->isProgramHead) {
            $this->courseFilter = $this->instructor->instructorCourse->course_id;
        }
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function getInstructor()
    {
        return Instructor::where('user_id', Auth::id())
            ->with(['instructorCourse.course', 'handles.section.course'])
            ->firstOrFail();
    }

    private function loadStudents()
    {
        $query = Student::query()
            ->with(['yearSection.course', 'acceptance_letter', 'user', 'deployment'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                });
            });

        // Handle program head vs regular instructor filtering
        if ($this->isProgramHead) {
            $query->whereHas('yearSection', function($q) {
                $q->where('course_id', $this->instructor->instructorCourse->course_id);
            });
        } else {
            $query->whereHas('yearSection', function($q) {
                $q->whereHas('handles', function($sq) {
                    $sq->where('instructor_id', $this->instructor->id)
                       ->where('is_verified', true);
                });
            });
        }

        // Apply filters
        $query->when($this->courseFilter, function($query) {
            $query->whereHas('yearSection', function($q) {
                $q->where('course_id', $this->courseFilter);
            });
        })
        ->when($this->sectionFilter, function($query) {
            $query->where('year_section_id', $this->sectionFilter);
        })
        ->when($this->statusFilter, function($query) {
            switch ($this->statusFilter) {
                case 'pending':
                    $query->whereDoesntHave('acceptance_letter');
                    break;
                case 'with_letter':
                    $query->whereHas('acceptance_letter')
                          ->whereDoesntHave('deployment');
                    break;
                case 'deployed':
                    $query->whereHas('deployment');
                    break;
            }
        });

        return $query->orderBy($this->sortField, $this->sortDirection)
                    ->paginate(10);
    }

    public function render()
    {
        $courses = $this->isProgramHead 
            ? Course::where('id', $this->instructor->instructorCourse->course_id)->get()
            : Course::whereHas('sections.handles', function($query) {
                $query->where('instructor_id', $this->instructor->id)
                      ->where('is_verified', true);
            })->get();

        return view('livewire.instructor.deployment-section', [
            'students' => $this->loadStudents(),
            'courses' => $courses,
            'instructor' => $this->instructor,
            'isProgramHead' => $this->isProgramHead
        ]);
    }
}
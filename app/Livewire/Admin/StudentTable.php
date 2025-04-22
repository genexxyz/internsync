<?php
namespace App\Livewire\Admin;

use App\Models\Student;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;

class StudentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $section;

    public function mount(?Section $section = null)
    {
        if (!$section) {
            return redirect()->route('admin.courses.index');
        }
        $this->section = $section;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $students = Student::query()
            ->where('year_section_id', $this->section->id)
            ->with(['user', 'deployment.company'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('student_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filter !== 'all', function ($query) {
                switch ($this->filter) {
                    case 'deployed':
                        $query->whereHas('deployment', function ($q) {
                            $q->where('status', 'ongoing');
                        });
                        break;
                    case 'completed':
                        $query->whereHas('deployment', function ($q) {
                            $q->where('status', 'completed');
                        });
                        break;
                    case 'pending':
                        $query->whereHas('deployment', function ($q) {
                            $q->where('status', 'pending');
                        });
                        break;
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.student-table', [
            'students' => $students,
            'sectionName' => "{$this->section->course->course_code} {$this->section->year_level}-{$this->section->class_section}"
        ]);
    }
}
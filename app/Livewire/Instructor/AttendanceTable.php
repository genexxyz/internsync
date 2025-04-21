<?php

namespace App\Livewire\Instructor;

use App\Models\Journal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDate;
    public $section_id;
    public $selectedStudentId = null;
    public $showJournalModal = false;

    protected $queryString = ['search', 'selectedDate'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function getSelectedStudentProperty()
{
    if (!$this->selectedStudentId) {
        return null;
    }

    $student = Student::with([
        'deployment.department.company',
        'attendances' => function($query) {
            $query->whereDate('date', $this->selectedDate);
        }
    ])->find($this->selectedStudentId);

    // Load journals with their task histories separately
    $student->journals = Journal::where('date', $this->selectedDate)
        ->where('student_id', $this->selectedStudentId)
        ->with(['taskHistories' => function($query) {
            $query->orderBy('changed_at', 'desc');
        }, 'taskHistories.task'])
        ->get();

    return $student;
}

    public function render()
    {
        $query = Student::query()
        ->with([
            'user',
            'deployment.department.company',
            'attendances' => function($query) {
                $query->whereDate('date', $this->selectedDate);
            }
        ])
        ->whereHas('deployment', function($q) {
            $q->whereNotNull('supervisor_id');
        });

        if ($this->section_id) {
            $query->where('year_section_id', $this->section_id);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('deployment.department.company', function ($sq) {
                        $sq->where('company_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $students = $query->orderBy('last_name')->paginate(10);

    // Load journals with task histories for the table view
    foreach($students as $student) {
        $student->journals = Journal::where('date', $this->selectedDate)
            ->where('student_id', $student->id)
            ->with(['taskHistories' => function($query) {
                $query->orderBy('changed_at', 'desc');
            }, 'taskHistories.task'])
            ->get();
    }

    return view('livewire.instructor.attendance-table', [
        'students' => $students,
        'selectedStudent' => $this->selectedStudent
    ]);
    }

    public function viewJournal($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->showJournalModal = true;
    }

    public function closeModal()
    {
        $this->showJournalModal = false;
        $this->selectedStudentId = null;
    }
}
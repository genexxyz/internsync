<?php

namespace App\Livewire\Instructor;

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

    protected $queryString = ['search', 'selectedDate'];
    public $selectedStudentId = null;
    public $showJournalModal = false;
    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $query = Student::query()
            ->with([
                'user',
                'deployment.department.company',
                'attendances' => function($query) {
                    $query->whereDate('date', $this->selectedDate);
                },
                'journals' => function($query) {
                    $query->whereDate('date', $this->selectedDate);
                }
            ])
            ->whereHas('deployment', function($q) {
                $q->whereNotNull('supervisor_id');
            });

        // Filter by section_id
        if ($this->section_id) {
            $query->whereHas('yearSection', function ($q) {
                $q->where('id', $this->section_id);
            });
        }

        // Search functionality
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

        return view('livewire.instructor.attendance-table', [
            'students' => $students,
        ]);
    }
    public function viewJournal($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->showJournalModal = true;
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }
}
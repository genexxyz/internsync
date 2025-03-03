<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\Student;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class WeeklyReportsTable extends Component
{
    use WithPagination;

    public $expandedStudent = null;
    
    protected $listeners = ['reportStatusChanged' => 'render'];

    public function toggleExpand($studentId)
    {
        $this->expandedStudent = $this->expandedStudent === $studentId ? null : $studentId;
    }
    
    public function viewReport($reportId)
    {
        $this->dispatch('openReportReview', $reportId);
    }

    public function render()
    {
        $students = Student::whereHas('deployment', function($query) {
            $query->where('supervisor_id', Auth::user()->supervisor->id);
        })->with(['deployment', 'weeklyReports' => function($query) {
            $query->orderBy('week_number', 'desc');
        }])->get();

        return view('livewire.supervisor.weekly-reports-table', [
            'students' => $students
        ]);
    }
}
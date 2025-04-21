<?php

namespace App\Livewire\Supervisor;

use App\Models\Journal;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DailyReportsTable extends Component
{
    use WithPagination;

    public $selectedDate;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['dailyEntryUpdated' => 'render'];

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
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

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    
   
    public function render()
    {
        // Get all students under this supervisor
        $students = Student::whereHas('deployment', function($query) {
                $query->where('supervisor_id', Auth::user()->supervisor->id);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['deployment'])
            ->get();
    
        // Get journals with relationships
        $journals = Journal::with(['attendance', 'taskHistories.task'])
            ->whereDate('date', $this->selectedDate)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');
    
        // Map students to daily reports
        $dailyReports = $students->map(function($student) use ($journals) {
            $journal = $journals->get($student->id);
            
            // Process tasks if journal exists
            $tasks = collect();
            if ($journal) {
                $taskHistories = $journal->taskHistories;
                $groupedHistories = $taskHistories->groupBy('task_id');
                
                $tasks = $groupedHistories->map(function($histories) {
                    $latestHistory = $histories->sortByDesc('changed_at')->first();
                    $task = $latestHistory->task;
                    
                    return [
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $latestHistory->status,
                        'worked_hours' => $latestHistory->worked_hours,
                        'remarks' => $latestHistory->remarks
                    ];
                })->values();
            }
    
            // Get attendance status
            $attendance = null;
            if ($journal && $journal->attendance) {
                $attendance = [
                    'status' => $journal->attendance->status,
                    'time_in' => $journal->attendance->time_in,
                    'time_out' => $journal->attendance->time_out,
                    'total_hours' => $journal->attendance->total_hours ?? '00:00',
                    'is_late' => $journal->attendance->is_late
                ];
            }
    
            return [
                'student' => $student,
                'journal' => $journal,
                'tasks' => $tasks,
                'attendance' => $attendance
            ];
        });
    
        // Apply sorting
        if ($this->sortField === 'student.last_name') {
            $dailyReports = $dailyReports->sortBy([
                fn ($a, $b) => $this->sortDirection === 'asc' 
                    ? $a['student']->last_name <=> $b['student']->last_name
                    : $b['student']->last_name <=> $a['student']->last_name
            ]);
        }
    
        return view('livewire.supervisor.daily-reports-table', [
            'dailyReports' => $dailyReports
        ]);
    }
}
<?php

namespace App\Livewire\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Journal;
use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskJournalModal extends ModalComponent
{
    public $newTask = '';
    public $taskStatuses = [];
    public $currentTasks;
    public $pendingTasks;
    public $student;
    public $editingTask = null;
    public $editedDescription = '';

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
{
    $this->student = Auth::user()->student;
    
    // Get today's tasks - tasks whose latest history is from today
    $this->currentTasks = Task::whereHas('histories', function($query) {
        $query->whereHas('journal', function($q) {
            $q->where('date', now()->toDateString())
              ->where('student_id', $this->student->id);
        });
    })
    ->with(['histories' => function($query) {
        $query->orderBy('changed_at', 'desc');
    }])
    ->get();

    // Get previous pending tasks - tasks whose latest history is pending and from previous days
    $this->pendingTasks = Task::query()
        ->whereHas('histories', function($query) {
            $query->where(function($q) {
                $q->select('status')
                    ->from('task_histories as th')
                    ->whereColumn('th.task_id', 'tasks.id')
                    ->orderBy('changed_at', 'desc')
                    ->limit(1);
            }, 'pending')
            ->whereHas('journal', function($q) {
                $q->where('date', '<', now()->toDateString())
                  ->where('student_id', $this->student->id);
            });
        })
        ->whereDoesntHave('histories', function($query) {
            $query->whereHas('journal', function($q) {
                $q->where('date', now()->toDateString());
            });
        })
        ->with(['histories' => function($query) {
            $query->orderBy('changed_at', 'desc');
        }])
        ->get();

    $this->taskStatuses = collect($this->currentTasks)
        ->mapWithKeys(function($task) {
            return [$task->id => $task->current_status];
        })
        ->toArray();
}

public function moveTaskToToday($taskId)
{
    DB::beginTransaction();
    
    try {
        $task = Task::find($taskId);
        $journal = Journal::firstOrCreate([
            'student_id' => $this->student->id,
            'date' => now()->toDateString(),
        ]);

        // Create new history entry for moving to today
        $task->histories()->create([
            'journal_id' => $journal->id,
            'status' => 'pending',
            'changed_at' => now()
        ]);
        
        DB::commit();
        $this->loadTasks();
        
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('alert', [
            'type' => 'error',
            'message' => 'Error moving task to today!'
        ]);
    }
}

    public function updateTaskStatus($taskId, $status)
    {
        $task = Task::find($taskId);
        $currentStatus = $task->current_status;
        
        if ($currentStatus !== $status) {
            DB::beginTransaction();
            
            try {
                $journal = Journal::firstOrCreate([
                    'student_id' => $this->student->id,
                    'date' => now()->toDateString(),
                ]);

                $task->histories()->create([
                    'journal_id' => $journal->id,
                    'status' => $status,
                    'changed_at' => now()
                ]);
                
                DB::commit();
                $this->loadTasks();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => 'Error updating task status!'
                ]);
            }
        }
    }

    public function addTask()
    {
        
        $this->validate([
            'newTask' => 'required|min:3'
        ]);

        DB::beginTransaction();
        
        try {
            $journal = Journal::firstOrCreate([
                'student_id' => $this->student->id,
                'date' => now()->toDateString(),
            ]);

            $task = Task::create([
                'description' => $this->newTask,
                'order' => $this->currentTasks->count()
            ]);

            $task->histories()->create([
                'journal_id' => $journal->id,
                'status' => 'pending',
                'changed_at' => now()
            ]);

            DB::commit();
            $this->newTask = '';
            $this->loadTasks();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Error creating task!'
            ]);
        }
    }

    public function startEditing($taskId)
    {
        $task = $this->currentTasks->find($taskId);
        $this->editingTask = $taskId;
        $this->editedDescription = $task->description;
    }

    public function saveEdited()
    {
        $this->validate([
            'editedDescription' => 'required|min:3'
        ]);

        Task::where('id', $this->editingTask)->update([
            'description' => $this->editedDescription
        ]);

        $this->editingTask = null;
        $this->editedDescription = '';
        $this->loadTasks();
    }

    public function cancelEditing()
    {
        $this->editingTask = null;
        $this->editedDescription = '';
    }

    public function removeTask($taskId)
    {
        Task::destroy($taskId);
        $this->loadTasks();
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render()
    {
        return view('livewire.components.task-journal-modal');
    }
}
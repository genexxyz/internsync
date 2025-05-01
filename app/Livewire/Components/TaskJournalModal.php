<?php

namespace App\Livewire\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Journal;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskJournalModal extends ModalComponent
{
    public $newTask = '';
    public $currentJournal = null;
    public $journalText = '';
    public $editingTask = null;
    public $editedDescription = '';
    public $editingJournalTitle = false;

    public function mount()
    {
        $this->loadTodayJournal();
    }
    public function discard()
    {
        try {
            DB::beginTransaction();

            // Delete tasks first due to foreign key constraint
            Task::where('journal_id', $this->currentJournal->id)->delete();

            // Then delete the journal
            Journal::where('date', now()->toDateString())
                ->where('student_id', Auth::user()->student->id)
                ->delete();

            DB::commit();


            $this->dispatch('alert', type: 'success', text: 'Journal discarded successfully.');

            $this->loadTodayJournal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', text: 'Error discarding journal.');
        }
    }
    protected function loadTodayJournal()
    {
        $student = Auth::user()->student;

        $this->currentJournal = Journal::with(['tasks' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->where([
            'student_id' => $student->id,
            'date' => now()->toDateString(),
        ])->first();

        $this->journalText = $this->currentJournal?->text ?? '';
    }

    // Add a new method to create journal
    public function createJournal()
    {
        $this->validate([
            'journalText' => 'required|min:3'
        ]);

        try {
            DB::beginTransaction();

            $student = Auth::user()->student;

            $this->currentJournal = Journal::create([
                'student_id' => $student->id,
                'date' => now()->toDateString(),
                'text' => $this->journalText
            ]);

            DB::commit();
            $this->dispatch('alert', type: 'success', text: 'Journal created successfully.');
            $this->loadTodayJournal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', text: 'Error creating journal.');
        }
    }

    public function startEditingJournalTitle()
    {
        $this->editingJournalTitle = true;
    }

    public function cancelEditingJournalTitle()
    {
        $this->editingJournalTitle = false;
    }

    public function saveJournalText()
    {
        $this->validate([
            'journalText' => 'required|min:3'
        ]);

        try {
            $this->currentJournal->update([
                'text' => $this->journalText
            ]);

            $this->editingJournalTitle = false;
            $this->dispatch('alert', type: 'success', text: 'Journal title updated successfully.');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error updating journal title.');
        }
    }

    public function addTask()
    {
        if (!$this->currentJournal || empty($this->currentJournal->text)) {
            $this->dispatch('alert', type: 'error', text: 'Please create a journal with a title first.');
            return;
        }

        $this->validate([
            'newTask' => 'required|min:3'
        ]);

        DB::beginTransaction();

        try {
            Task::create([
                'journal_id' => $this->currentJournal->id,
                'description' => $this->newTask,
                'order' => $this->currentJournal->tasks->count()
            ]);

            DB::commit();
            $this->newTask = '';
            $this->loadTodayJournal();
            $this->dispatch('alert', type: 'success', text: 'Task added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', text: 'Error creating task!');
        }
    }

    public function startEditing($taskId)
    {
        $task = $this->currentJournal->tasks->find($taskId);
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
        $this->loadTodayJournal();
    }

    public function cancelEditing()
    {
        $this->editingTask = null;
        $this->editedDescription = '';
    }

    public function removeTask($taskId)
    {
        try {
            Task::where('id', $taskId)
                ->where('journal_id', $this->currentJournal->id)
                ->delete();

            $this->loadTodayJournal();
            $this->dispatch('alert', type: 'success', text: 'Task removed successfully.');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error removing task.');
        }
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function taskJournalClose()
    {
        $this->closeModal();
        $this->dispatch('refreshTaskAttendance');
    }

    public function render()
    {
        return view('livewire.components.task-journal-modal');
    }
}

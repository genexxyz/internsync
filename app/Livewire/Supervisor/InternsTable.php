<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deployment;
use Illuminate\Support\Facades\Auth;

class InternsTable extends Component
{
    use WithPagination;
    public $selectedDeployment = null;
    public $startDate;

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
    }

    public function saveStartDate()
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
        ]);

        $deployment = Deployment::findOrFail($this->selectedDeployment);
        $deployment->update([
            'starting_date' => $this->startDate,
            'status' => 'ongoing'
        ]);

        $this->selectedDeployment = null;

        $this->dispatch('alert', type: 'success', text: 'Starting date has been set successfully!');
    }
    public function render()
    {
        $deployments = Deployment::where('company_dept_id', Auth::user()->supervisor->company_department_id)
            ->with(['student.yearSection.course'])
            ->paginate(10);

        return view('livewire.supervisor.interns-table', [
            'deployments' => $deployments
        ]);
    }
}
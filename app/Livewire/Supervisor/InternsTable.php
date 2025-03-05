<?php

namespace App\Livewire\Supervisor;

use App\Models\Attendance;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deployment;
use Illuminate\Support\Facades\Auth;

class InternsTable extends Component
{
    use WithPagination;
    public $selectedDeployment = null;
    public $startDate;

    public $totalHours = [];
    public $status;
    

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->calculateTotalHours();
    }
    private function calculateTotalHours()
{
    $deployments = Deployment::where('company_dept_id', Auth::user()->supervisor->company_department_id)
        ->with(['student'])  // Include course relationship
        ->get();

    foreach ($deployments as $deployment) {
        $totalMinutes = 0;
        
        // Get all approved attendances for this student
        $attendances = Attendance::where('student_id', $deployment->student_id)
            ->where('status', '!=', 'absent')
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
                $totalMinutes += ((int)$hours * 60) + (int)$minutes;
            }
        }

        // Convert total minutes to hours and minutes format
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        // Update deployment status based on required hours
        $requiredHours = $deployment->custom_hours ?? 500;
        // Store total hours for display
        $this->totalHours[$deployment->id] = [
            'hours' => $hours,
            'minutes' => $minutes,
            'formatted' => sprintf("%d/%d", $hours, $requiredHours)
        ];

        
        
        if ($this->status !== 'pending' && $deployment->starting_date) {
            if ($hours >= $requiredHours) {
                $deployment->update(['status' => 'completed']);
            } else {
                $deployment->update(['status' => 'ongoing']);
            }
        }
    }
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
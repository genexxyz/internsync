<?php

namespace App\Livewire\Supervisor;

use App\Models\Deployment;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EvaluationTable extends Component
{
    protected $listeners = ['refreshEvaluation' => '$render'];
    public function getFinishedStudents()
    {
        return Deployment::with(['student.user', 'department'])
            ->where('supervisor_id', Auth::user()->supervisor->id)->where('status', 'completed')
            ->get()
            ->filter(function ($deployment) {
                $completedHours = Attendance::getTotalApprovedHours($deployment->student_id);
                return $completedHours >= $deployment->custom_hours;
            });
    }

    public function render()
    {
        $finishedStudents = $this->getFinishedStudents();
        return view('livewire.supervisor.evaluation-table', [
            'finishedStudents' => $finishedStudents
        ]);
    }
}
<?php

namespace App\Livewire\Supervisor;

use App\Models\Deployment;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;

class InternDetailsModal extends ModalComponent
{
    public Deployment $deployment;
    public $totalHours = 0;
    public $totalMinutes = 0;

    public function mount(Deployment $deployment)
    {
        $this->deployment = $deployment->load([
            'student.user', 
            'student.yearSection.course',
            'student.acceptance_letter'
        ]);
        
        // Calculate total hours
        $attendances = $this->deployment->student->attendances()
            ->where('status', 'approved')
            ->get();
            
        foreach ($attendances as $attendance) {
            if ($attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
                $this->totalHours += (int)$hours;
                $this->totalMinutes += (int)$minutes;
            }
        }
        
        // Convert excess minutes to hours
        $this->totalHours += floor($this->totalMinutes / 60);
        $this->totalMinutes = $this->totalMinutes % 60;
    }

    public function render()
    {
        return view('livewire.supervisor.intern-details-modal');
    }
}
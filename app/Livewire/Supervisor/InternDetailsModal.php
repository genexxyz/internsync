<?php

namespace App\Livewire\Supervisor;

use App\Models\Deployment;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;
use App\Services\DocumentGeneratorService;
use Illuminate\Support\Str;

class InternDetailsModal extends ModalComponent
{
    public Deployment $deployment;
    public $totalHours = 0;
    public $totalMinutes = 0;
    protected $documentGenerator;
    public function boot(DocumentGeneratorService $documentGenerator)
    {
        $this->documentGenerator = $documentGenerator;
    }

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
    public function downloadAcceptanceLetter()
    {
        try {
            $pdf = $this->documentGenerator->generateAcceptanceLetter($this->deployment);
            $filename = sprintf(
                'acceptance_letter_%s_%s_%s.pdf',
                $this->deployment->student->student_id,
                Str::slug($this->deployment->student->last_name),
                Str::slug($this->deployment->student->first_name)
            );
            return response()->streamDownload(
                function() use ($pdf) { 
                    echo $pdf->output(); 
                },
                $filename
            );
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error generating letter.');
        }
    }
    public function render()
    {
        return view('livewire.supervisor.intern-details-modal');
    }
}
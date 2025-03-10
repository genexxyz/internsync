<?php
// filepath: /opt/lampp/htdocs/internsync/app/Livewire/Supervisor/AcceptStudents.php

namespace App\Livewire\Supervisor;

use App\Models\Deployment;
use App\Models\CompanyDepartment;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AcceptanceLetter;
use Illuminate\Support\Facades\DB;

class AcceptStudents extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    public $availableStudents = [];
    public $selectedStudents = [];
    public $selectAll = false;
    public $signature;
    
    // For viewing individual letters
    public $viewingDeploymentId = null;
    public $showLetterModal = false;
    
    protected $listeners = [
        'refreshAvailableStudents' => 'loadAvailableStudents'
    ];
    
    public function mount()
    {
        $this->loadAvailableStudents();
    }
    
    public function loadAvailableStudents()
    {
        $supervisor = Auth::user()->supervisor;
        
        // Get company and department information
        $companyDeptId = $supervisor->company_department_id;
        $companyId = null;
        
        if ($companyDeptId) {
            // If supervisor has a department, get its company ID
            $department = Department::find($companyDeptId);
            if ($department) {
                $companyId = $department->company_id;
            }
        }
        
        // Find deployments without supervisors matching the company/department
        $query = Deployment::whereNull('supervisor_id')
            ->with(['student.user', 'student.yearSection.course', 'department.company']);
            
        if ($companyDeptId) {
            // If supervisor has a department, filter by exact department
            $query->where('company_dept_id', $companyDeptId);
        } elseif ($companyId) {
            // If only company is known, filter by company
            $query->whereHas('department', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }
        
        $this->availableStudents = $query->get();
        $this->selectedStudents = [];
        $this->selectAll = false;
    }
    
    public function updatedSelectAll($value)
    {
        $this->selectedStudents = [];
        
        if ($value && count($this->availableStudents) > 0) {
            foreach ($this->availableStudents as $deployment) {
                $this->selectedStudents[] = (string) $deployment->id;
            }
        }
    }
    
    public function updatedSelectedStudents($value)
    {
        // Check if all students are selected
        if (count($this->selectedStudents) === count($this->availableStudents)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }
    
    public function viewStudentLetter($deploymentId)
    {
        $deployment = Deployment::with(['student.user', 'department.company'])->find($deploymentId);
        if ($deployment) {
            $this->viewingDeploymentId = $deploymentId;
            $this->showLetterModal = true;
        }
    }
    
    public function closeLetterModal()
    {
        $this->viewingDeploymentId = null;
        $this->showLetterModal = false;
    }
    
    public function acceptStudents()
{
    if (empty($this->selectedStudents)) {
        $this->dispatch('alert', type: 'error', text: 'Please select at least one student.');
        return;
    }
    
    // Validate signature if provided
    if ($this->signature) {
        $this->validate([
            'signature' => 'image|max:2048', // 2MB max
        ]);
        
        // Store signature if uploaded
        $filename = 'supervisor_signature_' . Auth::id() . '_' . time() . '.' . $this->signature->getClientOriginalExtension();
        $path = $this->signature->storeAs('signatures', $filename, 'public');
        
        // Update supervisor with signature path
        Auth::user()->supervisor->update([
            'signature_path' => Storage::url($path)
        ]);
    }
    
    $supervisorId = Auth::user()->supervisor->id;
    $accepted = 0;
    
    foreach ($this->selectedStudents as $deploymentId) {
        $deployment = Deployment::with(['student.user', 'student.yearSection.course', 'department.company'])
            ->find($deploymentId);
            
        if ($deployment && is_null($deployment->supervisor_id)) {
            try {
                DB::beginTransaction();
                
                // Update deployment
                $deployment->update([
                    'supervisor_id' => $supervisorId,
                    'accepted_at' => now(),
                ]);

                // Generate PDF data
                $student = $deployment->student;
                $studentName = ($student->user->first_name ?? $student->first_name) . ' ' . 
                             ($student->user->last_name ?? $student->last_name);
                
                $data = [
                    'student_name' => $studentName,
                    'student_id' => $student->student_id ?? $student->student_number,
                    'supervisor_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'supervisor_position' => Auth::user()->supervisor->position,
                    'company_name' => Auth::user()->supervisor->department->company->name ?? 'Company',
                    'department_name' => Auth::user()->supervisor->department->name ?? 'Department',
                    'date' => now()->format('F d, Y'),
                    'signature_path' => Auth::user()->supervisor->signature_path,
                ];
                
                // Generate PDF
                $pdf = Pdf::loadView('pdfs.supervisor-acceptance-letter', $data);
                
                // Create filename
                $fileName = implode('-', [
                    $student->student_id,
                    $student->user->last_name ?? $student->last_name,
                    $student->user->first_name ?? $student->first_name,
                    $student->yearSection->course->course_code,
                    'acceptance-letter-signed',
                    Str::random(8)
                ]) . '.pdf';
                
                // Store the PDF
                Storage::put('public/acceptance_letters/' . $fileName, $pdf->output());
                
                // Create or update acceptance letter record
                AcceptanceLetter::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'is_generated' => true,
                        'signed_path' => 'acceptance_letters/' . $fileName,
                        'supervisor_id' => $supervisorId,
                        'company_name' => $data['company_name'],
                        'department_name' => $data['department_name'],
                        'supervisor_name' => $data['supervisor_name'],
                        'supervisor_position' => $data['supervisor_position'],
                        'signed_at' => now(),
                    ]
                );
                
                DB::commit();
                $accepted++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                logger()->error('Error accepting student', [
                    'error' => $e->getMessage(),
                    'deployment_id' => $deploymentId
                ]);
                continue;
            }
        }
    }
    
    // Clear selections and reload
    $this->selectedStudents = [];
    $this->loadAvailableStudents();
    
    // Dispatch events to update counts and notifications
    $this->dispatch('alert', type: 'success', text: $accepted . ' student(s) accepted successfully!');
    $this->dispatch('accept-students-updated');
    $this->dispatch('refreshInternsTable');
}
    
    public function generateAcceptanceLetter($deploymentId)
    {
        $deployment = Deployment::with(['student.user', 'department.company'])->find($deploymentId);
        if (!$deployment) {
            $this->dispatch('alert', type: 'error', text: 'Deployment not found.');
            return;
        }
        
        $student = $deployment->student;
        $studentName = ($student->user->first_name ?? $student->first_name) . ' ' . ($student->user->last_name ?? $student->last_name);
        
        $data = [
            'student_name' => $studentName,
            'student_id' => $student->student_id ?? $student->student_number,
            'supervisor_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'supervisor_position' => Auth::user()->supervisor->position,
            'company_name' => Auth::user()->supervisor->department->company->name ?? 'Company',
            'department_name' => Auth::user()->supervisor->department->name ?? 'Department',
            'date' => now()->format('F d, Y'),
            'signature_path' => Auth::user()->supervisor->signature_path,
        ];
        
        $pdf = Pdf::loadView('pdfs.supervisor-acceptance-letter', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'Supervisor_Acceptance_Letter_' . str_replace(' ', '_', $studentName) . '.pdf'
        );
    }
    
    public function render()
    {
        return view('livewire.supervisor.accept-students');
    }
}
<?php

namespace App\Livewire\Supervisor;

use App\Models\Attendance;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Deployment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InternsTable extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $currentView = 'interns'; 
    public $selectedDeployment = null;
    public $startDate;

    public $totalHours = [];
    public $status;
    // New properties for student acceptance modal
    public $showAcceptModal = true;
    public $availableStudents;
    public $selectedStudents = [];
    public $selectAll = false;
    public $availableStudentCount = 0;
    public $signature;

    public $viewingStudentId = null; // To track which student's letter is being viewed
public $viewingDeploymentId = null; // To track which deployment's letter is being viewed
public $showLetterModal = false; // Control letter modal visibility

    
    protected function rules()
    {
        return [
            'signature' => 'nullable|image|max:2048', // 2MB limit
            'selectedStudents' => 'array',
        ];
    }
    public function mount()
    {
        $this->availableStudents = new Collection();
        $this->startDate = now()->format('Y-m-d');
        $this->calculateTotalHours();
        $this->updateAvailableStudentCount();
    }

    public function viewStudentLetter($deploymentId)
{
    $deployment = Deployment::with(['student.user', 'department.company'])->find($deploymentId);
    if ($deployment) {
        $this->viewingDeploymentId = $deploymentId;
        $this->viewingStudentId = $deployment->student_id;
        $this->showLetterModal = true;
    }
}

public function closeLetterModal()
{
    $this->viewingStudentId = null;
    $this->viewingDeploymentId = null;
    $this->showLetterModal = false;
}

// Update acceptStudents method to work with individual students
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
        
        // Update supervisor record with signature path
        $supervisor = Auth::user()->supervisor;
        $supervisor->signature_path = Storage::url($path);
        $supervisor->save();
    }
    
    $supervisorId = Auth::user()->supervisor->id;
    $accepted = 0;
    
    foreach ($this->selectedStudents as $deploymentId) {
        $deployment = Deployment::find($deploymentId);
        if ($deployment && is_null($deployment->supervisor_id)) {
            $deployment->update([
                'supervisor_id' => $supervisorId,
                'accepted_at' => now(),
                'acceptance_letter_signed' => !empty($this->signature) || !empty(Auth::user()->supervisor->signature_path)
            ]);
            $accepted++;
        }
    }
    
    $this->updateAvailableStudentCount();
    $this->dispatch('alert', type: 'success', text: $accepted . ' student(s) accepted successfully!');
    
    // Go back to main view
    $this->showInternsView();
}

    public function updateAvailableStudentCount()
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
    
    // Count deployments without supervisors matching the company/department
    $query = Deployment::whereNull('supervisor_id');
        
    if ($companyDeptId) {
        // If supervisor has a department, filter by exact department
        $query->where('company_dept_id', $companyDeptId)->with('acceptance_letter');
    } elseif ($companyId) {
        // If only company is known, filter by company
        $query->whereHas('department', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });
    }
    
    $this->availableStudentCount = $query->count();
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
    public function showAcceptStudentsView()
    {
        $this->loadAvailableStudents();
        $this->currentView = 'acceptStudents';
    }
    
    public function showInternsView()
    {
        $this->reset(['selectedStudents', 'selectAll']);
        $this->currentView = 'interns';
    }
    // New methods for student acceptance
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
    
  
    public function updatedSelectedStudents($value)
    {
        // Check if all students are selected
        if (count($this->selectedStudents) === count($this->availableStudents)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Convert IDs to strings because Livewire form values are always strings
            $this->selectedStudents = $this->availableStudents->map(function($deployment) {
                return (string) $deployment->id;
            })->toArray();
        } else {
            $this->selectedStudents = [];
        }
    }


    public function render()
    {
        $deployments = Deployment::where('supervisor_id', Auth::user()->supervisor->id)
            ->with(['student.yearSection.course'])
            ->paginate(10);
        return view('livewire.supervisor.interns-table', [
            'deployments' => $deployments
        ]);
    }
}
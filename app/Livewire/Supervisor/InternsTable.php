<?php

namespace App\Livewire\Supervisor;

use App\Models\Academic;
use App\Models\Attendance;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Deployment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\AcceptanceLetter;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Services\DocumentGeneratorService;

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
    public $showSignatureUpload = false;
    public $academicDate;
    protected $documentGenerator;


    public function boot(DocumentGeneratorService $documentGenerator)
    {
        $this->documentGenerator = $documentGenerator;
    }
    public function uploadSignature()
    {
        $this->validate([
            'signature' => 'required|image|max:2048',
        ]);

        try {
            // Generate unique filename for signature
            $extension = $this->signature->getClientOriginalExtension();
            $randomString = Str::random(8);
            $filename = sprintf(
                '%s_%s_%s_%s_%s.%s',
                Auth::id(),
                'supervisor',
                strtolower(Auth::user()->supervisor->last_name),
                strtolower(Auth::user()->supervisor->first_name),
                $randomString,
                $extension
            );
            
            // Store signature with custom filename
            $path = $this->signature->storeAs('signatures', $filename, 'public');
            
            Auth::user()->supervisor->update([
                'signature_path' => $path
            ]);

            $this->reset('signature');
            $this->dispatch('alert', type: 'success', text: 'E-signature uploaded successfully! This will be used for acceptance letters and weekly reports.');
            
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Failed to upload signature. Please try again.');
        }
    }

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
    $this->academicDate = Academic::where('ay_default', 1)->firstOrFail();
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
            'signature' => 'image|max:2048',
        ]);
        
        // Generate unique filename for signature
        $extension = $this->signature->getClientOriginalExtension();
        $randomString = Str::random(8);
        $filename = sprintf(
            '%s_%s_%s_%s_%s.%s',
            Auth::id(),
            'supervisor',
            strtolower(Auth::user()->supervisor->last_name),
            strtolower(Auth::user()->supervisor->first_name),
            $randomString,
            $extension
        );
        
        // Store signature with custom filename
        $path = $this->signature->storeAs('signatures', $filename, 'public');
        
        Auth::user()->supervisor->update([
            'signature_path' => $path
        ]);
    }
    
    $supervisorId = Auth::user()->supervisor->id;
    $accepted = 0;
    
    foreach ($this->selectedStudents as $deploymentId) {
        $deployment = Deployment::with([
            'student.user', 
            'student.yearSection.course',
            'department.company',
            'student.section.course.instructorCourses.instructor'
        ])->find($deploymentId);

        if ($deployment && is_null($deployment->supervisor_id)) {
            try {
                DB::beginTransaction();

                // Update deployment
                $deployment->update([
                    'supervisor_id' => $supervisorId,
                    'accepted_at' => now(),
                    'acceptance_letter_signed' => true
                ]);

                // Update acceptance letter record
                AcceptanceLetter::updateOrCreate(
                    ['student_id' => $deployment->student_id],
                    [
                        'is_generated' => true,
                        'company_name' => $deployment->department->company->company_name,
                        'department_name' => $deployment->department->department_name,
                        'supervisor_name' => Auth::user()->supervisor->getFullNameAttribute(),
                        'supervisor_id' => $supervisorId,
                        'signed_at' => now()
                    ]
                );

                // Send notification
                $supervisorName = Auth::user()->supervisor->getFullNameAttribute();
                    $companyName = Auth::user()->supervisor->department->company->company_name ?? 'Company';

                    // Notification for student
                    Notification::send(
                        $deployment->student->user_id,
                        'student_acceptance',
                        'Supervisor Assigned',
                        "Welcome! {$supervisorName} from {$companyName} has accepted you as their intern. Your acceptance letter is now available.",
                        'student.document',
                        'fa-handshake'
                    );
                

                DB::commit();
                $accepted++;
            } catch (\Exception $e) {
                DB::rollBack();
                logger()->error('Error accepting student', [
                    'error' => $e->getMessage(),
                    'deployment_id' => $deploymentId,
                    'stack_trace' => $e->getTraceAsString()
                ]);
                continue;
            }
        }
    }
    
    $this->updateAvailableStudentCount();
    $this->dispatch('alert', type: 'success', text: $accepted . ' student(s) accepted successfully!');
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
            $query->whereHas('department', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $this->availableStudentCount = $query->count();
    }
    private function calculateTotalHours()
{
    $deployments = Deployment::where('company_dept_id', Auth::user()->supervisor->company_department_id)
        ->with(['student'])
        ->get();

    foreach ($deployments as $deployment) {
        $totalMinutes = 0;
        $requiredHours = $deployment->custom_hours ?? 500;
        $completionDate = null;

        // Get all approved attendances for this student, ordered by date
        $attendances = Attendance::where('student_id', $deployment->student_id)
            ->where('is_approved', true)
            ->where('status', '!=', 'absent')
            ->orderBy('date', 'asc')
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
                $totalMinutes += ((int)$hours * 60) + (int)$minutes;
                
                // Check if required hours were met on this day
                if (floor($totalMinutes / 60) >= $requiredHours && !$completionDate) {
                    $completionDate = $attendance->date;
                }
            }
        }

        // Convert total minutes to hours and minutes format
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        // Store total hours for display
        $this->totalHours[$deployment->id] = [
            'hours' => $hours,
            'minutes' => $minutes,
            'formatted' => sprintf("%d/%d", $hours, $requiredHours)
        ];

        if ($this->status !== 'pending' && $deployment->starting_date) {
            if ($hours >= $requiredHours) {
                $deployment->update([
                    'status' => 'completed',
                    'ending_date' => $completionDate
                ]);
            } else {
                $deployment->update(['status' => 'ongoing']);
            }
        }
    }
}

public function saveStartDate()
{
    // Get the deployment and required hours
    $deployment = Deployment::findOrFail($this->selectedDeployment);
    $requiredHours = $deployment->custom_hours;
    
    // Calculate working days left in academic year
    $startDate = \Carbon\Carbon::parse($this->startDate);
    $academicEndDate = \Carbon\Carbon::parse($this->academicDate->end_date);
    $workingDays = 0;
    
    $currentDate = $startDate->copy();
    while ($currentDate->lte($academicEndDate)) {
        if (!$currentDate->isWeekend()) {
            $workingDays++;
        }
        $currentDate->addDay();
    }

    // Calculate possible hours (8 hours per working day)
    $possibleHours = $workingDays * 8;

    // Validate the start date
    $this->validate([
        'startDate' => [
            'required',
            'date',
            'after_or_equal:' . $this->academicDate->start_date,
            'before_or_equal:' . $this->academicDate->end_date,
            function ($attribute, $value, $fail) use ($possibleHours, $requiredHours) {
                if ($possibleHours < $requiredHours) {
                    $fail("Starting on this date would not allow completion of required {$requiredHours} hours within the academic year. " .
                          "Only {$possibleHours} hours possible with 8-hour workdays (excluding weekends).");
                }
            },
        ],
    ], [
        'startDate.after_or_equal' => 'Start date must be within the current academic year (' . $this->academicDate->start_date . ' onwards)',
        'startDate.before_or_equal' => 'Start date must not exceed the academic year end date (' . $this->academicDate->end_date . ')',
    ]);

    try {
        $deployment->update([
            'starting_date' => $this->startDate,
            'status' => 'ongoing'
        ]);

        $this->selectedDeployment = null;
        $this->dispatch('alert', type: 'success', text: 'Starting date has been set successfully!');
    } catch (\Exception $e) {
        $this->dispatch('alert', type: 'error', text: 'Failed to set starting date. Please try again.');
    }
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
            $query->whereHas('department', function ($q) use ($companyId) {
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
            $this->selectedStudents = $this->availableStudents->map(function ($deployment) {
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

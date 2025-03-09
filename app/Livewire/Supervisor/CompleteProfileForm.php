<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Company;
use App\Models\Department;
use App\Models\CompanyDepartment;
use App\Models\Deployment;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompleteProfileForm extends Component
{
    use WithFileUploads;

    // Step tracking
    public $currentStep = 1;
    public $totalSteps = 3;
    
    // Form fields - Step 1: Company Details
    public $name;
    public $position;
    public $company_name;
    public $department_name;
    public $address;
    public $contact;
    
    // Step 2: Student Assignment
    public $availableStudents = [];
    public $selectedStudents = [];
    
    // Step 3: Acceptance Letter & Signature
    public $signature;
    public $acceptanceLetter;
    public $signaturePath;
    
    // Validation rules per step
    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'company_name' => 'required|string|max:100',
            'department_name' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'selectedStudents' => 'nullable|array',
            'signature' => 'nullable|image|max:2048', // 2MB max
        ];
    }
    
    // Different validation messages per step
    protected function messages()
    {
        return [
            'name.required' => 'Please enter your name.',
            'position.required' => 'Please enter your position.',
            'company_name.required' => 'Please enter your company name.',
            'address.required' => 'Please enter company address.',
            'contact.required' => 'Please provide a contact number.',
            'signature.image' => 'The signature must be an image file.',
            'signature.max' => 'The signature should not exceed 2MB.',
        ];
    }
    
    public function mount()
    {
        $supervisor = Auth::user()->supervisor;
        
        // Pre-populate fields if data exists
        $this->name = $supervisor->name ?? Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $this->position = $supervisor->position ?? '';
        $this->address = $supervisor->address ?? '';
        $this->contact = $supervisor->contact_number ?? '';
        
        // Load available students for assignment
        $this->loadAvailableStudents();
    }
    
    public function loadAvailableStudents()
    {
        // Get all students without supervisors
        $this->availableStudents = Student::whereHas('deployment', function ($query) {
            $query->whereNull('supervisor_id');
        })->with(['user', 'yearSection.course', 'deployment.department.company'])->get();
    }
    
    // Step navigation methods
    public function nextStep()
    {
        // Validate based on current step
        if ($this->currentStep == 1) {
            $this->validate([
                'name' => 'required|string|max:100',
                'position' => 'required|string|max:100',
                'company_name' => 'required|string|max:100',
                'department_name' => 'nullable|string|max:100',
                'address' => 'required|string|max:255',
                'contact' => 'required|string|max:20',
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
        
        // When reaching step 3, generate acceptance letter
        if ($this->currentStep == 3) {
            $this->generateAcceptanceLetter();
        }
    }
    
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    
    // Generate acceptance letter content
    public function generateAcceptanceLetter()
    {
        $supervisor = Auth::user()->supervisor;
        $user = Auth::user();
        
        // Generate letter content based on form data
        $this->acceptanceLetter = [
            'supervisor_name' => $this->name,
            'position' => $this->position,
            'company_name' => $this->company_name,
            'department_name' => $this->department_name,
            'address' => $this->address,
            'contact' => $this->contact,
            'selected_students_count' => count($this->selectedStudents),
            'date' => now()->format('F d, Y'),
        ];
    }
    public function generateSupervisorAcceptancePdf()
{
    $supervisor = Auth::user()->supervisor;
    
    $data = [
        'name' => $this->name,
        'position' => $this->position,
        'company_name' => $this->company_name,
        'department_name' => $this->department_name,
        'address' => $this->address,
        'contact' => $this->contact,
        'selected_students' => $this->selectedStudents,
        'selected_students_count' => count($this->selectedStudents),
        'signature_path' => $supervisor->signature_path,
        'date' => now()->format('F d, Y'),
    ];
    
    $pdf = Pdf::loadView('pdfs.supervisor-acceptance-letter', $data);
    return $pdf->download('SupervisorAcceptanceLetter_'.$supervisor->id.'.pdf');
}
    // Upload signature and complete profile
    public function uploadSignature()
    {
        if ($this->signature) {
            $this->validate([
                'signature' => 'image|max:2048',
            ]);
            
            // Store the signature
            $filename = 'signature_' . Auth::id() . '_' . time() . '.' . $this->signature->getClientOriginalExtension();
            $path = $this->signature->storeAs('signatures', $filename, 'public');
            $this->signaturePath = Storage::url($path);
        }
    }
    
    // Save all data and complete profile
    public function save()
    {
        if ($this->currentStep == 3) {
            $this->uploadSignature();
        }
        
        DB::beginTransaction();
        
        try {
            $supervisor = Auth::user()->supervisor;
            
            // Step 1: Update supervisor information
            $supervisor->update([
                'name' => $this->name,
                'position' => $this->position,
                'address' => $this->address,
                'contact_number' => $this->contact,
                'is_profile_complete' => true
            ]);
            
            // Find or create company
            $company = Company::firstOrCreate(
                ['name' => $this->company_name],
                ['address' => $this->address]
            );
            
            // Find or create department if provided
            if (!empty($this->department_name)) {
                $department = Department::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'name' => $this->department_name
                    ]
                );
                $supervisor->company_department_id = $department->id;
                $supervisor->save();
            }
            
            // Step 2: Associate selected students with this supervisor
            if (!empty($this->selectedStudents)) {
                foreach ($this->selectedStudents as $studentId) {
                    $deployment = Deployment::where('student_id', $studentId)
                        ->whereNull('supervisor_id')
                        ->first();
                    
                    if ($deployment) {
                        $deployment->update([
                            'supervisor_id' => $supervisor->id
                        ]);
                    }
                }
            }
            
            // Step 3: Save signature path if uploaded
            if ($this->signaturePath) {
                $supervisor->update([
                    'signature_path' => $this->signaturePath
                ]);
            }
            
            DB::commit();
            
            session()->flash('message', 'Profile completed successfully! Welcome to InternSync.');
            
            return redirect()->route('supervisor.dashboard');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.supervisor.complete-profile-form');
    }
}
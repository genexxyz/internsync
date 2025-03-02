<?php

namespace App\Livewire\Instructor;

use App\Models\Academic;
use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Department;
use App\Models\Deployment;
use App\Models\Student;
use App\Models\Supervisor;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignModal extends ModalComponent
{
    public Student $student;
    public $acceptanceLetter;
    public $letterUrl;
    
    // Company Properties
    public $companySearch = '';
    public $selectedCompany = null;
    public $companies = [];
    public $companyExists = false;
    public $existingCompanyDepartment = null;
    public $isCreatingCompany = false;
    public $existingDepartments = [];
    public $selectedDepartment = '';
    
    public $newCompany = [
        'company_name' => '',
        'address' => '',
        'contact_number' => '',
        'contact_person' => '',
        'email' => '',
        'department' => '',
    ];

    public $isNewDepartment = false;
    public $newDepartment = [
        'company_id' => null,
        'department_name' => ''
    ];
    public $showDepartmentForm = false;
    public $showDepartmentSelection = false;
    
    // Supervisor Properties
    public $selectedSupervisor = null;
    public $filteredSupervisors;
    
    // Deployment Properties
    public $custom_hours;
    public $department;

    public function mount()
    {
        $this->loadAcceptanceLetter();
        if ($this->acceptanceLetter) {
            $this->selectedDepartment = $this->acceptanceLetter->department_name;
        }
    }

    private function loadAcceptanceLetter()
    {
        $this->acceptanceLetter = $this->student->acceptance_letter;
        if (!$this->acceptanceLetter) return;

        $this->letterUrl = Storage::url($this->acceptanceLetter->file_path);
        $this->companySearch = $this->acceptanceLetter->company_name;
        $this->department = $this->acceptanceLetter->department_name;
        
        // Pre-fill new company form with acceptance letter data
        $this->newCompany = [
            'company_name' => $this->acceptanceLetter->company_name,
            'address' => $this->acceptanceLetter->address,
            'contact_number' => $this->acceptanceLetter->contact,
            'contact_person' => $this->acceptanceLetter->name,
            'department' => $this->acceptanceLetter->department_name,
        ];
    }

    public function searchExistingCompany()
    {
        $existingCompany = Company::where('company_name', 'like', '%' . $this->acceptanceLetter->company_name . '%')
            ->first();

        if ($existingCompany) {
            $this->handleExistingCompany($existingCompany);
        } else {
            $this->prepareNewCompany();
        }
    }

    private function handleExistingCompany($company)
    {
        $this->companyExists = true;
        $this->selectedCompany = $company;
        
        // Load all departments for this company
        $this->existingDepartments = Department::where('company_id', $company->id)
            ->pluck('department_name')
            ->toArray();
    }

    public function addNewDepartment()
    {
        $this->validate([
            'selectedDepartment' => 'required|min:2',
        ]);

        $department = Department::create([
            'company_id' => $this->selectedCompany->id,
            'department_name' => $this->selectedDepartment
        ]);

        // Refresh departments list
        $this->existingDepartments = Department::where('company_id', $this->selectedCompany->id)
            ->pluck('department_name')
            ->toArray();

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'New department added successfully!'
        ]);
    }

public function createNewDepartment()
{
    $this->validate([
        'newDepartment.department_name' => 'required|min:2',
    ]);

    $department = Department::create([
        'company_id' => $this->selectedCompany->id,
        'department_name' => $this->newDepartment['department_name']
    ]);

    $this->existingCompanyDepartment = $department->name;
    $this->isNewDepartment = false;

    $this->dispatch('alert', [
        'type' => 'success',
        'message' => 'New department added successfully!'
    ]);
}

    private function prepareNewCompany()
    {
        $this->isCreatingCompany = true;
        
        $this->dispatch('alert', type: 'info', text: 'Company information pre-filled. Please review and add to system.');
    }

    public function createAndSelectCompany()
    {
        $this->validate([
            'newCompany.company_name' => 'required|min:3|unique:companies,company_name',
            'newCompany.address' => 'required',
            'newCompany.contact_number' => 'required',
            'newCompany.contact_person' => 'required',
        ]);

        // Create company
        $company = Company::create([
            'company_name' => $this->newCompany['company_name'],
            'address' => $this->newCompany['address'],
            'contact' => $this->newCompany['contact_number'],
            'contact_person' => $this->newCompany['contact_person'],
            
        ]);
        
        // Create department if provided
        if ($this->newCompany['department']) {
            $department = Department::create([
                'company_id' => $company->id,
                'department_name' => $this->newCompany['department']
            ]);
            
            $this->existingCompanyDepartment = $department->name;
        }

        $this->selectCompany($company->id);
        $this->isCreatingCompany = false;
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Company successfully added to the system!'
        ]);
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompany = Company::findOrFail($companyId);
        $this->companySearch = $this->selectedCompany->company_name;
        $this->companies = collect();
        $this->selectedSupervisor = null;
        
        // Load supervisors for this company's department
        if ($this->existingCompanyDepartment) {
            $this->filteredSupervisors = Supervisor::whereHas('department', function($query) {
                $query->where('department_name', $this->existingCompanyDepartment);
            })
            ->where('company_id', $this->selectedCompany->id)
            ->get();
        }
    }

    public function assignStudent()
    {
        $academic = Academic::where('ay_default', 1)->firstOrFail();

        $this->validate([
            'selectedCompany' => 'required',
            'department' => 'required',
            'custom_hours' => 'nullable|integer|min:1',
        ]);

        Deployment::create([
            'student_id' => $this->student->id,
            'instructor_id' => Auth::user()->instructor->id,
            'supervisor_id' => $this->selectedSupervisor?->id,
            'academic_id' => $academic->id,
            'company_id' => $this->selectedCompany->id,
            'department' => $this->department,
            'custom_hours' => $this->custom_hours,
            'status' => 'pending'
        ]);

        $this->closeModalWithEvents([
            'studentAssigned' => $this->student->id
        ]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => "{$this->student->first_name} successfully assigned to {$this->selectedCompany->company_name}!"
        ]);
    }

    public function cancelCompanyCreation()
    {
        $this->isCreatingCompany = false;
        $this->resetCompanyForm();
    }

    private function resetCompanyForm()
    {
        $this->newCompany = [
            'company_name' => '',
            'address' => '',
            'contact_number' => '',
            'contact_person' => '',
            'email' => '',
            'department' => '',
        ];
    }

    public function render()
    {
        return view('livewire.instructor.assign-modal');
    }
}
<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use LivewireUI\Modal\ModalComponent;

class CompanyModal extends ModalComponent
{
    public $company;
    public $isEditing = false;
    
    public $editableData = [
        'company_name' => '',
        'address' => '',
        'contact_person' => '',
        'contact' => '',
    ];
    protected $messages = [
        'editableData.company_name.required' => 'Company name is required.',
        'editableData.company_name.string' => 'Company name must be text.',
        'editableData.company_name.max' => 'Company name cannot exceed 255 characters.',
        'editableData.address.required' => 'Address is required.',
        'editableData.address.string' => 'Address must be text.',
        'editableData.address.max' => 'Address cannot exceed 255 characters.',
        'editableData.contact_person.required' => 'Contact person is required.',
        'editableData.contact_person.string' => 'Contact person must be text.',
        'editableData.contact_person.max' => 'Contact person cannot exceed 255 characters.',
        'editableData.contact.required' => 'Contact number is required.',
        'editableData.contact.string' => 'Contact number must be text.',
        'editableData.contact.max' => 'Contact number cannot exceed 255 characters.',
        'editableData.contact.regex' => 'Please enter a valid contact number.',
    ];
    public function mount(Company $company)
    {
        $this->company = $company->load([
            'department.deployments.student.user',
            'supervisor.user',
        ]);
        
        $this->editableData = [
            'company_name' => $this->company->company_name,
            'address' => $this->company->address,
            'contact_person' => $this->company->contact_person,
            'contact' => $this->company->contact,
        ];
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveChanges()
    {
        $validated = $this->validate([
            'editableData.company_name' => [
                'required',
                'string',
                'max:255',
                function($attribute, $value, $fail) {
                    $exists = Company::where('company_name', $value)
                        ->where('id', '!=', $this->company->id)
                        ->exists();
                    
                    if ($exists) {
                        $fail('This company name is already taken.');
                    }
                }
            ],
            'editableData.address' => 'required|string|max:255',
            'editableData.contact_person' => 'required|string|max:255',
            'editableData.contact' => [
                'required',
                'string',
                'max:255',
                'regex:/^([0-9\s\-\+\(\)]*)$/'
            ],
        ]);

        try {
            $this->company->update([
                'company_name' => $validated['editableData']['company_name'],
                'address' => $validated['editableData']['address'],
                'contact_person' => $validated['editableData']['contact_person'],
                'contact' => $validated['editableData']['contact'],
            ]);
            
            $this->isEditing = false;
            $this->dispatch('alert', type: 'success', text: 'Company updated successfully!');
            $this->dispatch('refreshCompanies');
            
        } catch (\Exception $e) {
            logger()->error('Error updating company', [
                'error' => $e->getMessage(),
                'company_id' => $this->company->id
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error updating company.');
        }
    }
    public function deleteCompany()
{
    try {
        if ($this->company->department()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete company with existing departments.');
            return;
        }

        if ($this->company->supervisor()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete company with existing supervisors.');
            return;
        }

        $this->company->delete();
        $this->dispatch('alert', type: 'success', text: 'Company deleted successfully!');
        $this->dispatch('refreshCompanies');
        $this->dispatch('closeModal');
        
    } catch (\Exception $e) {
        logger()->error('Error deleting company', [
            'error' => $e->getMessage(),
            'company_id' => $this->company->id
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting company.');
    }
}

public function deleteDepartment($departmentId)
{
    try {
        $department = $this->company->department()->findOrFail($departmentId);
        
        // Check for active deployments
        if ($department->deployments()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete department with active deployments.');
            return;
        }

        // Check for supervisors using the correct relationship
        if ($this->company->supervisor()->where('company_department_id', $departmentId)->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete department with assigned supervisors.');
            return;
        }

        $department->delete();
        $this->company->refresh();
        $this->dispatch('alert', type: 'success', text: 'Department deleted successfully!');
        
    } catch (\Exception $e) {
        logger()->error('Error deleting department', [
            'error' => $e->getMessage(),
            'department_id' => $departmentId,
            'company_id' => $this->company->id
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting department.');
    }
}

    public function render()
    {
        return view('livewire.admin.company-modal');
    }
}
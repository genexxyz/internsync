<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Section;
use LivewireUI\Modal\ModalComponent;

class AddCompanyModal extends ModalComponent
{
    public $companies;
    public $company_name, $address, $contact_person, $contact;

    protected $rules = [
        
        'company_name' => 'required',
        'address' => 'required|string',
        'contact_person' => 'nullable|string',
        'contact' => 'nullable|integer',
    ];

    public function mount()
    {
        $this->companies = Company::all();
    }

    public function saveCompany()
    {

        $exists = Company::where('company_name', $this->company_name)->exists();

    if ($exists) {
        $this->addError('company_name', 'This company is already existing on the system.');
        return;
    }
        $this->validate();

        Company::create([
            'company_name' => $this->company_name,
            'address' => $this->address,
            'contact_person' => $this->contact_person,
            'contact' => $this->contact,
        ]);

        $this->resetForm();
        $this->closeModal();
        $this->dispatch('alert', type: 'success', text: 'Company Added Successfully!');
    }

    public function resetForm()
    {
        $this->reset(['company_name', 'address', 'contact_person', 'contact_email']);
    }

    public function render()
    {
        return view('livewire.admin.add-company-modal');
    }
}

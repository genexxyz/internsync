<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supervisor;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class SupervisorTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $showModal = false;
    public $selectedId;
    
    // Form fields
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $companyDepartmentId;
    public $contactNumber;
    public $companyName;
    public $departmentName;
    protected $queryString = ['search', 'filter'];

    #[On('refreshSupervisors')] 
    public function refresh()
    {
        // This method will be called when the refreshSupervisors event is dispatched
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }

    protected $rules = [
        'firstName' => 'required|min:2',
        'lastName' => 'required|min:2',
        'email' => 'required|email',
        'companyDepartmentId' => 'required|exists:company_departments,id',
        'contactNumber' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    ];

    public function openModal($id)
    {
        $this->selectedId = $id;
        $this->resetValidation();
        $this->resetForm();

        $supervisor = Supervisor::with(['user', 'companyDepartment.company'])->find($id);
        if ($supervisor) {
            $this->firstName = $supervisor->user->first_name;
            $this->lastName = $supervisor->user->last_name;
            $this->email = $supervisor->user->email;
            $this->contactNumber = $supervisor->contact_number;
            $this->companyName = $supervisor->companyDepartment->company->name ?? 'Not assigned';
            $this->departmentName = $supervisor->companyDepartment->name ?? 'No department';
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            // Create new user
            $user = User::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'password' => Hash::make($this->password ?? 'password123'),
                'role' => 'supervisor'
            ]);

            // Create supervisor
            Supervisor::create([
                'user_id' => $user->id,
                'company_department_id' => $this->companyDepartmentId,
                'contact_number' => $this->contactNumber
            ]);

            $this->dispatch('alert', type: 'success', text: 'Supervisor added successfully.');
        } else {
            // Update existing supervisor
            $supervisor = Supervisor::find($this->selectedId);
            $supervisor->user->update([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email
            ]);

            $supervisor->update([
                'company_department_id' => $this->companyDepartmentId,
                'contact_number' => $this->contactNumber
            ]);

            $this->dispatch('alert', type: 'success', text: 'Supervisor updated successfully.');
        }

        $this->closeModal();
        $this->dispatch('refreshSupervisors');
    }

    public function delete($id)
    {
        $supervisor = Supervisor::find($id);
        $supervisor->user->delete(); // This will cascade delete the supervisor
        
        $this->dispatch('alert', type: 'success', text: 'Supervisor deleted successfully.');
    }

    private function resetForm()
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->email = '';
        $this->password = '';
        $this->companyDepartmentId = '';
        $this->contactNumber = '';
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when search is updated
    }

    public function updatedFilter()
    {
        $this->resetPage(); // Reset pagination when filter is updated
    }

    public function render()
    {
        $query = Supervisor::query()
            ->with(['user', 'department.company'])->orderBy('first_name', 'asc')
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filter !== 'all', function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('is_verified', $this->filter === 'verified');
                });
            });
        
        $supervisors = $query->paginate(10);
        
        return view('livewire.admin.supervisor-table', [
            'supervisors' => $supervisors
        ]);
    }
}
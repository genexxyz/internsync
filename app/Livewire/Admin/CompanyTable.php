<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instructor;
use App\Models\Section;

class CompanyTable extends Component
{
    use WithPagination;
    public function paginationView()

    {

        return 'vendor.pagination.tailwind';

    }
    public $search = '';

    public $modalOpen = false;
    public $selectedCompany = null; // For the modal data

    protected $queryString = ['search'];

    

    public function closeModal()
    {
        $this->selectedCompany = null;
        $this->modalOpen = false;
        

        // Set to null to close the modal
    }
    public function render()
    {
        $query = Company::query()->orderBy('company_name','asc');

        

        
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%');
            });
        }


        $companies = $query->paginate(10);
        return view('livewire.admin.company-table', [
            'companies' => $companies,
        ]);
    }

    public function updatedSearch()
{
    $this->resetPage(); // Reset pagination when search is updated
}

public function updatedFilter()
{
    $this->resetPage(); // Reset pagination when filter is updated
}
}

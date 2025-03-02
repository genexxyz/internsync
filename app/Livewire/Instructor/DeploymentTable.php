<?php
namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;

class DeploymentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // Default filter value: all, deployed, not_deployed
    public $section_id; // ID of the section to filter students by

    protected $queryString = ['search', 'filter']; // Persist filter and search in the query string

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }

    public function render()
    {
        $query = Student::query()
            ->with([
                'user',
                'yearSection',
                'deployment' // Assuming this relationship exists
            ]);

        // Filter by section_id
        if ($this->section_id) {
            $query->whereHas('yearSection', function ($q) {
                $q->where('id', $this->section_id); // Filter students in the selected section
            });
        }

        // Filtering based on deployment status
        if ($this->filter === 'deployed') {
            $query->whereHas('deployment', function ($q) {
                $q->whereNotNull('company_id'); // Students with an existing deployment
            });
        } elseif ($this->filter === 'not_deployed') {
            $query->whereDoesntHave('deployment', function ($q) {
                $q->whereNotNull('company_id'); // Students without a deployment
            });
        }

        // Search functionality
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('yearSection', function ($sq) {
                        $sq->where('class_section', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $students = $query->orderBy('last_name', 'asc')->paginate(10);

        return view('livewire.instructor.deployment-table', [
            'students' => $students,
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }
}

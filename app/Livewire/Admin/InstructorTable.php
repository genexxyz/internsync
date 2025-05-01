<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instructor;
use Livewire\Attributes\On; 
use App\Models\Section;

class InstructorTable extends Component
{
    use WithPagination;
    public function paginationView()

    {

        return 'vendor.pagination.tailwind';

    }
    public $search = '';
    public $filter = 'all'; // Default filter value
    public $modalOpen = false;
    public $selectedInstructor = null; // For the modal data
    public $courses = [];

    protected $queryString = ['search', 'filter'];

    #[On('refreshInstructors')] 
    public function refresh()
    {
        // This method will be called when the refreshInstructors event is dispatched
        $this->render();
    }

    public function closeModal()
    {
        $this->selectedInstructor = null;
        $this->modalOpen = false;
        

        // Set to null to close the modal
    }
    public function mount(){
        $this->courses = Course::all();
    }
    public function render()
    {
        $query = Instructor::query()->orderBy('first_name','asc')
            ->with(['user', 'handles'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'instructor');
            });

        // Changed filtering to check verification status
        if ($this->filter === 'verified') {
            $query->whereHas('user', function ($q) {
                $q->where('is_verified', 1);
            });
        } elseif ($this->filter === 'unverified') {
            $query->whereHas('user', function ($q) {
                $q->where('is_verified', 0);
            });
        }

        // Search functionality remains the same
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('suffix', 'like', '%' . $this->search . '%');
            });
        }

        
        $instructors = $query->paginate(10);
        return view('livewire.admin.instructor-table', [
            'instructors' => $instructors,
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

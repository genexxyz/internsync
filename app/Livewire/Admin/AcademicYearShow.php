<?php

namespace App\Livewire\Admin;

use App\Models\Academic;
use App\Models\Program;
use App\Models\Handle;
use App\Models\Deployment;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicYearShow extends Component
{
    use WithPagination;

    public Academic $academic;
    public $activeTab = 'instructors';
    public $search = '';
    
    public function mount(Academic $academic)
    {
        $this->academic = $academic;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function getInstructorsProperty()
    {
        return Program::where('academic_year_id', $this->academic->id)
            ->whereHas('instructor.user', function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })
            ->with(['instructor.user', 'course'])
            ->paginate(10);
    }

    public function getStudentsProperty()
    {
        return Deployment::where('academic_id', $this->academic->id)
            ->whereHas('student.user', function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })
            ->with(['student.user', 'student.section.course', 'company'])
            ->paginate(10);
    }

    public function getSupervisorsProperty()
    {
        return Deployment::where('academic_id', $this->academic->id)
            ->whereHas('supervisor.user', function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })
            ->with(['supervisor.user', 'company', 'department'])
            ->distinct('supervisor_id')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.academic-year-show');
    }
}
<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Course;

class AcceptanceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $courseFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
{
    $students = Student::query()
        ->with(['user', 'section.course', 'acceptance_letter', 'deployment'])
        ->when($this->search, function ($query) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
            });
        })
        ->when($this->courseFilter, function ($query) {
            $query->whereHas('section.course', function ($q) {
                $q->where('id', $this->courseFilter);
            });
        })
        ->when($this->sectionFilter, function ($query) {
            $query->where('year_section_id', $this->sectionFilter);
        })
        ->when($this->statusFilter, function ($query) {
            switch ($this->statusFilter) {
                case 'pending':
                    // No acceptance letter, no deployment or deployment without company and supervisor
                    $query->where(function($q) {
                        $q->whereDoesntHave('acceptance_letter')
                          ->where(function($sq) {
                              $sq->whereDoesntHave('deployment')
                                 ->orWhereHas('deployment', function($dq) {
                                     $dq->whereNull('company_id')
                                        ->whereNull('supervisor_id');
                                 });
                          });
                    });
                    break;
                    
                case 'for_review':
                    // Has acceptance letter but no deployment or deployment without company and supervisor
                    $query->whereHas('acceptance_letter')
                          ->where(function($q) {
                              $q->whereDoesntHave('deployment')
                                ->orWhereHas('deployment', function($dq) {
                                    $dq->whereNull('company_id')
                                       ->whereNull('supervisor_id');
                                });
                          });
                    break;
                    
                case 'waiting_for_supervisor':
                    // Has acceptance letter, has company but no supervisor
                    $query->whereHas('acceptance_letter')
                          ->whereHas('deployment', function($q) {
                              $q->whereNotNull('company_id')
                                ->whereNull('supervisor_id');
                          });
                    break;
                    
                case 'deployed':
                    // Has everything: acceptance letter, company, and supervisor
                    $query->whereHas('acceptance_letter')
                          ->whereHas('deployment', function($q) {
                              $q->whereNotNull('company_id')
                                ->whereNotNull('supervisor_id');
                          });
                    break;
            }
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);

    return view('livewire.admin.documents.acceptance-table', [
        'students' => $students,
        'courses' => Course::all()
    ]);
}
}
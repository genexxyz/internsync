<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MoaRequest;
use App\Models\Course;

class MoaTable extends Component
{
    use WithPagination;

    public $search = '';
    public $courseFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
protected $listeners = ['moaStatusUpdated' => 'render'];
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

    public function updateStatus(MoaRequest $moaRequest, $newStatus)
    {
        $statusTimestamp = match($newStatus) {
            'for_pickup' => ['for_pickup_at' => now()],
            'picked_up' => ['picked_up_at' => now()],
            'received_by_company' => ['received_by_company_at' => now()],
            default => []
        };

        $moaRequest->update([
            'status' => $newStatus,
            ...$statusTimestamp
        ]);
    }

    public function render()
    {
        $requests = MoaRequest::query()
            ->with(['student.section.course', 'company'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->whereHas('student', function($sq) {
                        $sq->where('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%')
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                    })->orWhereHas('company', function($sq) {
                        $sq->where('company_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->courseFilter, function ($query) {
                $query->whereHas('student.section.course', function ($q) {
                    $q->where('id', $this->courseFilter);
                });
            })
            ->when($this->sectionFilter, function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('year_section_id', $this->sectionFilter);
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.documents.moa-table', [
            'requests' => $requests,
            'courses' => Course::all()
        ]);
    }
}
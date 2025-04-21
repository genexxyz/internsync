<?php
namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EndorsementRequest;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class EndorsementTable extends Component
{
    use WithPagination;

    public $search = '';
    public $courseFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $receivedBy = [];

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

    public function updateStatus(EndorsementRequest $request, $newStatus)
    {
        if ($newStatus === 'picked_up') {
            $this->validate([
                "receivedBy.{$request->id}" => 'required|string|min:3'
            ], [
                "receivedBy.{$request->id}.required" => 'Please enter who received the letter',
                "receivedBy.{$request->id}.min" => 'Name must be at least 3 characters'
            ]);
        }

        $statusTimestamp = match($newStatus) {
            'for_pickup' => ['for_pickup_at' => now()],
            'picked_up' => [
                'picked_up_at' => now(),
                'received_by' => $this->receivedBy[$request->id] ?? null,
                'admin_id' => Auth::id()
            ],
            default => []
        };

        $request->update([
            'status' => $newStatus,
            ...$statusTimestamp
        ]);

        // Improved notification messages
    $message = match($newStatus) {
        'for_pickup' => "Your endorsement letter is ready for pickup at the OJT Office. Please bring a valid ID.",
        'picked_up' => "Your endorsement letter has been picked up by {$this->receivedBy[$request->id]}.",
        default => "Your endorsement letter status has been updated to " . str_replace('_', ' ', $newStatus)
    };

    $icon = match($newStatus) {
        'for_pickup' => 'fa-envelope',
        'picked_up' => 'fa-check-circle',
        default => 'fa-file-signature'
    };

    $title = match($newStatus) {
        'for_pickup' => 'Endorsement Letter Ready',
        'picked_up' => 'Endorsement Letter Picked Up',
        default => 'Endorsement Letter Update'
    };

    Notification::send(
        $request->student->user_id,
        'endorsement_' . $newStatus,
        $title,
        $message,
        'student.document',
        $icon
    );
    $this->dispatch('alert', 
    type:'success',
    text: "Endorsement Letter request status updated to " . str_replace('_', ' ', $newStatus)
);
        if ($newStatus === 'picked_up') {
            unset($this->receivedBy[$request->id]);
        }
    }

    public function render()
    {
        $requests = EndorsementRequest::query()
            ->with(['student.section.course', 'company'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->whereHas('student', function($sq) {
                        $sq->where('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%')
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
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

        return view('livewire.admin.documents.endorsement-table', [
            'requests' => $requests,
            'courses' => Course::all()
        ]);
    }
}
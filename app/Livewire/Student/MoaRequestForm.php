<?php
namespace App\Livewire\Student;

use LivewireUI\Modal\ModalComponent;
use App\Models\MoaRequest;
use Illuminate\Support\Facades\Auth;

class MoaRequestForm extends ModalComponent
{
    public $company;
    public $companyNumber;
    public $officerName;
    public $officerPosition;
    public $witnessName;
    public $witnessPosition;
    public $existingRequest;
    
    protected $rules = [
        'companyNumber' => 'required|string|max:50',
        'officerName' => 'required|string|max:255',
        'officerPosition' => 'required|string|max:255',
        'witnessName' => 'required|string|max:255',
        'witnessPosition' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->company = Auth::user()->student->deployment->company;
        $this->existingRequest = MoaRequest::where('company_id', $this->company->id)
            ->where('requested_by', Auth::user()->student->id)
            ->latest()
            ->first();
    }

    public function getStatusColor()
    {
        return match($this->existingRequest?->status) {
            'requested' => 'bg-blue-50 text-blue-700',
            'for_pickup' => 'bg-yellow-50 text-yellow-700',
            'picked_up' => 'bg-purple-50 text-purple-700',
            'received_by_company' => 'bg-green-50 text-green-700',
            default => 'bg-gray-50 text-gray-700'
        };
    }

    public function getStatusIcon()
    {
        return match($this->existingRequest?->status) {
            'requested' => 'fa-clock',
            'for_pickup' => 'fa-box',
            'picked_up' => 'fa-truck',
            'received_by_company' => 'fa-check-circle',
            default => 'fa-info-circle'
        };
    }

    public function submit()
    {
        // Check for existing request
        if ($this->existingRequest) {
            session()->flash('error', 'You already have a MOA request for this company.');
            return;
        }

        $this->validate();

        try {
            MoaRequest::create([
                'company_id' => $this->company->id,
                'company_number' => $this->companyNumber,
                'officer_name' => $this->officerName,
                'officer_position' => $this->officerPosition,
                'witness_name' => $this->witnessName,
                'witness_position' => $this->witnessPosition,
                'requested_by' => Auth::user()->student->id,
                'status' => 'requested',
                'requested_at' => now(),
            ]);

            $this->dispatch('moaRequestCreated');
            $this->dispatch('closeModal');
            
            session()->flash('message', 'MOA request submitted successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit MOA request. Please try again.');
        }
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render()
    {
        return view('livewire.student.moa-request-form');
    }
}
<?php

namespace App\Livewire\Admin;

use App\Models\Academic;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicYear extends Component
{
    use WithPagination;

    public $isEditing = false;
    public $editingId = null;
    public $showDeleteModal = false;
    public $academicToDelete = null;
    public $showCreateForm = false;
public $newAcademic = [
    'academic_year' => '',
    'semester' => '',
    'description' => '',
    'start_date' => '',
    'end_date' => '',
];

    public $editableData = [
        'academic_year' => '',
        'semester' => '',
        'description' => '',
        'start_date' => '',
        'end_date' => '',
    ];

    protected $rules = [
        'editableData.academic_year' => 'required|string|max:9',
        'editableData.semester' => 'required|string',
        'editableData.description' => 'nullable|string|max:255',
        'editableData.start_date' => 'required|date|min:today',
        'editableData.end_date' => 'required|date|after:editableData.start_date',
    ];

    public function createAcademic()
{
    $this->validate([
        'newAcademic.academic_year' => 'required|string|max:9',
        'newAcademic.semester' => 'required|string',
        'newAcademic.description' => 'nullable|string|max:255',
        'newAcademic.start_date' => 'required|date|min:today',
        'newAcademic.end_date' => 'required|date|after:newAcademic.start_date',
    ]);
if (Academic::where('academic_year', $this->newAcademic['academic_year'])
        ->where('semester', $this->newAcademic['semester'])
        ->exists()) {
        $this->dispatch('alert', type: 'error', text: 'Academic year already exists.');
        return;
    }
    try {
        Academic::create([
            'academic_year' => $this->newAcademic['academic_year'],
            'semester' => $this->newAcademic['semester'],
            'description' => $this->newAcademic['description'],
            'start_date' => $this->newAcademic['start_date'],
            'end_date' => $this->newAcademic['end_date'],
            'status' => 1
        ]);

        $this->showCreateForm = false;
        $this->reset('newAcademic');
        $this->dispatch('alert', type: 'success', text: 'Academic year created successfully!');
    } catch (\Exception $e) {
        logger()->error('Error creating academic year', [
            'error' => $e->getMessage(),
            'academic_year' => $this->newAcademic['academic_year'],
            'semester' => $this->newAcademic['semester'],
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error creating academic year.');
    }
}

    public function startEditing($academicId)
    {
        $academic = Academic::findOrFail($academicId);
        $this->editingId = $academicId;
        $this->editableData = [
            'academic_year' => $academic->academic_year,
            'semester' => $academic->semester,
            'description' => $academic->description,
            'start_date' => $academic->start_date?->format('Y-m-d'),
            'end_date' => $academic->end_date?->format('Y-m-d'),
        ];
        $this->isEditing = true;
    }

    public function saveChanges()
    {
        $this->validate();

        try {
            $academic = Academic::findOrFail($this->editingId);
            $academic->update($this->editableData);
            
            $this->dispatch('alert', type: 'success', text: 'Academic year updated successfully!');
            $this->cancelEditing();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error updating academic year.');
        }
    }

    public function confirmDelete($academicId)
    {
        $this->academicToDelete = $academicId;
        $this->showDeleteModal = true;
    }

    public function deleteAcademic()
    {
        try {
            $academic = Academic::findOrFail($this->academicToDelete);
            
            // Check for related records
            if ($academic->sections()->exists()) {
                $this->dispatch('alert', type: 'error', text: 'Cannot delete academic year with existing sections.');
                return;
            }
            
            if ($academic->deployments()->exists()) {
                $this->dispatch('alert', type: 'error', text: 'Cannot delete academic year with existing deployments.');
                return;
            }

            $academic->delete();
            $this->showDeleteModal = false;
            $this->dispatch('alert', type: 'success', text: 'Academic year deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error deleting academic year.');
        }
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->editableData = [
            'academic_year' => '',
            'semester' => '',
            'description' => '',
            'start_date' => '',
            'end_date' => '',
        ];
    }

    public function setDefault($academicId)
    {
        try {
            Academic::where('ay_default', true)->update(['ay_default' => false]);
            Academic::findOrFail($academicId)->update(['ay_default' => true]);
            $this->dispatch('alert', type: 'success', text: 'Default academic year set successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error setting default academic year.');
        }
    }

    public function render()
    {
        return view('livewire.admin.academic-year', [
            'academics' => Academic::orderBy('academic_year', 'desc')
                ->orderBy('semester', 'asc')
                ->paginate(10)
        ]);
    }
}
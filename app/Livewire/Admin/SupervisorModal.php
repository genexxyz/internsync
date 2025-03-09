<?php

namespace App\Livewire\Admin;

use App\Models\Supervisor;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\DB;

class SupervisorModal extends ModalComponent
{
    public Supervisor $supervisor;

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(Supervisor $supervisor)
    {
        $this->supervisor = $supervisor->load([
            'user',
            'department',
            'deployments.student'
        ]);
    }

    public function verifySupervisor()
    {
        if (!$this->supervisor->user) {
            $this->dispatch('alert', type: 'error', text: 'Error verifying!');
            return;
        }

        try {
            DB::beginTransaction();
            
            // Update user verification only
            $this->supervisor->user->update(['is_verified' => 1]);
            
            DB::commit();
            
            $this->dispatch('refreshSupervisors');
            $this->dispatch('alert', type: 'success', text: 'The supervisor has been verified successfully!');
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error verifying supervisor', [
                'error' => $e->getMessage(),
                'supervisor_id' => $this->supervisor->id
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error verifying supervisor!');
        }
    }

    public function deleteSupervisor()
    {
        // Ensure the user and supervisor exist
        if ($this->supervisor->user) {
            // Delete the user record
            $this->supervisor->user->delete();
        }

        // Delete the supervisor record
        $this->supervisor->delete();

        // Dispatch success notification
        $this->dispatch('closeModal');
        $this->dispatch('refreshSupervisors');
        $this->dispatch('alert', type: 'success', text: 'The supervisor has been deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.supervisor-modal', [
            'supervisor' => $this->supervisor
        ]);
    }
}
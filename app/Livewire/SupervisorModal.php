<?php

namespace App\Livewire;


use App\Models\Supervisor;
use LivewireUI\Modal\ModalComponent;

class SupervisorModal extends ModalComponent
{
    public Supervisor $user;

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(Supervisor $supervisor)
    {
        
        $this->user = $supervisor->load([
            'deployment',
            'user',
            'company',
        ]);


        
    }

    public function verifySupervisor()
    {
        if (!$this->user->user) {
            $this->dispatch('alert', type: 'error', text: 'Error verifying!');
        }

        $this->user->user->update(['is_verified' => 1]);
        $this->dispatch('alert', type: 'success', text: 'The supervisor has been verified!');
    }

  

    
    public function deleteSupervisor()
    {
        // Ensure the user and instructor exist
        if ($this->user->user) {
            // Delete the user record
            $this->user->user->delete();
        }

        // Delete the instructor record
        $this->user->delete();

        // Dispatch success notification
        $this->dispatch('closeModal');
        $this->dispatch('alert', type: 'success', text: 'The supervisor has been deleted successfully!');

    }
    public function render()
    {
        return view('livewire.supervisor-modal', [
            'supervisor' => $this->user
        ]);
    }
}

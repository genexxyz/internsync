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
        
        // Update user verification
        $this->supervisor->user->update(['is_verified' => 1]);
        
        // Get the supervisor's company department ID
        $companyDeptId = $this->supervisor->company_department_id;
        
        // Find deployments and assign the supervisor based on available information
        if ($companyDeptId) {
            // Case 1: Supervisor has a specific department - update deployments for that department
            $updatedCount = DB::table('deployments')
                ->where('company_dept_id', $companyDeptId)
                ->whereNull('supervisor_id')
                ->update(['supervisor_id' => $this->supervisor->id]);
                
            logger()->info('Assigned supervisor to department deployments', [
                'supervisor_id' => $this->supervisor->id,
                'company_dept_id' => $companyDeptId,
                'updated_deployments' => $updatedCount
            ]);
        } else if ($this->supervisor->department && $this->supervisor->department->company_id) {
            // Case 2: Supervisor has company but no specific department - update any deployments for that company without a supervisor
            $companyId = $this->supervisor->department->company_id;
            
            $updatedCount = DB::table('deployments')
                ->where('company_id', $companyId)
                ->whereNull('supervisor_id')
                ->update(['supervisor_id' => $this->supervisor->id]);
                
            logger()->info('Assigned supervisor to company-wide deployments', [
                'supervisor_id' => $this->supervisor->id,
                'company_id' => $companyId,
                'updated_deployments' => $updatedCount
            ]);
        }
        
        DB::commit();
        
        $this->dispatch('refreshSupervisors');
        $this->dispatch('alert', type: 'success', text: 'The supervisor has been verified and assigned to relevant deployments!');
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
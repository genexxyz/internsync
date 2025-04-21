<?php
namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public $student;
    public $contact;
    public $signature_path;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showSignatureModal = false;
    public $currentSignature;
    public $name;
    public $permitFile;

    protected $listeners = [
        'signature-saved' => 'closeSignatureModal'
    ];

    protected $rules = [
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'signature_path' => 'nullable|image|max:1024',
        'permitFile' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
    ];

    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->contact = $this->student->contact;
        $this->currentSignature = $this->student->signature_path;
        $this->name = $this->student->name();

    }

    public function updatedPermitFile()
{
    $this->validateOnly('permitFile');

    try {
        // Generate filename with student info
        $extension = $this->permitFile->getClientOriginalExtension();
        $randomString = \Illuminate\Support\Str::random(8);
        $filename = sprintf(
            '%s_%s_%s_%s.%s',
            $this->student->student_id,
            strtolower($this->student->first_name),
            strtolower($this->student->last_name),
            $randomString,
            $extension
        );

        // Store file with custom name
        $path = $this->permitFile->storeAs('permits', $filename, 'public');
        
        // Delete old permit if exists
        if ($this->student->deployment->permit_path) {
            Storage::disk('public')->delete($this->student->deployment->permit_path);
        }

        // Update deployment record
        $this->student->deployment->update([
            'permit_path' => $path
        ]);

        $this->permitFile = null;
        session()->flash('message', 'Permit uploaded successfully.');

    } catch (\Exception $e) {
        session()->flash('error', 'Failed to upload permit. Please try again.');
    }
}

public function deletePermit()
{
    try {
        if ($this->student->deployment->permit_path) {
            Storage::disk('public')->delete($this->student->deployment->permit_path);
            $this->student->deployment->update(['permit_path' => null]);
            session()->flash('message', 'Permit deleted successfully.');
        }
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to delete permit. Please try again.');
    }
}

    public function closeSignatureModal()
    {
        $this->showSignatureModal = false;
        $this->currentSignature = $this->student->fresh()->signature_path;
    }

    protected function passwordRules()
    {
        return [
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ];
    }

    // public function updatePassword()
    // {
    //     $this->validate($this->passwordRules());

    //     Auth::user()->update([
    //         'password' => Hash::make($this->new_password)
    //     ]);

    //     $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

    //     $this->dispatch('alert', type: 'success', text: 'Password changed successfully!');
    // }

    public function updateProfile()
    {
        $this->validate();

        $data = [
            'contact' => $this->contact,
        ];

        if ($this->signature_path) {
            if ($this->student->signature_path) {
                Storage::disk('public')->delete($this->student->signature_path);
            }

            $extension = $this->signature_path->getClientOriginalExtension();
            $randomString = \Illuminate\Support\Str::random(8);
            $filename = sprintf(
                '%s_%s_%s_%s_%s.%s',
                $this->student->user->id,
                'student',
                strtolower($this->student->last_name),
                strtolower($this->student->first_name),
                $randomString,
                $extension
            );

            $path = $this->signature_path->storeAs('signatures', $filename, 'public');
            $data['signature_path'] = $path;
            $this->currentSignature = $path;
        }

        $this->student->update($data);

        $this->dispatch('alert', type: 'success', text: 'Profile updated successfully!');
    }

    // public function deleteSignature()
    // {
    //     if ($this->student->signature_path) {
    //         Storage::disk('public')->delete($this->student->signature_path);
    //         $this->student->update(['signature_path' => null]);
    //         $this->currentSignature = null;
    //     }

    //     $this->dispatch('alert', type: 'success', text: 'E-Signature deleted successfully!');
    // }

    public function deleteAccount()
    {
        if ($this->student->signature_path) {
            Storage::disk('public')->delete($this->student->signature_path);
        }
        
        $user = Auth::user();
        $this->student->delete();
        $user->delete();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.student.profile');
    }
}
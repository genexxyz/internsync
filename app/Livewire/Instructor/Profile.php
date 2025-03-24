<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public $instructor;
    public $contact;
    public $signature_path;
    public $showDeleteModal = false;
    public $currentSignature;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showSignatureModal = false;
    
    protected $listeners = [
        'signature-saved' => 'closeSignatureModal'
    ];
    
    public function closeSignatureModal()
    {
        $this->showSignatureModal = false;
    }
    protected $rules = [
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'signature_path' => 'nullable|image|max:1024',
    ];

    protected function passwordRules()
    {
        return [
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ];
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules());

        Auth::user()->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('alert', type: 'success', text: 'Password changed successfully!');
    }

    public function mount()
    {
        $this->instructor = Auth::user()->instructor;
        $this->contact = $this->instructor->contact;
        $this->currentSignature = $this->instructor->signature_path;
    }

    public function updateProfile()
{
    $this->validate();

    $data = [
        'contact' => $this->contact,
    ];

    // Handle signature upload
    if ($this->signature_path) {
        // Delete old signature if exists
        if ($this->instructor->signature_path) {
            Storage::disk('public')->delete($this->instructor->signature_path);
        }

        // Generate unique filename
        $extension = $this->signature_path->getClientOriginalExtension();
        $randomString = \Illuminate\Support\Str::random(8);
        $filename = sprintf(
            '%s_%s_%s_%s_%s.%s',
            $this->instructor->user->id,
            'instructor',
            strtolower($this->instructor->last_name),
            strtolower($this->instructor->first_name),
            $randomString,
            $extension
        );

        // Store new signature with custom filename
        $path = $this->signature_path->storeAs('signatures', $filename, 'public');
        $data['signature_path'] = $path;
        $this->currentSignature = $path;
    }

    $this->instructor->update($data);

    $this->dispatch('alert', type: 'success', text: 'Profile updated successfully!');
}

    public function deleteSignature()
    {
        if ($this->instructor->signature_path) {
            Storage::disk('public')->delete($this->instructor->signature_path);
            $this->instructor->update(['signature_path' => null]);
            $this->currentSignature = null;
        }

        $this->dispatch('alert', type: 'success', text: 'E-Signature deleted successfully!');
    }

    public function deleteAccount()
    {
        // Delete related files
        if ($this->instructor->signature_path) {
            Storage::disk('public')->delete($this->instructor->signature_path);
        }
        if ($this->instructor->supporting_doc) {
            Storage::disk('public')->delete($this->instructor->supporting_doc);
        }
        if ($this->instructor->image) {
            Storage::disk('public')->delete($this->instructor->image);
        }

        // Delete the instructor and associated user
        $user = Auth::user();
        $this->instructor->delete();
        $user->delete();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.instructor.profile');
    }
}
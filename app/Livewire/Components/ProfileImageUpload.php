<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileImageUpload extends Component
{
    use WithFileUploads;

    public $profileImage;
    public $currentImage;
    public $userRole;
    public $roleModel;

    public function mount()
    {
        $this->userRole = Auth::user()->role;
        $this->loadRoleModel();
        $this->currentImage = $this->roleModel->image;
    }

    protected function loadRoleModel()
    {
        $user = Auth::user();
        $this->roleModel = match($this->userRole) {
            'admin' => $user->admin,
            'student' => $user->student,
            'instructor' => $user->instructor,
            'supervisor' => $user->supervisor,
            default => null
        };
    }

    public function updatedProfileImage()
    {
        $this->validate([
            'profileImage' => 'image|max:2048|mimes:jpg,jpeg,png'
        ]);

        try {
            $extension = $this->profileImage->getClientOriginalExtension();
            $filename = sprintf(
                'profile_%s_%s_%s.%s',
                $this->userRole,
                Str::slug(Auth::user()->name),
                Str::random(8),
                $extension
            );

            $path = $this->profileImage->storeAs(
                'profiles/' . $this->userRole,
                $filename,
                'public'
            );

            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
            }

            $this->roleModel->update(['image' => $path]);
            $this->currentImage = $path;
            $this->profileImage = null;

            $this->dispatch('alert', type: 'success', text: 'Profile picture updated successfully!');

        } catch (\Exception $e) {
            logger()->error('Profile picture upload error:', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', text: 'Failed to upload profile picture.');
        }
    }

    public function deleteImage()
    {
        try {
            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
                $this->roleModel->update(['image' => null]);
                $this->currentImage = null;
                $this->dispatch('alert', type: 'success', text: 'Profile picture removed successfully!');
            }
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Failed to remove profile picture.');
        }
    }

    public function render()
    {
        return view('livewire.components.profile-image-upload');
    }
}
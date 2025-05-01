<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPVerification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class Profile extends Component
{
    use WithFileUploads;

    public $admin;
    public $firstName;
    public $middleName;
    public $lastName;
    public $suffix;
    public $email;
    public $newImage;
    public $currentImage;
    public $newEmail;
    public $showOtpModal = false;
    public $otp;
    public $generatedOtp;
    public $showEmailChange = false;
    public $showOtpInput = false;
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;
    public $showPasswordChange = false;

    protected function rules()
    {
        return [
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email',
            'newEmail' => ['nullable', 'email', 'unique:users,email,' . Auth::id()],
            'newImage' => 'nullable|image|max:1024',
            'otp' => 'nullable|string|size:6',
            'currentPassword' => 'required_if:showPasswordChange,true',
            'newPassword' => 'required_if:showPasswordChange,true|min:8|different:currentPassword',
            'confirmPassword' => 'required_if:showPasswordChange,true|same:newPassword',
        ];
    }

    public function mount()
    {
        $this->admin = Auth::user()->admin;
        $this->firstName = $this->admin->first_name;
        $this->middleName = $this->admin->middle_name;
        $this->lastName = $this->admin->last_name;
        $this->suffix = $this->admin->suffix;
        $this->email = Auth::user()->email;
        $this->currentImage = $this->admin->image;
    }

    public function updatedEmail($value)
    {
        if ($value !== Auth::user()->email) {
            $this->newEmail = $value;
            $this->sendOTP();
        }
    }
    public function toggleEmailChange()
    {
        $this->showEmailChange = !$this->showEmailChange;
        if (!$this->showEmailChange) {
            $this->resetEmailChange();
        }
    }

    public function togglePasswordChange()
    {
        $this->showPasswordChange = !$this->showPasswordChange;
        if (!$this->showPasswordChange) {
            $this->resetPasswordFields();
        }
    }

    public function initiateEmailChange()
    {
        $this->validate([
            'newEmail' => ['required', 'email', 'unique:users,email,' . Auth::id()]
        ]);

        if ($this->newEmail === Auth::user()->email) {
            $this->dispatch('alert', type: 'info', text: 'New email is the same as current email.');
            return;
        }

        $this->generatedOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        try {
            Mail::to($this->newEmail)->send(new OTPVerification($this->generatedOtp));
            $this->showOtpInput = true;
            $this->dispatch('alert', type: 'success', text: 'OTP sent to your new email address.');
        } catch (\Exception $e) {
            logger()->error('Error sending OTP:', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', text: 'Error sending OTP. Please try again.');
        }
    }

    public function resetEmailChange()
    {
        $this->showEmailChange = false;
        $this->showOtpInput = false;
        $this->newEmail = null;
        $this->otp = null;
        $this->generatedOtp = null;
        $this->resetErrorBag();
    }

    public function resetPasswordFields()
    {
        $this->currentPassword = null;
        $this->newPassword = null;
        $this->confirmPassword = null;
        $this->showPasswordChange = false;
        $this->resetErrorBag();
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        try {
            Auth::user()->update([
                'password' => Hash::make($this->newPassword)
            ]);

            $this->dispatch('alert', type: 'success', text: 'Password updated successfully.');
            $this->resetPasswordFields();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error updating password.');
        }
    }
    public function sendOTP()
    {
        $this->validate([
            'newEmail' => ['required', 'email', 'unique:users,email,' . Auth::id()]
        ]);
        // Check if the new email is different from the current email
        if ($this->newEmail === Auth::user()->email) {
            $this->dispatch('alert', type: 'info', text: 'New email is the same as the current email.');
            return;
        }
        // Check if the new email is already in use
        if (User::where('email', $this->newEmail)->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Email already in use.');
            return;
        }

        // Generate 6-digit OTP
        $this->generatedOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        try {
            Mail::to($this->newEmail)->send(new OTPVerification($this->generatedOtp));
            $this->showOtpModal = true;
            $this->dispatch('alert', type: 'success', text: 'OTP sent to your new email address.');
        } catch (\Exception $e) {
            logger()->error('Error sending OTP:', ['error' => $e->getMessage()]);
            $this->dispatch('alert', type: 'error', text: 'Error sending OTP. Please try again.');
        }
    }

    public function verifyOTP()
    {
        $this->validate(['otp' => 'required|size:6']);

        if ($this->otp === $this->generatedOtp) {
            try {
                Auth::user()->update(['email' => $this->newEmail]);
                $this->email = $this->newEmail;
                $this->newEmail = null;
                $this->otp = null;
                $this->generatedOtp = null;
                $this->showOtpModal = false;
                $this->dispatch('alert', type: 'success', text: 'Email updated successfully.');
            } catch (\Exception $e) {
                $this->dispatch('alert', type: 'error', text: 'Error updating email.');
            }
        } else {
            $this->addError('otp', 'Invalid OTP code.');
        }
    }

    public function cancelEmailChange()
    {
        $this->email = Auth::user()->email;
        $this->newEmail = null;
        $this->otp = null;
        $this->generatedOtp = null;
        $this->showOtpModal = false;
        $this->resetErrorBag();
    }

    public function updateProfile()
    {
        $this->validate();

        try {
            // Handle image upload
            if ($this->newImage) {
                if ($this->currentImage) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                $imagePath = $this->newImage->store('profile-photos', 'public');
            }

            // Update admin details
            $this->admin->update([
                'first_name' => $this->firstName,
                'middle_name' => $this->middleName,
                'last_name' => $this->lastName,
                'suffix' => $this->suffix,
                'image' => $this->newImage ? $imagePath : $this->currentImage,
            ]);

            $this->dispatch('alert', type: 'success', text: 'Profile updated successfully.');
            $this->newImage = null;

        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', text: 'Error updating profile.');
        }
    }

    public function deleteImage()
    {
        if ($this->currentImage) {
            Storage::disk('public')->delete($this->currentImage);
            $this->admin->update(['image' => null]);
            $this->currentImage = null;
            $this->dispatch('alert', type: 'success', text: 'Profile photo removed.');
        }
    }

    public function render()
    {
        return view('livewire.admin.profile');
    }
}
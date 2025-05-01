<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;

class Settings extends Component
{
    use WithFileUploads;

    public $system_name;
    public $school_name;

    public $school_address;
    public $system_email;
    public $system_contact;
    public $default_theme;
    public $default_logo;

    public $logo;
    public $headerImage;
public $footerImage;
public $uploadedHeaderPath;
public $uploadedFooterPath;
    public $uploadProgress = 0;  // Track upload progress
    public $uploadedLogoPath = null;
    public $minimum_hours;

    public function mount()
    {
        // Preload existing data from the database if available
        $settings = Setting::first();

        if ($settings) {
            $this->system_name = $settings->system_name;
            $this->school_name = $settings->school_name;
            $this->school_address = $settings->school_address;
            $this->system_email = $settings->system_email;
            $this->system_contact = $settings->system_contact;
            $this->default_theme = $settings->default_theme;
            $this->uploadedLogoPath = $settings->default_logo;
            $this->minimum_hours = $settings->minimum_hours;
            $this->uploadedHeaderPath = $settings->header_image;
        $this->uploadedFooterPath = $settings->footer_image;
            
        }
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:2048|mimes:svg,png,jpg,jpeg', // 2MB max
        ]);
    }

    public function uploadLogo()
{
    $this->validate([
        'logo' => 'required|image|max:2048|mimes:svg,png,jpg,jpeg',
    ]);

    try {
        $this->uploadProgress = 25;

        // Generate a unique filename
        $filename = 'logo_' . Str::uuid() . '.' . $this->logo->getClientOriginalExtension();
        $folder = config('app.logo_path', 'logos');

        // Store the file
        $path = $this->logo->storeAs($folder, $filename, 'public');

        $this->uploadProgress = 75;

        // Get settings or create new
        $settings = Setting::first() ?? new Setting();

        // Delete old logo if it exists and isn't the default
        if ($settings->default_logo && Storage::disk('public')->exists($settings->default_logo) && $settings->default_logo !== 'logos/default-logo.png') {
            Storage::disk('public')->delete($settings->default_logo);
        }

        // Update logo path
        $settings->default_logo = $path;
        $settings->save();

        $this->uploadProgress = 100;

        $this->uploadedLogoPath = $path;
        $this->logo = null;
        $this->dispatch('reloadPage');
        $this->dispatch('alert', type: 'success', text: 'System Logo has been updated successfully!');
    } catch (\Exception $e) {
        $this->uploadProgress = 0;
        $this->dispatch('alert', type: 'danger', text: 'Error uploading System Logo!');
    }
}
public function uploadHeaderImage()
{
    $this->validate([
        'headerImage' => 'required|image|max:2048|mimes:png,jpg,jpeg',
    ]);

    try {
        $filename = 'header_' . Str::uuid() . '.' . $this->headerImage->getClientOriginalExtension();
        $path = $this->headerImage->storeAs('logos', $filename, 'public');

        $settings = Setting::first() ?? new Setting();
        if ($settings->header_image) {
            Storage::disk('public')->delete($settings->header_image);
        }

        $settings->header_image = $path;
        $settings->save();

        $this->uploadedHeaderPath = $path;
        $this->headerImage = null;
        $this->dispatch('alert', type: 'success', text: 'Header image updated successfully!');
    } catch (\Exception $e) {
        $this->dispatch('alert', type: 'error', text: 'Error uploading header image!');
    }
}

public function uploadFooterImage()
{
    $this->validate([
        'footerImage' => 'required|image|max:2048|mimes:png,jpg,jpeg',
    ]);

    try {
        $filename = 'footer_' . Str::uuid() . '.' . $this->footerImage->getClientOriginalExtension();
        $path = $this->footerImage->storeAs('documents', $filename, 'public');

        $settings = Setting::first() ?? new Setting();
        if ($settings->footer_image) {
            Storage::disk('public')->delete($settings->footer_image);
        }

        $settings->footer_image = $path;
        $settings->save();

        $this->uploadedFooterPath = $path;
        $this->footerImage = null;
        $this->dispatch('alert', type: 'success', text: 'Footer image updated successfully!');
    } catch (\Exception $e) {
        $this->dispatch('alert', type: 'error', text: 'Error uploading footer image!');
    }
}

    public function saveSettings()
    {
        // Validate Inputs
        $validatedData = $this->validate([
            'system_name' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:255',
            'system_email' => 'required|email|max:255',
            'system_contact' => 'required|string',
            'default_theme' => 'required|string|in:maroon,blue,green,gold',
            'minimum_hours' => 'required|integer|min:1|max:24',
        ]);


        // Update database
        $settings = Setting::first() ?? new Setting();
        $settings->fill($validatedData);
        $settings->save();
        $this->dispatch('reloadPage');
        // Success Message
        $this->dispatch('alert', type: 'success', text: 'Settings have been updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}

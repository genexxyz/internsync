<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class SignaturePad extends Component
{
    use WithFileUploads;

    public $signature;
    public $currentSignature;
    public $uploadedSignature;
    public $method = 'draw';
    public function setMethod($value)
    {
        $this->method = $value;
        $this->reset(['signature', 'uploadedSignature']);
    }
    public function mount()
    {
        $user = Auth::user();
        if ($user->role === 'supervisor') {
            $this->currentSignature = $user->supervisor->signature_path;
        } else if ($user->role === 'instructor') {
            $this->currentSignature = $user->instructor->signature_path;
        }
    }
    
    public function updatedUploadedSignature()
    {
        $this->validate([
            'uploadedSignature' => 'required|image|max:1024|mimes:png,jpg,jpeg',
        ]);
    }

    public function saveSignature($signatureData = null)
    {
        if ($this->method === 'draw') {
            if (!$this->isValidSignatureData($signatureData)) {
                $this->dispatch('alert', 
                    type: 'error',
                    text: 'Please provide a valid signature.'
                );
                return;
            }
        }

        try {
            // Delete old signature if exists
            if ($this->currentSignature) {
                Storage::disk('public')->delete($this->currentSignature);
            }

            $user = Auth::user();
            $userRole = $user->role;
            
            // Get user details based on role
            if ($userRole === 'supervisor') {
                $firstName = $user->supervisor->first_name;
                $lastName = $user->supervisor->last_name;
            } else {
                $firstName = $user->instructor->first_name;
                $lastName = $user->instructor->last_name;
            }

            // Generate filename
            $filename = sprintf(
                '%s_%s_%s_%s_%s.png',
                $user->id,
                $userRole,
                strtolower($lastName),
                strtolower($firstName),
                Str::random(8)
            );

            $path = 'signatures/' . $filename;

            // Process and save signature based on method
            if ($this->method === 'draw') {
                $imageData = explode(',', $signatureData)[1];
                $decodedImage = base64_decode($imageData);
                Storage::disk('public')->put($path, $decodedImage);
            } else {
                Storage::disk('public')->putFileAs(
                    'signatures',
                    $this->uploadedSignature,
                    $filename
                );
            }

            // Update user signature based on role
            if ($userRole === 'supervisor') {
                $user->supervisor->update(['signature_path' => $path]);
            } else {
                $user->instructor->update(['signature_path' => $path]);
            }

            $this->currentSignature = $path;
            $this->reset(['uploadedSignature']);

            $this->dispatch('alert', 
                    type: 'success',
                    text: 'Signature saved successfully.'
                    
                );
                $this->dispatch('signature-saved');
    $this->dispatch('alert', type: 'success',
    text: 'Signature saved successfully.');

        } catch (\Exception $e) {
            
            $this->dispatch('alert', 
                    type: 'error',
                    text: 'Failed to save signature. Please try again.'
                );
        }
    }
    public function saveUploadedSignature()
    {
        try {
            $this->validate([
                'uploadedSignature' => 'required|image|mimes:png|max:1024',
            ]);
    
            // Create image from uploaded file
            $image = imagecreatefrompng($this->uploadedSignature->getRealPath());
            
            // Get image dimensions
            $width = imagesx($image);
            $height = imagesy($image);
            
            // Create new image with alpha channel
            $newImage = imagecreatetruecolor($width, $height);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transparent);
            
            // Copy and maintain transparency
            imagecopy($newImage, $image, 0, 0, 0, 0, $width, $height);
            
            // Find bounds of non-transparent pixels
            $bounds = [
                'left' => $width,
                'top' => $height,
                'right' => 0,
                'bottom' => 0
            ];
            
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $alpha = ((imagecolorat($newImage, $x, $y) >> 24) & 0x7F);
                    if ($alpha != 127) { // if not fully transparent
                        $bounds['left'] = min($bounds['left'], $x);
                        $bounds['top'] = min($bounds['top'], $y);
                        $bounds['right'] = max($bounds['right'], $x);
                        $bounds['bottom'] = max($bounds['bottom'], $y);
                    }
                }
            }
            
            // Add padding
            $padding = 20;
            $bounds['left'] = max(0, $bounds['left'] - $padding);
            $bounds['top'] = max(0, $bounds['top'] - $padding);
            $bounds['right'] = min($width - 1, $bounds['right'] + $padding);
            $bounds['bottom'] = min($height - 1, $bounds['bottom'] + $padding);
            
            // Create cropped image
            $croppedWidth = $bounds['right'] - $bounds['left'] + 1;
            $croppedHeight = $bounds['bottom'] - $bounds['top'] + 1;
            $cropped = imagecreatetruecolor($croppedWidth, $croppedHeight);
            imagesavealpha($cropped, true);
            imagefill($cropped, 0, 0, $transparent);
            
            imagecopy($cropped, $newImage, 0, 0, $bounds['left'], $bounds['top'], $croppedWidth, $croppedHeight);
            
            // Save to temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'signature');
            imagepng($cropped, $tempFile);
            
            // Process like a drawn signature
            $signatureData = base64_encode(file_get_contents($tempFile));
            
            // Clean up
            unlink($tempFile);
            imagedestroy($image);
            imagedestroy($newImage);
            imagedestroy($cropped);
            
            $this->saveSignature('data:image/png;base64,' . $signatureData);
            $this->dispatch('signature-saved');
    $this->dispatch('alert', type: 'success',
    text: 'Signature saved successfully.');
            
        } catch (\Exception $e) {
            
            $this->dispatch('alert', 
                    type: 'error',
                    text: 'Failed to save signature. Please try again.'
                );
        }
    }
    protected function isValidSignatureData($data)
    {
        if (empty($data)) return false;
        if ($data === 'data:,') return false;
        if (!str_starts_with($data, 'data:image/png;base64,')) return false;
        
        return true;
    }

    public function render()
    {
        return view('livewire.components.signature-pad');
    }
}
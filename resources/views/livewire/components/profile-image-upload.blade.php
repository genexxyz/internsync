<div class="flex items-center space-x-6 my-3">
    <div class="relative">
        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100">
            @if($currentImage)
                <img src="{{ Storage::url($currentImage) }}" 
                     alt="Profile picture" 
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                    <i class="fas fa-user text-gray-400 text-3xl"></i>
                </div>
            @endif
        </div>
        
        <label for="profile-upload" 
               class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-lg cursor-pointer hover:bg-gray-50">
            <i class="fas fa-camera text-gray-600"></i>
            <input type="file"
                   wire:model="profileImage"
                   id="profile-upload"
                   class="hidden"
                   accept="image/*">
        </label>
    </div>

    <div class="space-y-2">
        <div class="text-lg font-medium text-gray-900">
            {{ Auth::user()->name }}
        </div>
        <div class="text-sm text-gray-500">
            {{ ucfirst($userRole) }}
        </div>
        @if($currentImage)
            <button type="button"
                    wire:click="deleteImage"
                    wire:confirm="Are you sure you want to delete your profile picture?"
                    class="text-sm text-red-600 hover:text-red-800">
                <i class="fas fa-trash-alt mr-1"></i>
                Remove photo
            </button>
        @endif
    </div>

    <div wire:loading wire:target="profileImage" class="mt-2">
        <div class="text-sm text-gray-500">
            <i class="fas fa-spinner fa-spin mr-1"></i>
            Uploading...
        </div>
    </div>

    @error('profileImage')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>
<div class="m-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">System Settings</h2>
        
        <!-- Form Start -->
        <form wire:submit.prevent="saveSettings" enctype="multipart/form-data" class="space-y-6">
            <!-- Logo Upload Section -->
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">System Logo</h3>
                    <div class="text-sm text-gray-500">Recommended size: 200x200px</div>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Current/Preview Logo -->
                    <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-lg overflow-hidden">
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($uploadedLogoPath)
                            <img src="{{ Storage::url($uploadedLogoPath) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fa fa-image text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Upload Controls -->
                    <div class="flex-grow">
                        <label class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <i class="fa fa-upload mr-2 text-gray-500"></i>
                            <span class="text-sm text-gray-700">Choose Logo</span>
                            <input type="file" wire:model="logo" accept=".svg,.png,.jpg,.jpeg" class="hidden">
                        </label>

                        @if($logo)
                            <button disabled type="button" wire:click="uploadLogo" 
                                class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fa fa-check mr-2"></i>
                                Confirm Upload
                            </button>
                        @endif
                    </div>
                </div>

                @if($uploadProgress > 0)
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" 
                                style="width: {{ $uploadProgress }}%"></div>
                        </div>
                        <span class="text-sm text-gray-500 mt-1">Uploading: {{ $uploadProgress }}%</span>
                    </div>
                @endif
            </div>

            <!-- School Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- System Name -->
                <div>
                    
                    <x-text-input icon="fa fa-font" id="system_name" class="block w-full" type="text"
                        wire:model="system_name" name="system_name" placeholder="System Name" disabled/>
                    <x-input-error :messages="$errors->get('system_name')" class="mt-2" />
                </div>

                <!-- School Name -->
                <div>
                    
                    <x-text-input icon="fa fa-building" id="school_name" class="block w-full" type="text"
                        wire:model="school_name" name="school_name" placeholder="School Name" />
                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                </div>
            </div>

            <!-- Address -->
            <div>
                
                <x-text-input icon="fa fa-location-dot" id="school_address" class="block w-full" type="text"
                    wire:model="school_address" name="school_address" placeholder="School Address" />
                <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    
                    <x-text-input icon="fa fa-envelope" id="system_email" class="block w-full" type="email"
                        wire:model="system_email" name="system_email" placeholder="School Email" />
                    <x-input-error :messages="$errors->get('system_email')" class="mt-2" />
                </div>

                <div>
                    
                    <x-text-input icon="fa fa-phone" id="system_contact" class="block w-full" type="text"
                        wire:model="system_contact" name="system_contact" placeholder="School Contact Number" />
                    <x-input-error :messages="$errors->get('system_contact')" class="mt-2" />
                </div>
            </div>

            <!-- Theme Selection -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">System Theme</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(['maroon' => 'Maroon', 'blue' => 'Blue', 'green' => 'Green', 'gold' => 'Gold'] as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" wire:model="default_theme" name="default_theme" 
                                value="{{ $value }}" class="sr-only peer">
                            <div class="p-4 text-center rounded-lg border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all">
                                <div class="w-8 h-8 mx-auto rounded-full mb-2" 
                                    style="background-color: {{ $value }};"></div>
                                <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('default_theme')" class="mt-2" />
            </div>

            <input type="hidden" wire:model="updated_by" name="updated_by" value="{{ Auth::user()->email }}">

            <!-- Form Buttons -->
            <div class="flex justify-end pt-6">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fa fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
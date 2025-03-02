<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        {{ $acceptance_letter && $acceptance_letter->is_generated ? 'Upload Signed Letter' : 'Generate Acceptance Letter' }}
    </h3>

    @if(!$acceptance_letter || !$acceptance_letter->is_generated)
    <form wire:submit.prevent="generatePDF" class="space-y-6">
        <!-- Company Name -->
        <div>
            <x-text-input icon="fa fa-building" id="company_name" class="block mt-1 w-full" type="text"
                        wire:model="company_name" name="company_name" :value="old('company_name')" required autofocus
                        placeholder="Company Name" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <!-- Department Name -->
        <div>
            <x-text-input icon="fa fa-building" id="department_name" class="block mt-1 w-full" type="text"
                        wire:model="department_name" name="department_name" :value="old('department_name')" autofocus
                        placeholder="Department(If Applicable)" />
                    <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
        </div>

        <!-- Contact Person Name -->
        <div>
            <x-text-input icon="fa fa-user-tie" id="name" class="block mt-1 w-full" type="text"
                        wire:model="name" name="name" :value="old('name')" required autofocus
                        placeholder="Contact Person Name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Position -->
        <div>
            <x-text-input icon="fa fa-id-card" id="position" class="block mt-1 w-full" type="text"
                        wire:model="position" name="position" :value="old('position')" required autofocus
                        placeholder="Position" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
        </div>

        <!-- Address -->
        <div>
            <x-text-input icon="fa fa-location-dot" id="address" class="block mt-1 w-full" type="text"
                        wire:model="address" name="address" :value="old('address')" required autofocus
                        placeholder="Address" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Contact Number -->
        <div>
            <x-text-input icon="fa fa-phone" id="contact" class="block mt-1 w-full" type="text"
                        wire:model="contact" name="contact" :value="old('contact')" required autofocus
                        placeholder="Contact Number" />
                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Generate Acceptance Letter
            </button>
        </div>
    </form>
    @else

    <form wire:submit="uploadSignedLetter" class="space-y-4">
        <div>
            <label for="signed_letter" class="block text-sm font-medium text-gray-700">Upload Signed Letter (PDF)</label>
            <input type="file" wire:model="signed_letter" id="signed_letter" 
                class="mt-1 block w-full" accept=".pdf">
            <div wire:loading wire:target="signed_letter" class="text-sm text-gray-500 mt-1">
                Uploading...
            </div>
            @error('signed_letter') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
            Upload Signed Letter
        </button>
    </form>
@endif
</div>
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        {{ $acceptance_letter && $acceptance_letter->is_generated ? 'Acceptance Letter Status' : 'Generate Acceptance Letter' }}
    </h3>

    @if(!$acceptance_letter || !$acceptance_letter->is_generated)
    <!-- Keep existing form for generating letter -->
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

        <!-- Supervisor Name -->
        <div>
            <x-text-input icon="fa fa-user-tie" id="supervisor_name" class="block mt-1 w-full" type="text"
                        wire:model="supervisor_name" name="supervisor_name" :value="old('supervisor_name')" required autofocus
                        placeholder="Supervisor Name" />
                    <x-input-error :messages="$errors->get('supervisor_name')" class="mt-2" />
        </div>

        

        <!-- Address -->
        <div>
            <x-text-input icon="fa fa-location-dot" id="address" class="block mt-1 w-full" type="text"
                        wire:model="address" name="address" :value="old('address')" required autofocus
                        placeholder="Company Address" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Contact Number -->
        <div>
            <x-text-input icon="fa fa-phone" id="contact" class="block mt-1 w-full" type="number"
                        wire:model="contact" name="contact" :value="old('contact')" required autofocus
                        placeholder="Contact Number" />
                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-text-input icon="fa fa-id-card" id="email" class="block mt-1 w-full" type="email"
                        wire:model="email" name="email" :value="old('email')" required
                        placeholder="Email (If Applicable)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
    <!-- Replace upload form with status message -->
    <div class="space-y-4">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Your acceptance letter has been generated and is waiting for supervisor's signature.
                        You will be notified once the supervisor has signed your letter.
                    </p>
                </div>
            </div>
        </div>

        <div class="border rounded-lg p-4">
            <h4 class="font-medium text-gray-700 mb-2">Submission Details</h4>
            <dl class="grid grid-cols-1 gap-2 text-sm">
                <div class="flex justify-between py-1 border-b">
                    <dt class="text-gray-500">Submitted to:</dt>
                    <dd class="text-gray-900">{{ $acceptance_letter->supervisor_name }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b">
                    <dt class="text-gray-500">Company:</dt>
                    <dd class="text-gray-900">{{ $acceptance_letter->company_name }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b">
                    <dt class="text-gray-500">Department:</dt>
                    <dd class="text-gray-900">{{ $acceptance_letter->department_name ?: 'N/A' }}</dd>
                </div>
                <div class="flex justify-between py-1 border-b">
                    <dt class="text-gray-500">Status:</dt>
                    <dd class="text-gray-900">
                        @if($acceptance_letter->signed_path)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Signed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Awaiting Signature
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        @if(!$acceptance_letter->signed_path)
            <p class="text-sm text-gray-500 italic mt-4">
                Please wait for your supervisor to review and sign your acceptance letter. 
                This may take some time depending on their availability.
            </p>
        @endif
    </div>
@endif
</div>
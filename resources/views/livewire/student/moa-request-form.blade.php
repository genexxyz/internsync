<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center pb-4 border-b mb-6">
        <h3 class="text-xl font-semibold text-gray-900">Request Memorandum of Agreement</h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    @if($existingRequest)
        <div class="mb-6 p-4 rounded-lg {{ $this->getStatusColor() }}">
            <div class="flex items-center">
                <i class="fas {{ $this->getStatusIcon() }} mr-3"></i>
                <div>
                    <h4 class="font-medium">MOA Request Status: {{ ucfirst($existingRequest->status) }}</h4>
                    <p class="text-sm mt-1">Requested on: {{ $existingRequest->requested_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(!$existingRequest)
        <form wire:submit="submit" class="space-y-6">
            <!-- Company Details -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Company Name</h4>
                        <p class="mt-1">{{ $company->company_name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Address</h4>
                        <p class="mt-1">{{ $company->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Company Number -->
            <div>
                <x-text-input 
                    icon="fa fa-building" 
                    id="companyNumber" 
                    type="text"
                    wire:model="companyNumber" 
                    name="companyNumber" 
                    :value="old('companyNumber')" 
                    required
                    placeholder="Company Number" />
                <x-input-error :messages="$errors->get('companyNumber')" class="mt-2" />
            </div>

            <!-- Officer Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-text-input 
                        icon="fa fa-user" 
                        id="officerName" 
                        type="text"
                        wire:model="officerName" 
                        name="officerName" 
                        :value="old('officerName')" 
                        required
                        placeholder="Officer Name" />
                    <x-input-error :messages="$errors->get('officerName')" class="mt-2" />
                </div>
                <div>
                    <x-text-input 
                        icon="fa fa-briefcase" 
                        id="officerPosition" 
                        type="text"
                        wire:model="officerPosition" 
                        name="officerPosition" 
                        :value="old('officerPosition')" 
                        required
                        placeholder="Officer Position" />
                    <x-input-error :messages="$errors->get('officerPosition')" class="mt-2" />
                </div>
            </div>

            <!-- Witness Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-text-input 
                        icon="fa fa-user" 
                        id="witnessName" 
                        type="text"
                        wire:model="witnessName" 
                        name="witnessName" 
                        :value="old('witnessName')" 
                        required
                        placeholder="Witness Name" />
                    <x-input-error :messages="$errors->get('witnessName')" class="mt-2" />
                </div>
                <div>
                    <x-text-input 
                        icon="fa fa-briefcase" 
                        id="witnessPosition" 
                        type="text"
                        wire:model="witnessPosition" 
                        name="witnessPosition" 
                        :value="old('witnessPosition')" 
                        required
                        placeholder="Witness Position" />
                    <x-input-error :messages="$errors->get('witnessPosition')" class="mt-2" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t">
                <x-primary-button 
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit">
                    <span wire:loading.remove wire:target="submit">Submit Request</span>
                    <span wire:loading wire:target="submit">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Submitting...
                    </span>
                </x-primary-button>
            </div>
        </form>
    @endif
</div>
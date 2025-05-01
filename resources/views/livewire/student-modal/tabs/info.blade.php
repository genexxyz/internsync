<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-gray-800">Personal Information</h4>
            @if($canEdit)
                <button wire:click="toggleEdit" class="p-2 hover:bg-gray-100 rounded-full">
                    <i class="fa {{ $isEditing ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                </button>
            @endif
        </div>

        @if(!$isEditing)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Full Name</label>
                    <p class="text-gray-800">{{ $student->name() }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Email</label>
                    <p class="text-gray-800">{{ $student->user->email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Contact</label>
                    <p class="text-gray-800">{{ $student->contact }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Address</label>
                    <p class="text-gray-800">{{ $student->address }}</p>
                </div>
            </div>
        @else
            <form wire:submit="saveChanges" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" wire:model="editableData.first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                    <input type="text" wire:model="editableData.middle_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.middle_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" wire:model="editableData.last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Suffix</label>
                    <input type="text" wire:model="editableData.suffix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.suffix') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact</label>
                    <input type="text" wire:model="editableData.contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" wire:model="editableData.address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('editableData.address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                        Save Changes
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-semibold text-gray-800">Deployment Information</h4>
            @if($canEdit && $student->deployment && $student->deployment->status === 'pending')
                <button wire:click="toggleDeploymentEdit" class="p-2 hover:bg-gray-100 rounded-full">
                    <i class="fa {{ $isEditingDeployment ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                </button>
            @endif
        </div>

        @if($student->deployment)
            @if(!$isEditingDeployment)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Company</label>
                        <p class="text-gray-800">{{ $student->deployment->company->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Department</label>
                        <p class="text-gray-800">{{ $student->deployment->department->department_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Type</label>
                        <p class="text-gray-800 capitalize">
                            {{ $student->deployment->student_type }}
                            @if($student->deployment->student_type === 'special')
                                ({{ $student->deployment->custom_hours }} hours)
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span @class([
                            'px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-yellow-100 text-yellow-600' => $student->deployment->status === 'pending',
                            'bg-blue-100 text-blue-600' => $student->deployment->status === 'ongoing',
                            'bg-green-100 text-green-600' => $student->deployment->status === 'completed'
                        ])>
                            {{ ucfirst($student->deployment->status) }}
                        </span>
                    </div>
                    @if($student->section->course->allows_custom_hours && $student->deployment->student_type === 'special')
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-500">Special Permit</label>
                            @if($student->deployment->permit_path )
                                <div class="mt-1">
                                    <a href="{{ Storage::url($student->deployment->permit_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center text-primary hover:text-primary-dark">
                                        <i class="fa fa-file-pdf mr-2"></i>
                                        View Permit
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No permit uploaded</p>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <form wire:submit.prevent="saveDeployment" class="space-y-4">
                    @if($student->section->course->allows_custom_hours)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Student Type</label>
                                <select wire:model.live="deploymentData.student_type" 
                                        class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="regular">Regular</option>
                                    <option value="special">Special</option>
                                </select>
                                @error('deploymentData.student_type')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($deploymentData['student_type'] === 'special')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Custom Hours</label>
                                    <input type="number" 
                                           wire:model="deploymentData.custom_hours"
                                           class="mt-1 block w-full rounded-md border-gray-300"
                                           min="1">
                                    @error('deploymentData.custom_hours')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Special Permit</label>
                                    <div class="mt-1 flex items-center gap-4">
                                        <input type="file" 
                                               wire:model="permitFile"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-dark"
                                               accept=".pdf,.jpg,.jpeg,.png">
                                        
                                        @if($student->deployment->permit_path)
                                            <a href="{{ Storage::url($student->deployment->permit_path) }}" 
                                               target="_blank"
                                               class="text-primary hover:text-primary-dark">
                                                <i class="fa fa-file-pdf mr-1"></i>
                                                Current Permit
                                            </a>
                                        @endif
                                    </div>
                                    @error('permitFile')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                wire:click="toggleDeploymentEdit"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark">
                            Save Changes
                        </button>
                    </div>
                </form>
            @endif
        @else
            <p class="text-gray-500 italic">Not yet deployed</p>
        @endif
    </div>
</div>
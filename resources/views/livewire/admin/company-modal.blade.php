<div class="bg-white rounded-lg w-full max-w-7xl shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Company Profile</h2>
            <div class="flex items-center gap-2">
                @if($isEditing)
                    <button 
                        wire:click="deleteCompany"
                        wire:confirm="Are you sure you want to delete this company? This action cannot be undone."
                        class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"
                        title="Delete Company"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
                <button wire:click="$dispatch('closeModal')"
                    class="p-2 rounded-full hover:bg-gray-200 transition-colors">
                    <i class="fa fa-xmark text-gray-500 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Company Information -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Company Information</h3>
                @if(Auth::user()->role === 'admin')
                <button wire:click="toggleEdit" class="p-2 hover:bg-gray-100 rounded-full">
                    <i class="fa {{ $isEditing ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                </button>
                @endif
                
            </div>

            @if($isEditing)
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" wire:model="editableData.company_name" 
                        class="w-full border-gray-300 rounded-lg @error('editableData.company_name') border-red-500 @enderror">
                    @error('editableData.company_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" wire:model="editableData.address" 
                        class="w-full border-gray-300 rounded-lg @error('editableData.address') border-red-500 @enderror">
                    @error('editableData.address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                    <input type="text" wire:model="editableData.contact_person" 
                        class="w-full border-gray-300 rounded-lg @error('editableData.contact_person') border-red-500 @enderror">
                    @error('editableData.contact_person')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" wire:model="editableData.contact" 
                        class="w-full border-gray-300 rounded-lg @error('editableData.contact') border-red-500 @enderror">
                    @error('editableData.contact')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
                <div class="flex justify-end mt-4">
                    <button wire:click="saveChanges"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <i class="fa fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Company Name</p>
                        <p class="font-medium">{{ $company->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">{{ $company->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Contact Person</p>
                        <p class="font-medium">{{ $company->contact_person }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Contact Number</p>
                        <p class="font-medium">{{ $company->contact }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Departments -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Departments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($company->department as $department)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $department->department_name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $department->deployments->count() }} Active Interns
                                </p>
                            </div>
                            @if($isEditing)
                                <button 
                                    wire:click="deleteDepartment({{ $department->id }}, {{$company->id}})"
                                    wire:confirm="Are you sure you want to delete this department? This action cannot be undone."
                                    class="p-1.5 rounded hover:bg-red-100 text-red-600 transition-colors"
                                    title="Delete Department"
                                >
                                    <i class="fa fa-trash text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full">No departments found</p>
                @endforelse
            </div>
        </div>

        <!-- Supervisors -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Supervisors</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($company->supervisor as $supervisor)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">
                                    {{ $supervisor->getFullNameAttribute() }}
                                </h4>
                                <p class="text-sm text-gray-500">{{ $supervisor->position }}</p>
                                <p class="text-sm text-gray-500">{{ $supervisor->department->department_name }}</p>
                            </div>
                            @if($supervisor->user->is_verified)
                                <i class="fas fa-check-circle text-green-500"></i>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full">No supervisors found</p>
                @endforelse
            </div>
        </div>

        <!-- Deployed Students -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Deployed Students</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Supervisor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($company->deployments as $deployment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">
                                        {{ $deployment->student->first_name }}
                                        {{ $deployment->student->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $deployment->student->student_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $deployment->department->department_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $deployment->supervisor?->getFullNameAttribute() ?? 'Not Assigned' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $deployment->status === 'ONGOING' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $deployment->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No deployed students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
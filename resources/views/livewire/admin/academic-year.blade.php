<div class="">
    <x-breadcrumbs :breadcrumbs="[['url' => route('admin.academic-year'), 'label' => 'Academic year']]" />
    <div class="bg-white rounded-lg shadow-sm mt-6">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Academic Year Management</h2>
                <button 
                    wire:click="$toggle('showCreateForm')"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add Academic Year
                </button>
            </div>

            <!-- Create Form -->
            @if($showCreateForm)
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Academic Year</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                            <input type="text" 
                                wire:model="newAcademic.academic_year"
                                class="w-full border-gray-300 rounded-lg text-sm"
                                placeholder="YYYY-YYYY"
                            >
                            @error('newAcademic.academic_year')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                            <select wire:model="newAcademic.semester"
                                class="w-full border-gray-300 rounded-lg text-sm">
                                <option value="">Select Semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                            @error('newAcademic.semester')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" 
                                wire:model="newAcademic.start_date"
                                class="w-full border-gray-300 rounded-lg text-sm"
                            >
                            @error('newAcademic.start_date')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" 
                                wire:model="newAcademic.end_date"
                                class="w-full border-gray-300 rounded-lg text-sm"
                            >
                            @error('newAcademic.end_date')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 gap-3">
                        <button 
                            wire:click="$set('showCreateForm', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="createAcademic"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark"
                        >
                            Create Academic Year
                        </button>
                    </div>
                </div>
            @endif

            <!-- Academic Years Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Academic Year
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semester
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($academics as $academic)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isEditing && $editingId === $academic->id)
                                        <input type="text" 
                                            wire:model="editableData.academic_year"
                                            class="w-full border-gray-300 rounded-lg text-sm"
                                            placeholder="YYYY-YYYY"
                                        >
                                    @else
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $academic->academic_year }}
                                            </span>
                                            @if($academic->ay_default)
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Default
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isEditing && $editingId === $academic->id)
                                        <select wire:model="editableData.semester"
                                            class="w-full border-gray-300 rounded-lg text-sm">
                                            <option value="">Select Semester</option>
                                            <option value="1st Semester">1st Semester</option>
                                            <option value="2nd Semester">2nd Semester</option>
                                            <option value="Summer">Summer</option>
                                        </select>
                                    @else
                                        <span class="text-sm text-gray-900">{{ $academic->semester }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isEditing && $editingId === $academic->id)
                                        <div class="flex gap-2">
                                            <input type="date" 
                                                wire:model="editableData.start_date"
                                                class="w-full border-gray-300 rounded-lg text-sm"
                                            >
                                            <input type="date" 
                                                wire:model="editableData.end_date"
                                                class="w-full border-gray-300 rounded-lg text-sm"
                                            >
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-900">
                                            {{ $academic->start_date?->format('M d, Y') }} - 
                                            {{ $academic->end_date?->format('M d, Y') }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($isEditing && $editingId === $academic->id)
                                            <button wire:click="saveChanges"
                                                class="p-1 hover:bg-green-100 rounded-full text-green-600 transition-colors">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="cancelEditing"
                                                class="p-1 hover:bg-red-100 rounded-full text-red-600 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                        <a href="{{ route('admin.academic-year.show', $academic->id) }}"
                                            class="p-1 hover:bg-blue-100 rounded-full text-blue-600 transition-colors"
                                            title="Manage Academic Year">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                            @unless($academic->ay_default)
                                                <button wire:click="setDefault({{ $academic->id }})"
                                                    class="p-1 hover:bg-blue-100 rounded-full text-blue-600 transition-colors"
                                                    title="Set as Default">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endunless
                                            <button wire:click="startEditing({{ $academic->id }})"
                                                class="p-1 hover:bg-gray-100 rounded-full text-gray-600 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $academic->id }})"
                                                class="p-1 hover:bg-red-100 rounded-full text-red-600 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No academic years found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $academics->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete this academic year? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button wire:click="deleteAcademic"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
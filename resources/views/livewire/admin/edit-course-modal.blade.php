<div class="relative">
    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl mx-auto">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold">
                Edit Course
            </h3>
            

            
            
            <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <!-- Course Details -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Code</label>
                        <input 
                            type="text" 
                            wire:model="courseCode"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                        >
                        @error('courseCode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Name</label>
                        <input 
                            type="text" 
                            wire:model="courseName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                        >
                        @error('courseName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Program Head Assignment -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Program Head</label>
                        
                        @if($currentInstructor && !$instructorId)
                            <div class="flex items-center justify-between bg-yellow-50 p-3 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-user-tie text-yellow-600"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-900">Current Program Head</p>
                                        <p class="text-sm text-yellow-700">{{ $currentInstructor->fullName }}</p>
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="$set('instructorId', null)"
                                    class="text-yellow-600 hover:text-yellow-700"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif

                        <select 
                            wire:model="instructorId"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                        >
                            <option value="">Select Program Head</option>
                            @foreach($availableInstructors as $instructor)
                                <option value="{{ $instructor['id'] }}">
                                    {{ $instructor['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructorId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <!-- Delete Button -->
                    <button
                        type="button"
                        x-data
                        x-on:click="if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) { $wire.deleteCourse() }"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        <i class="fas fa-trash-alt mr-2"></i>
                        Delete Course
                    </button>
                
                    <!-- Save/Cancel Buttons -->
                    <div class="flex gap-3">
                        <button
                            type="button"
                            wire:click="$dispatch('closeModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
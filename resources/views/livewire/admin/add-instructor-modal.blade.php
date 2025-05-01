<div class="relative">
    <div class="relative bg-white rounded-lg shadow-xl max-w-3xl mx-auto">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold">
                Add New Instructor
            </h3>
            <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructor ID</label>
                        <input type="text" wire:model="instructorId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('instructorId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="firstName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('firstName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" wire:model="middleName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('middleName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="lastName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('lastName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suffix</label>
                        <input type="text" wire:model="suffix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('suffix') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" wire:model="contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('contact') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Program Head Section -->
                <div class="space-y-4">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" wire:model.live="isProgramHead" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <label class="ml-2 text-sm font-medium text-gray-700">Assign as Program Head</label>
                    </div>
                
                    @if($isProgramHead)
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Courses</label>
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="courseSearch" 
                    placeholder="Search courses..."
                    class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto custom-scrollbar">
            @if(count($this->filteredCourses) === 0)
                <div class="col-span-2 text-center py-3 text-gray-500 text-sm">
                    No courses found matching "{{ $courseSearch }}"
                </div>
            @else
                @foreach($this->filteredCourses as $course)
                <label class="relative flex items-center p-2 bg-white rounded-md border border-gray-200 hover:border-primary transition-colors cursor-pointer">
                    <input 
                        type="checkbox" 
                        value="{{ $course->id }}"
                        wire:model.live="courseCodes"
                        class="rounded border-gray-300 text-primary focus:ring-primary"
                    >
                    <div class="ml-2 truncate">
                        <span class="block text-sm font-medium text-gray-900">{{ $course->course_code }}</span>
                        <span class="block text-xs text-gray-500 truncate" title="{{ $course->course_name }}">
                            {{ $course->course_name }}
                        </span>
                    </div>
                </label>
                @endforeach
            @endif
        </div>
        @error('courseCodes') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
    </div>
@endif
                </div>

                <!-- Section Handling -->
<div class="bg-gray-50 rounded-lg p-4">
    <div class="space-y-3">
        <div class="flex justify-between items-center">
            <label class="text-sm font-medium text-gray-700">
                @if($isProgramHead)
                    Optional Handling Sections
                @else
                    Handling Sections
                @endif
                @if($isProgramHead && empty($courseCodes))
                    <span class="text-sm text-gray-500 ml-2">(Please select courses first)</span>
                @endif
            </label>
            {{-- @if(!empty($availableSections))
                <span class="text-xs text-gray-500">{{ count($availableSections) }} sections available</span>
            @endif --}}
        </div>

        @if($isProgramHead && empty($courseCodes))
            <div class="text-center py-4 bg-gray-100 rounded-md">
                <span class="text-sm text-gray-500">Select courses first to view available sections</span>
            </div>
        @else
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="sectionSearch" 
                    placeholder="Search sections..."
                    class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto custom-scrollbar">
                @forelse($this->availableSections as $section)
                    <label class="relative flex items-center p-2 bg-white rounded-md border border-gray-200 hover:border-primary transition-colors cursor-pointer">
                        <input 
                            type="checkbox"
                            value="{{ $section['id'] }}"
                            wire:model="handlingSections"
                            class="rounded border-gray-300 text-primary focus:ring-primary"
                        >
                        <div class="ml-2 truncate">
                            <span class="block text-sm font-medium text-gray-900">{{ $section['name'] }}</span>
                        </div>
                    </label>
                @empty
                    <div class="col-span-full text-center py-3 text-gray-500 text-sm">
                        {{ empty($sectionSearch) ? 'No sections available' : 'No sections found matching "' . $sectionSearch . '"' }}
                    </div>
                @endforelse
            </div>
        @endif
    </div>
    @unless($isProgramHead)
        @error('handlingSections') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
    @endunless
</div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                        
                        


                        <span wire:loading.remove><i class="fas fa-save mr-2"></i>Save Instructor</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Processing...
            </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
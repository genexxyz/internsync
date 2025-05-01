<div class="relative">
    <div class="relative bg-white rounded-lg shadow-xl max-w-3xl mx-auto">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold">
                Add New Student
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
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" wire:model="studentId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('studentId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                        @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Course and Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course</label>
                        <select wire:model.live="courseId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select Course</option>
                            @foreach($availableCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_code }} - {{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        @error('courseId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Section</label>
                        <select wire:model="sectionId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" @if(!$courseId) disabled @endif>
                            <option value="">Select Section</option>
                            @foreach($availableSections as $section)
                                <option value="{{ $section->id }}">{{ $section->year_level }}{{ $section->class_section }}</option>
                            @endforeach
                        </select>
                        @error('sectionId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student Type</label>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        wire:model="type" 
                                        value="regular" 
                                        class="form-radio text-primary focus:ring-primary"
                                    >
                                    <span class="ml-2">Regular @if ($courseId && $selectedCourse?->allows_custom_hours)
                                        <span class="text-xs text-gray-500">({{ $selectedCourse->required_hours }} hours)</span>
                                    
                                    @endif</span>
                                </label>
                                <label class="inline-flex items-center {{ !$courseId || !$selectedCourse?->allows_custom_hours ? 'opacity-50' : '' }}">
                                    <input 
                                        type="radio" 
                                        wire:model="type" 
                                        value="special" 
                                        class="form-radio text-primary focus:ring-primary"
                                        @if(!$courseId || !$selectedCourse?->allows_custom_hours) disabled @endif
                                    >
                                    <span class="ml-2">Special @if ($courseId && $selectedCourse?->allows_custom_hours)
                                        <span class="text-xs text-gray-500">({{ $selectedCourse->custom_hours }} hours)</span>
                                    
                                    @endif</span>
                                </label>
                            </div>
                            
                            @if($courseId && !$selectedCourse?->allows_custom_hours)
                                <p class="text-sm text-yellow-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    This course does not allow special type students
                                </p>
                            @elseif(!$courseId)
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Select a course to see available student types
                                </p>
                            @endif
                        </div>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-md shadow-sm transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
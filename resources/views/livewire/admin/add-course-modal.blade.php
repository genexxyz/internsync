<div class="bg-white rounded-lg shadow-xl">
    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Add New Course</h2>
            <button 
                wire:click="$dispatch('closeModal')"
                class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200 focus:outline-none"
            >
                <i class="fa fa-xmark text-gray-500 text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Modal Content -->
    <form wire:submit.prevent="saveCourse" class="space-y-6">
    <div class="px-6 py-4 space-y-6">
        
            <!-- Course Information -->
            <div class="space-y-4">
                <div>
                    <x-text-input 
                        icon="fa fa-book" 
                        id="course_name" 
                        class="block w-full" 
                        type="text"
                        wire:model="course_name" 
                        placeholder="Course Name" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('course_name')" class="mt-1" />
                </div>

                <div>
                    <x-text-input 
                        icon="fa fa-code" 
                        id="course_code" 
                        class="block w-full" 
                        type="text"
                        wire:model="course_code" 
                        placeholder="Course Code" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('course_code')" class="mt-1" />
                </div>
            </div>

            <!-- Hours Configuration -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Hours Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-text-input 
                            icon="fa fa-clock" 
                            id="required_hours" 
                            class="block w-full" 
                            type="number"
                            wire:model="required_hours" 
                            placeholder="Required Hours" 
                            required 
                        />
                        <x-input-error :messages="$errors->get('required_hours')" class="mt-1" />
                    </div>

                    <div>
                        <x-text-input 
                            icon="fa fa-clock" 
                            id="custom_hours" 
                            class="block w-full" 
                            type="number"
                            wire:model="custom_hours" 
                            placeholder="Custom Hours" 
                            required 
                        />
                        <x-input-error :messages="$errors->get('custom_hours')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Academic Year Selection -->
            <div>
                <x-select-input 
                    wire:model="academic_year" 
                    name="academic_year" 
                    icon="fa fa-calendar" 
                    :options="$academicYear->mapWithKeys(fn($year) => [
                        $year->id => $year->academic_year . ' (Semester ' . $year->semester . ')'
                    ])->toArray()" 
                    :selected="old('academic_year')" 
                    placeholder="A.Y. & Semester" 
                />
                <x-input-error :messages="$errors->get('academic_year')" class="mt-1" />
            </div>
            <!-- Modal Footer -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
        <div class="flex justify-end gap-3">
            <button 
                wire:click="$dispatch('closeModal')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Cancel
            </button>
            <button 
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Create Course
            </button>
        </div>
    </div>
        </form>
    </div>

    
</div>
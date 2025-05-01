<div class="p-6">
    <div class="space-y-6">
        <!-- Search Bar -->
        <div class="flex items-center justify-between gap-4">
            <div class="relative w-full lg:w-1/3">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa fa-search text-gray-400"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search courses..." 
                />
            </div>
            <div class="flex items-center gap-2">
                <button 
            onclick="Livewire.dispatch('openModal', { component: 'admin.import-course-section-modal' })"
            class="inline-flex items-center px-4 py-2.5 rounded-lg bg-primary text-white hover:bg-accent transition-colors gap-2 shadow-sm"
        >
            <i class="fas fa-file-import"></i>
            Import Courses
        </button>
            </div>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                    <!-- Card Header -->
                    <div class="bg-secondary text-white rounded-t-xl p-4">
                        <div class="text-center space-y-1">
                            @if($editingCourse === $course->id)
    <div class="space-y-2">
        <input 
            type="text" 
            wire:model="editableData.course_code"
            class="w-full text-center bg-white/10 border-white/20 rounded text-white placeholder-white/50"
            placeholder="Course Code"
        >
        <input 
            type="text" 
            wire:model="editableData.course_name"
            class="w-full text-center bg-white/10 border-white/20 rounded text-white placeholder-white/50"
            placeholder="Course Name"
        >
        <div class="flex items-center justify-center gap-2 mt-2">
            <button 
                wire:click="saveCourseChanges"
                class="p-1 hover:bg-white/20 rounded transition-colors"
                title="Save Changes"
            >
                <i class="fas fa-check text-white"></i>
            </button>
            <button 
                wire:click="cancelEditing"
                class="p-1 hover:bg-white/20 rounded transition-colors"
                title="Cancel"
            >
                <i class="fas fa-times text-white"></i>
            </button>
            <button 
                wire:click="deleteCourse({{ $course->id }})"
                wire:confirm="Are you sure you want to delete this course? This action cannot be undone."
                class="p-1 hover:bg-red-500/20 rounded transition-colors"
                title="Delete Course"
            >
                <i class="fas fa-trash text-red-300 hover:text-red-400"></i>
            </button>
        </div>
    </div>
@else
                                <div class="flex items-center justify-center gap-2">
                                    <div class="flex-1 text-left">
                                        <h3 class="text-lg font-bold tracking-wide">{{ $course->course_code }}</h3>
                                        <p class="text-sm text-white/90">{{ $course->course_name }}</p>
                                    </div>
                                    <button 
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'admin.edit-course-modal', 
                                        arguments: { course: {{ $course->id }} } 
                                    })"
                                        class="p-1.5 hover:bg-white/20 rounded transition-colors"
                                    >
                                        <i class="fas fa-pen text-white/80 text-sm"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4">
                        <div class="flex flex-col space-y-3">
                            <!-- Statistics -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <span class="text-sm text-gray-500">Sections</span>
                                    <p class="text-lg font-semibold text-gray-700">{{ $course->sections->count() ?? 0 }}</p>
                                </div>
                                <div class="text-center">
                                    <span class="text-sm text-gray-500">Students</span>
                                    <p class="text-lg font-semibold text-gray-700">{{ $course->students_count }}</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a 
                                wire:navigate 
                                href="{{ route('admin.courses.show', $course->course_code) }}"
                                class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                            >
                                <span class="text-sm font-medium">View Details</span>
                                <i class="fa fa-arrow-right text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-8 bg-white rounded-xl shadow-sm">
                    <i class="fa fa-book-open text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-lg">No courses found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your search</p>
                </div>
            @endforelse
        </div>

        {{-- <!-- Pagination if needed -->
        @if($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif --}}
    </div>
    @if($showImportModal)
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button wire:click="$set('showImportModal', false)" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Import Courses</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload a CSV file with courses and sections.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                        <select wire:model="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select Academic Year</option>
                            @foreach(\App\Models\Academic::where('status', 1)->get() as $academic)
                                <option value="{{ $academic->id }}">{{ $academic->academic_year }} - {{ $academic->semester }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">CSV File</label>
                        <input type="file" wire:model="importFile" accept=".csv" class="mt-1 block w-full">
                        <p class="mt-1 text-xs text-gray-500">Download the <a href="#" class="text-blue-500">template file</a></p>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6">
                    <button 
                        wire:click="importCourses"
                        class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-dark"
                    >
                        Import Courses
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>
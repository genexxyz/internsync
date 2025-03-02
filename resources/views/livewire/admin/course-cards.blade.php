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
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                    <!-- Card Header -->
                    <div class="bg-secondary text-white rounded-t-xl p-4">
                        <div class="text-center space-y-1">
                            <h3 class="text-lg font-bold tracking-wide">{{ $course->course_code }}</h3>
                            <p class="text-sm text-white/90">{{ $course->course_name }}</p>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4">
                        <div class="flex flex-col space-y-3">
                            <!-- Statistics -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <span class="text-sm text-gray-500">Sections</span>
                                    <p class="text-lg font-semibold text-gray-700">{{ $course->sections_count ?? 0 }}</p>
                                </div>
                                <div class="text-center">
                                    <span class="text-sm text-gray-500">Students</span>
                                    <p class="text-lg font-semibold text-gray-700">{{ $course->students_count ?? 0 }}</p>
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
</div>
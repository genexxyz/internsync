<div class="mt-3 p-4  rounded-md">
    <div class="relative" wire:loading.class="opacity-50">
        <!-- Loading indicator -->
        <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50 z-10">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <!-- Section Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($sections as $section)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <!-- Card Header -->
                    <div class="bg-secondary text-white rounded-t-xl px-4 py-3">
                        <div class="flex flex-col items-center space-y-1">
                            <h3 class="text-xl font-bold">
                                {{$courses->course_code}} {{ $section->year_level }}{{ $section->class_section }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <i class="fa fa-chalkboard-user text-white/80"></i>
                                <p class="text-sm font-medium">
                                    @if($section->handles->whereNotNull('instructor.user')->where('instructor.user.is_verified', true)->count() > 0)
                                        @foreach($section->handles->whereNotNull('instructor.user')->where('instructor.user.is_verified', true) as $handle)
                                            {{ $handle->instructor->first_name }} {{ $handle->instructor->last_name }}
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    @else
                                        No Verified Instructor
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4">
                        <div class="flex flex-col space-y-2">
                            <!-- Student Count -->
                            <div class="flex items-center justify-center gap-2 text-gray-600">
                                <i class="fa fa-users w-5"></i>
                                <span class="text-sm">{{ $section->students_count ?? 0 }} Students</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-4 pt-3 border-t">
                            <a 
                                wire:navigate 
                                href="{{ route('admin.courses.sections.show', [
                                    'course_code' => $courses->course_code, 
                                    'year_level' => $section->year_level, 
                                    'class_section' => $section->class_section
                                ]) }}"
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
                    <i class="fa fa-folder-open text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-lg">No sections found</p>
                    <p class="text-gray-400 text-sm mt-1">Create a new section to get started</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
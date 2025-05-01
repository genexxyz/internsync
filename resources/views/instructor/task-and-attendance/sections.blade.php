<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('instructor.taskAttendance'), 'label' => 'Task & Attendance']]" />
    <div class="p-6">
        {{-- @if($programHeadCourses->isNotEmpty())
            <!-- Program Head Status -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Program Head of:</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($programHeadCourses as $program)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $program->course->course_code }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif --}}

        <!-- Group sections by course -->
        @php
            $sectionsByCourse = $sections->groupBy('course.course_code');
        @endphp

        @forelse($sectionsByCourse as $courseCode => $courseSections)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $courseCode }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($courseSections as $section)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <!-- Card Header -->
                            <div class="bg-secondary text-white rounded-t-xl p-4">
                                <div class="text-center space-y-1">
                                    <h3 class="text-lg font-bold tracking-wide">
                                        {{ $section->year_level }}{{ $section->class_section }}
                                    </h3>
                                    <p class="text-sm text-white/90">
                                        {{ $section->students->count() ?? 0 }} Student/s
                                    </p>
                                </div>
                            </div>
    
                            <!-- Card Content -->
                            <div class="p-4">
                                <div class="flex flex-col space-y-3">
                                    {{-- <!-- Role Badge -->
                                    <div class="text-center">
                                        @php
                                            $isProgramHead = $programHeadCourses->contains('course_id', $section->course_id);
                                            $isHandled = $section->handles->contains('instructor_id', $instructor->id);
                                        @endphp
                                        
                                        @if($isProgramHead)
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Program Head
                                            </span>
                                        @endif
                                        
                                        @if($isHandled)
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 {{ $isProgramHead ? 'mt-1' : '' }}">
                                                Advisory
                                            </span>
                                        @endif
                                    </div> --}}

                                    <!-- Action Button -->
                                    <a href="{{ route('instructor.taskAttendance.section.show', [
                                            'course_code' => $section->course->course_code,
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
                    @endforeach
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center p-8 bg-white rounded-xl shadow-sm">
                <i class="fa fa-folder-open text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500 text-lg">No sections found</p>
                <p class="text-gray-400 text-sm mt-1">Please wait for the Administrator to verify</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('instructor.deployments.section'), 'label' => 'Deployments']]" />
    
    <div class="p-6">
        <!-- Section Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($sections as $handle)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                    <!-- Card Header -->
                    <div class="bg-secondary text-white rounded-t-xl p-4">
                        <div class="text-center space-y-1">
                            <h3 class="text-lg font-bold tracking-wide">
                                {{ optional($handle->section->course)->course_code ?? 'N/A' }}
                                {{ optional($handle->section)->year_level ?? 'N/A' }}{{ optional($handle->section)->class_section ?? '' }}
                            </h3>
                            <p class="text-sm text-white/90">
                                {{ optional($handle->section->students)->count() ?? 0 }} Students
                            </p>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4">
                        <div class="flex flex-col space-y-3">
                            <!-- Action Button -->
                            @if($handle->section)
                                <a 
                                    href="{{ route('instructor.deployments.section.show', [
                                        'course_code' => $handle->section->course->course_code,
                                        'year_level' => $handle->section->year_level,
                                        'class_section' => $handle->section->class_section
                                    ]) }}"
                                    class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                                >
                                    <span class="text-sm font-medium">View Details</span>
                                    <i class="fa fa-arrow-right text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-8 bg-white rounded-xl shadow-sm">
                    <i class="fa fa-folder-open text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-lg">No sections found</p>
                    <p class="text-gray-400 text-sm mt-1">Please wait for the Administrator to verify</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
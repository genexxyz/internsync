<div class="mt-6 p-4 sm:p-6 bg-white rounded-xl shadow-sm">
    
    <!-- Header and Controls -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Instructors Management</h2>
            <button 
                onclick="Livewire.dispatch('openModal', { component: 'admin.import-instructors-modal' })"
                class="inline-flex items-center px-4 py-2.5 rounded-lg bg-primary text-white hover:bg-accent transition-colors gap-2 shadow-sm"
            >
                <i class="fas fa-file-import"></i>
                Import Instructors
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full h-11 rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                    placeholder="Search instructors..." 
                />
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            {{-- <select 
                wire:model.live="courseFilter"
                class="w-full sm:w-48 h-11 rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
            >
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_code }}</option>
                @endforeach
            </select> --}}
        </div>
    </div>

    <!-- Table Container -->
    <div class="relative overflow-hidden rounded-xl border border-gray-200">
        {{-- <!-- Loading Overlay -->
        <div 
            wire:loading 
            class="absolute inset-0 flex items-center justify-center bg-white/75 z-50 backdrop-blur-sm"
        >
            <div class="flex items-center gap-2 text-primary">
                <div class="animate-spin rounded-full h-6 w-6 border-2 border-primary border-t-transparent"></div>
                <span class="text-sm font-medium">Loading...</span>
            </div>
        </div> --}}

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Instructor Details
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                            Program Head
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                            Handled Sections
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($instructors as $instructor)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $instructor->first_name }} {{ $instructor->last_name }} {{ $instructor->suffix ?? '' }}
                                        </div>
                                        
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 whitespace-nowrap hidden lg:table-cell">
                                @if($instructor->instructorCourses->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($instructor->instructorCourses as $program)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $program->course->course_code }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 whitespace-nowrap hidden sm:table-cell">
                                @if($instructor->handles->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($instructor->handles as $handle)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $handle->section->course->course_code }}-{{ $handle->section->year_level }}{{ $handle->section->class_section }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">No sections</span>
                                @endif
                            </td>
                            <td class="px-6 whitespace-nowrap text-center">
                                <button
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'admin.instructor-modal', 
                                        arguments: { instructor: {{ $instructor->id }} } 
                                    })"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
                                >
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-users-slash text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No instructors found</p>
                                    <p class="text-sm text-gray-400">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $instructors->links() }}
    </div>
    <div class="fixed bottom-4 right-4">
        <button onclick="Livewire.dispatch('openModal', { component: 'admin.add-instructor-modal' })"
            class="w-16 h-16 bg-primary text-white rounded-full shadow-lg flex items-center justify-center hover:bg-accent focus:outline-none group">
            <i class="fa fa-plus"></i>
            <!-- Tooltip -->
            <span
                class="absolute top-1/2 -translate-y-1/2 right-20 bg-gray-800 text-white text-medium px-3 py-1 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                style="white-space: nowrap;">
                Add an Instructor
            </span>
        </button>
    </div>
</div>
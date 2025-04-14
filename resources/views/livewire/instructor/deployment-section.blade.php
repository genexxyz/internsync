<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/instructor/deployment-section.blade.php -->
<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- View Toggle -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-2">
            <button 
                wire:click="toggleView('section')"
                class="px-4 py-2 rounded-lg {{ $viewMode === 'section' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-th-large mr-2"></i>
                By Section
            </button>
            <button 
                wire:click="toggleView('all')"
                class="px-4 py-2 rounded-lg {{ $viewMode === 'all' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-list mr-2"></i>
                All Students
            </button>
        </div>

        @if($viewMode === 'all')
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="search"
                    class="w-64 pl-10 pr-4 py-2 rounded-lg border-gray-200 focus:border-primary focus:ring-primary"
                    placeholder="Search students...">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>

            <!-- Filter -->
            <select 
                wire:model.live="filter"
                class="rounded-lg border-gray-200 focus:border-primary focus:ring-primary">
                <option value="all">All Students</option>
                <option value="with_letter">With Acceptance Letter</option>
                <option value="no_letter">No Acceptance Letter</option>
                <option value="deployed">Deployed</option>
            </select>

            <!-- Date Sort - Only shows for relevant filters -->
            @if($showDateSort)
            <button 
                wire:click="toggleSort"
                class="flex items-center px-4 py-2 rounded-lg {{ $filter === 'with_letter' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }} hover:bg-opacity-75 transition-colors">
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ $filter === 'with_letter' ? 'Letter Date' : 'Deployment Date' }}
                @if($sortDirection === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @else
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </button>
            @endif
        </div>
        @endif
    </div>

    @if($viewMode === 'section')
        <!-- Sections Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($sections as $section)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="bg-secondary text-white rounded-t-xl p-4">
                        <div class="text-center space-y-1">
                            <h3 class="text-lg font-bold tracking-wide">
                                {{ $section->course->course_code ?? 'N/A' }}
                                {{ $section->year_level }}{{ $section->class_section }}
                            </h3>
                            <p class="text-sm text-white/90">
                                {{ $section->students->count() ?? 0 }} Students
                            </p>
                        </div>
                    </div>

                    <div class="p-4">
                        <a href="{{ route('instructor.deployments.section.show', [
                            'course_code' => $section->course->course_code,
                            'year_level' => $section->year_level,
                            'class_section' => $section->class_section
                        ]) }}"
                        class="flex items-center justify-center gap-2 w-full py-2 px-4 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <span class="text-sm font-medium">View Details</span>
                            <i class="fa fa-arrow-right text-sm"></i>
                        </a>
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
    @else
        <!-- Students List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($students as $student)
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $student->first_name . ' ' . $student->last_name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $student->yearSection->course->course_code }} 
                                {{ $student->yearSection->year_level }}{{ $student->yearSection->class_section }}
                            </p>
                            <button 
                                onclick="Livewire.dispatch('openModal', { 
                                    component: 'instructor.student-modal', 
                                    arguments: { student: {{ $student->id }} } 
                                })"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    Profile
                                </button>
                        </div>
                        <div class="flex-shrink-0 flex flex-col items-end space-y-2">
                            @if($student->deployment?->company_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-building mr-1"></i>
                                    Deployed
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $student->deployment->updated_at->format('M d, Y') }}
                                </span>
                                <!-- View Deployment Button -->
                                <button 
                                    wire:click=""
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    View Details
                                </button>
                                @elseif($student->acceptance_letter)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Letter<span class="text-xs text-gray-400">
                                        {{ $student->acceptance_letter->updated_at->format('M d, Y') }}
                                    </span>
                                </span>
                               
                                @if($isProgramHead)
                                    <!-- Assign Company Button - Only shown to program heads -->
                                    <button 
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'instructor.assign-modal', 
                                        arguments: { student: {{ $student->id }} } 
                                    })"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium shadow-md text-white bg-green-600 rounded-lg hover:bg-green-800 transition-colors">
                                        <i class="fas fa-building-user mr-1.5"></i>
                                        Assign
                                    </button>
                                @else
                                    <!-- Message for regular instructors -->
                                    <span class="text-xs text-gray-500 italic">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Waiting for program head
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-8 bg-white rounded-xl shadow-sm">
                    <i class="fa fa-users text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-lg">No students found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif
    @endif
</div>
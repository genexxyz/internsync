<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- Header and Controls -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">All Students</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fa fa-search text-gray-500"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Search students..." 
                />
            </div>
            
            <!-- Filter Dropdown -->
            <select 
                wire:model.live="filter"
                class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            >
                <option value="all">All Students</option>
                <option value="with_letter">With Acceptance Letter</option>
                <option value="no_letter">No Acceptance Letter</option>
            </select>
        </div>
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                <!-- Student Header -->
                <div class="bg-secondary text-white rounded-t-xl p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold">{{ $student->first_name }} {{ $student->last_name }}</h3>
                            <p class="text-sm text-white/80">{{ $student->student_id }}</p>
                        </div>
                        <div class="bg-white/20 rounded-lg p-2">
                            <i class="fa fa-user text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Student Content -->
                <div class="p-4">
                    <div class="space-y-3">
                        <!-- Section Info -->
                        <div class="flex items-center text-gray-600">
                            <i class="fa fa-graduation-cap w-5"></i>
                            <span>{{ $student->yearSection->course->course_code }} {{ $student->yearSection->year_level }}{{ $student->yearSection->class_section }}</span>
                        </div>

                        <!-- Acceptance Letter Status -->
                        @if($student->acceptance_letter)
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-600">
                                    <i class="fa fa-building w-5"></i>
                                    <span>{{ $student->acceptance_letter->company_name }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fa fa-calendar w-5"></i>
                                    <span>Submitted {{ $student->acceptance_letter->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fa fa-clock w-5"></i>
                                    <span> {{ $student->acceptance_letter->updated_at->format('M d, Y') ?? 00 }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No acceptance letter submitted</p>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="mt-4">
                        <button
                            onclick="Livewire.dispatch('openModal', { 
                                component: 'instructor.student-modal', 
                                arguments: { student: {{ $student->id }} } 
                            })"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200"
                        >
                            <i class="fa fa-eye mr-2"></i>
                            View Details
                        </button>
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
    <div class="mt-6">
        {{ $students->links() }}
    </div>
</div>
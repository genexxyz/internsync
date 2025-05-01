<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search students..."
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
        </div>
        
        @if($isProgramHead)
<div>
    <select 
        wire:model.live="courseFilter"
        class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
    >
        <option value="">All Courses</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
        @endforeach
    </select>
</div>
@endif

        <div>
            <select 
                wire:model.live="sectionFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Sections</option>
                @if($courseFilter)
                    @foreach($courses->find($courseFilter)->sections as $section)
                        <option value="{{ $section->id }}">{{ $section->year_level }}{{ $section->class_section }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <select 
                wire:model.live="statusFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Status</option>
                <option value="pending">No Letter</option>
                <option value="with_letter">With Letter</option>
                <option value="deployed">Deployed</option>
            </select>
        </div>
    </div>

    <!-- Students List -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('created_at')">
                        Date
                        @if($sortField === 'created_at')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left">Student</th>
                    <th class="py-3 px-6 text-left">Course</th>
                    <th class="py-3 px-6 text-left">Section</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($students as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6">
                            {{ $student->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->yearSection->course->course_code }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->yearSection->year_level }}{{ $student->yearSection->class_section }}
                        </td>
                        <td class="py-3 px-6">
                            @if($student->deployment?->company_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-building mr-1"></i>
                                    Deployed
                                </span>
                            @elseif($student->acceptance_letter)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    With Letter
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    No Letter
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- View Profile - Always visible -->
                                <button 
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'student-modal', 
                                        arguments: { student: {{ $student->id }} } 
                                    })"
                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors"
                                    title="View Profile">
                                    View Details
                                </button>
                        
                                <!-- Assign Company - Only for program head, with letter but not deployed -->
                                @if($isProgramHead && $student->acceptance_letter && !$student->deployment?->company_id  && 
    $programHeadCourses->pluck('course_id')->contains($student->yearSection->course_id))
    <button 
        onclick="Livewire.dispatch('openModal', { 
            component: 'instructor.assign-modal', 
            arguments: { student: {{ $student->id }} } 
        })"
        class="p-1.5 text-white bg-green-600 hover:bg-green-800 rounded-md transition-colors"
        title="Assign Company">
        Assign
    </button>
@endif
                        
                                {{-- <!-- Reassign Company - Only for program head and deployed students -->
                                @if($isProgramHead && $student->acceptance_letter && $student->deployment)
                                    <button 
                                        onclick="Livewire.dispatch('openModal', { 
                                            component: 'instructor.assign-modal', 
                                            arguments: { student: {{ $student->id }}, isReassign: true } 
                                        })"
                                        class="p-1.5 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-full transition-colors"
                                        title="Reassign Company">
                                        <i class="fas fa-building-circle-arrow-right"></i>
                                    </button>
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">
                            No students found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif
</div>
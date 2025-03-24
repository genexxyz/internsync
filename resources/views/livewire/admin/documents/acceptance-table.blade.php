<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Acceptance Letters Management</h2>
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
        <div>
            <select 
                wire:model.live="sectionFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Sections</option>
                @if($courseFilter)
                    @foreach($courses->find($courseFilter)->sections as $section)
                        <option value="{{ $section->id }}">{{ $section->course->year_level }}{{ $section->class_section }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div>
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="for_review">For Review</option>
                <option value="waiting_for_supervisor">Waiting for Supervisor</option>
                <option value="deployed">Deployed</option>
            </select>
        </div>
    </div>

    <!-- Table -->
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
                            {{ $student->section->course->course_code }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->section->course->year_level }}{{ $student->section->class_section }}
                        </td>
                        <td class="py-3 px-6">
                            @php
                                $status = 'pending';
                                if ($student->acceptance_letter) {
                                    if ($student->deployment?->company_id && $student->deployment?->supervisor_id) {
                                        $status = 'deployed';
                                    } elseif ($student->deployment?->company_id) {
                                        $status = 'waiting_for_supervisor';
                                    } else {
                                        $status = 'for_review';
                                    }
                                }
                        
                                $statusClasses = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'for_review' => 'bg-yellow-100 text-yellow-800',
                                    'waiting_for_supervisor' => 'bg-blue-100 text-blue-800',
                                    'deployed' => 'bg-green-100 text-green-800'
                                ];
                                
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'for_review' => 'For Review',
                                    'waiting_for_supervisor' => 'Waiting for Supervisor',
                                    'deployed' => 'Deployed'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$status] }}">
                                {{ $statusLabels[$status] }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <button 
                                wire:click="generatePdf({{ $student->id }})"
                                class="bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-primary-dark transition-colors
                                {{ $status != 'deployed' ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $status != 'deployed' ? 'disabled' : '' }}>
                                Generate PDF
                            </button>
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
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
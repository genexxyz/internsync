<div class="">
    <x-breadcrumbs :breadcrumbs="[['url' => route('admin.students'), 'label' => 'Students']]" />
    <div class="mt-6">
<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Students Management</h2>
        <button 
            onclick="Livewire.dispatch('openModal', { component: 'admin.import-students-modal' })"
            class="inline-flex items-center px-4 py-2.5 rounded-lg bg-primary text-white hover:bg-accent transition-colors gap-2 shadow-sm"
        >
            <i class="fas fa-file-import"></i>
            Import Students
        </button>
    </div>
    
    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <select 
                wire:model.live="academicFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                
                @foreach($academics as $academic)
                    <option value="{{ $academic->id }}">
                        {{ $academic->academic_year }} - {{ $academic->semester }}
                    </option>
                @endforeach
            </select>
        </div>
        
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
        
        
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="sortBy('student_id')">
                        Student ID
                        @if($sortField === 'student_id')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="sortBy('last_name')">
                        Name
                        @if($sortField === 'last_name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                    
                    <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($students as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $student->student_id }}</td>
                        <td class="px-6">{{ $student->last_name }}, {{ $student->first_name }}</td>
                        <td class="px-6">{{ $student->section->course->course_code }}</td>
                        <td class="px-6">
                            {{ $student->section->year_level }}{{ $student->section->class_section }}
                        </td>
                        <td class="px-6 whitespace-nowrap text-center">
                            <button 
                                wire:click="$dispatch('openModal', { component: 'student-modal', arguments: { student: {{ $student->id }} }})"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
                                >
                                    <i class="fas fa-eye mr-2"></i>
                                View Details
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
<div class="fixed bottom-4 right-4">
    <button onclick="Livewire.dispatch('openModal', { component: 'admin.add-student-modal' })"
        class="w-16 h-16 bg-primary text-white rounded-full shadow-lg flex items-center justify-center hover:bg-accent focus:outline-none group">
        <i class="fa fa-plus"></i>
        <!-- Tooltip -->
        <span
            class="absolute top-1/2 -translate-y-1/2 right-20 bg-gray-800 text-white text-medium px-3 py-1 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
            style="white-space: nowrap;">
            Add a Student
        </span>
    </button>
</div>
    </div>
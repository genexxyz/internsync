<div class="">
    <x-breadcrumbs :breadcrumbs="[['url' => route('admin.academic-year'), 'label' => 'Academic year']]" />
    <div class="mt-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Academic Year {{ $academic->academic_year }} - {{ $academic->semester }}
        </h2>
        <p class="text-gray-600 mt-1">
            {{ $academic->start_date?->format('M d, Y') }} - {{ $academic->end_date?->format('M d, Y') }}
        </p>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="setTab('instructors')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'instructors' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Instructors
            </button>
            <button wire:click="setTab('students')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'students' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Students
            </button>
            <button wire:click="setTab('supervisors')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'supervisors' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Supervisors
            </button>
        </nav>
    </div>
    <div class="mb-4 flex justify-between items-center">
        <div class="w-72">
            <div class="relative">
                <input type="text" 
                wire:model.live.debounce.300ms="search" 
                    class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm" 
                    placeholder="Search...">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Content -->
    <div class="bg-white rounded-lg shadow-sm">
        @if($activeTab === 'instructors')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->instructors as $program)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $program->instructor->getFullNameAttribute() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        @foreach ($program->instructor->sections as $section)
                                            {{ $section->course->course_code }}-{{$section->year_level}}{{$section->class_section}}{{ !$loop->last ? ', ' : '' }}
                                        
                                        @endforeach
                                        
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $program->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $program->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        wire:click="$dispatch('openModal', { component: 'admin.instructor-modal', arguments: { instructor: {{ $program->instructor->id }} }})"
                                        class="p-1.5 hover:bg-gray-100 rounded-full text-gray-600 transition-colors"
                                        title="View Details"
                                    >
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No instructors found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->instructors->links() }}
            </div>
        @elseif($activeTab === 'students')
            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->students as $deployment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $deployment->student->name() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $deployment->student->section->course->course_code }}-{{ $deployment->student->section->year_level }}{{ $deployment->student->section->class_section }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $deployment->company->company_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $deployment->status === 'ONGOING' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $deployment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        wire:click="$dispatch('openModal', { component: 'admin.student-modal', arguments: { student: {{ $deployment->student->id }} }})"
                                        class="p-1.5 hover:bg-gray-100 rounded-full text-gray-600 transition-colors"
                                        title="View Details"
                                    >
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No students found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->students->links() }}
            </div>
        @else
            <!-- Supervisors Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supervisor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->supervisors as $deployment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $deployment->supervisor->getFullNameAttribute() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $deployment->company->company_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $deployment->department->department_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $deployment->supervisor->deployments->where('academic_id', $academic->id)->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        wire:click="$dispatch('openModal', { component: 'admin.supervisor-modal', arguments: { supervisor: {{ $deployment->supervisor->id }} }})"
                                        class="p-1.5 hover:bg-gray-100 rounded-full text-gray-600 transition-colors"
                                        title="View Details"
                                    >
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No supervisors found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->supervisors->links() }}
            </div>
        @endif
    </div>
</div>
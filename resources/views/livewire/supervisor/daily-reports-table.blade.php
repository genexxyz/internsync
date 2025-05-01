<div>
    <!-- Filters Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Date Selector -->
        <div>
            <input 
                type="date" 
                wire:model.live="selectedDate"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                max="{{ now()->format('Y-m-d') }}"
            >
        </div>
    
        <!-- Search -->
        <div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search students..."
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
        </div>
    
        <!-- Reopen Button -->
        <div class="flex justify-end">
            <button
                onclick="Livewire.dispatch('openModal', { component: 'supervisor.journal-reopen-modal', arguments: { date: '{{ $selectedDate }}' }})"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
            >
                <i class="fas fa-unlock mr-2"></i>
                Reopen Date
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('student.last_name')">
                        Student Name
                        @if($sortField === 'student.last_name')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Attendance
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tasks
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($dailyReports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $report['student']->first_name }} {{ $report['student']->last_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($report['attendance'])
                                <div class="space-y-1">
                                    <span @class([
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        'bg-green-100 text-green-800' => $report['attendance']['status'] === 'regular',
                                        'bg-yellow-100 text-yellow-800' => $report['attendance']['status'] === 'late',
                                        'bg-red-100 text-red-800' => $report['attendance']['status'] === 'absent',
                                    ])>
                                        @if($report['attendance']['status'] === 'regular')
                                            Present
                                        @elseif($report['attendance']['status'] === 'late')
                                            Late
                                        @else
                                            Absent
                                        @endif
                                    </span>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">No record</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($report['journal'])
                                @if($report['tasks']->isNotEmpty())
                                    <div class="text-sm text-gray-900">
                                        {{ $report['tasks']->count() }} task(s)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Title:</span> {{ $report['journal']->text }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">No tasks added</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-500">No journal entry</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($report['journal'])
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    'bg-yellow-100 text-yellow-800' => !$report['journal']->is_approved === 0,
                                    'bg-green-100 text-green-800' => $report['journal']->is_approved === 1,
                                    'bg-red-100 text-red-800' => $report['journal']->is_approved === 2,
                                ])>
                                    @if($report['journal']->is_approved === 1)
                                        Approved
                                    @elseif($report['journal']->is_approved === 2)
                                        Rejected
                                    @else
                                        Pending
                                    @endif
                                </span>
                            @else
                                <span class="text-sm text-gray-500">No entry</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($report['journal'])
                                <button
                                    onclick="Livewire.dispatch('openModal', { component: 'supervisor.daily-report-review', arguments: { journal: {{ $report['journal']->id }} }})"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <span class="text-gray-400">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No students found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
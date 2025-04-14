<div>
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
                        <!-- Modal Header -->
                        <div class="bg-white px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Weekly Report Review
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </p>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="px-6 py-4">
                            <!-- Report Overview -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Week</h4>
                                    <p class="text-lg font-semibold">Week {{ $report->week_number }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} - 
                                        {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Total Hours</h4>
                                    <p class="text-lg font-semibold">{{ $this->formatHoursAndMinutes($weeklyTotal) }}</p>
                                </div>
                                {{-- <div>
                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $report->status === 'approved',
                                        'bg-yellow-100 text-yellow-800' => $report->status === 'pending',
                                        'bg-red-100 text-red-800' => $report->status === 'rejected'
                                    ])>
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div> --}}
                            </div>


                            <!-- Add this before the Daily Activities section -->
<div class="flex justify-end space-x-4 mb-4">
    <button 
        wire:click="approveAll"
        wire:confirm="Are you sure you want to approve all daily entries?"
        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md"
    >
        <i class="fas fa-check mr-2"></i>
        Approve All
    </button>
    <button 
        wire:click="rejectAll"
        wire:confirm="Are you sure you want to reject all daily entries?"
        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md"
    >
        <i class="fas fa-times mr-2"></i>
        Reject All
    </button>
</div>


                            <!-- Daily Journal Entries -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Daily Activities</h4>
                                <div class="flex items-center gap-4">
                                    <!-- Pending Status -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full shrink-0 bg-yellow-400"></div>
                                        <span class="text-sm text-gray-600">Pending</span>
                                    </div>
                                    
                                    <!-- Done Status -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full shrink-0 bg-green-400"></div>
                                        <span class="text-sm text-gray-600">Done</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border rounded-lg divide-y mb-6 overflow-hidden text-sm">
                                @foreach($weeklyJournals as $date => $data)
                                    <div class="p-3 {{ !$data['journal'] ? 'bg-gray-50' : 'hover:bg-gray-50' }}">
                                        <div class="flex justify-between items-start gap-4">
                                            <!-- Left: Date and Time -->
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h5 class="font-medium text-gray-900">
                                                            {{ Carbon\Carbon::parse($date)->format('l, M d') }}
                                                        </h5>
                                                        @if($data['journal'] && $data['journal']->attendance)
                                                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                                                <span>
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    {{ $data['journal']->attendance->time_in ? Carbon\Carbon::parse($data['journal']->attendance->time_in)->format('h:i A') : 'No time in' }} - 
                                                                    {{ $data['journal']->attendance->time_out ? Carbon\Carbon::parse($data['journal']->attendance->time_out)->format('h:i A') : 'No time out' }}
                                                                </span>
                                                                @if($data['daily_total'] !== '00:00')
                                                                    <span class="px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                                                        {{ $this->formatHoursAndMinutes($data['daily_total']) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-red-500 mt-0.5">No Entry</p>
                                                        @endif
                                                    </div>
                                                </div>
                            
                                                @if($data['journal'])
                                                    <!-- Notes -->
                                                    @if($data['journal']->text)
                                                        <p class="mt-2 text-sm text-gray-600">{{ $data['journal']->text }}</p>
                                                    @endif
                            
                                                    <!-- Tasks -->
                                                    @if($data['tasks']->isNotEmpty())
                                                        <div class="mt-3 space-y-2">
                                                            @foreach($data['tasks'] as $task)
                                                                <div class="bg-white p-2 rounded border border-gray-200 hover:border-gray-300">
                                                                    <div class="flex items-start gap-2">
                                                                        <!-- Task Status Icon -->
                                                                        <div @class([
                                                                            'mt-0.5 w-2 h-2 rounded-full shrink-0',
                                                                            'bg-yellow-400' => $task['status'] === 'pending',
                                                                            'bg-green-400' => $task['status'] === 'done',
                                                                        ])></div>
                                                                        
                                                                        <!-- Task Details -->
                                                                        <div class="min-w-0 flex-1">
                                                                            <div class="flex items-center gap-2">
                                                                                <h6 class="font-medium text-gray-900 truncate">{{ $task['title'] }}</h6>
                                                                                <span class="text-xs text-gray-500">
                                                                                    @if($task['worked_hours'])
                                                                                        · {{ $task['worked_hours'] }}h
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                            @if($task['description'])
                                                                                <p class="text-xs text-gray-600 line-clamp-2">{{ $task['description'] }}</p>
                                                                            @endif
                                                                            @if($task['remarks'])
                                                                                <p class="text-xs text-gray-500 mt-1 italic">{{ $task['remarks'] }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                            
                                            <!-- Right: Status and Actions -->
                                            @if($data['journal'])
                                                <div class="flex flex-col items-end space-y-2 shrink-0">
                                                    <span @class([
                                                        'px-2 py-0.5 rounded-full text-xs font-medium',
                                                        'bg-yellow-100 text-yellow-800' => $dailyApprovals[$date]['journal_status'] === 0,
                                                        'bg-green-100 text-green-800' => $dailyApprovals[$date]['journal_status'] === 1,
                                                        'bg-red-100 text-red-800' => $dailyApprovals[$date]['journal_status'] === 2,
                                                    ])>
                                                        {{ ['Pending', 'Approved', 'Rejected'][$dailyApprovals[$date]['journal_status']] }}
                                                    </span>
                                                    <div class="flex items-center gap-1">
                                                        <button 
                                                            wire:click="approveDay('{{ $date }}')"
                                                            class="p-1 text-green-600 hover:text-green-800"
                                                            title="Approve"
                                                        >
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button 
                                                            wire:click="rejectDay('{{ $date }}')"
                                                            class="p-1 text-red-600 hover:text-red-800"
                                                            title="Reject"
                                                        >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Learning Outcomes -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Learning Outcomes</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700">{{ $report->learning_outcomes }}</p>
                                </div>
                            </div>
<!-- Previous Feedback Section (if exists) -->
@if($report->status !== 'pending' && ($report->supervisor_feedback || $report->reviewed_at))
    <div class="mb-6">
        <h4 class="font-medium text-gray-900 mb-3">
            Previous Review
            @if($report->status === 'approved')
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Approved
                </span>
            @elseif($report->status === 'rejected')
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Rejected
                </span>
            @endif
        </h4>
        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
            @if($report->supervisor_feedback)
                <p class="text-gray-700 italic">{{ $report->supervisor_feedback }}</p>
            @else
                <p class="text-gray-500 italic">No feedback provided</p>
            @endif
            @if($report->reviewed_at)
                <p class="mt-2 text-sm text-gray-500">
                    Reviewed on {{ Carbon\Carbon::parse($report->reviewed_at)->format('M d, Y \a\t h:i A') }}
                </p>
            @endif
        </div>
    </div>
@endif
                            <!-- Existing Feedback Form section -->
<div class="mb-6">
    <h4 class="font-medium text-gray-900 mb-3">
        {{ $report->status !== 'pending' ? 'Update Feedback' : 'Feedback' }}
    </h4>
    <textarea
        wire:model="feedbackNote"
        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
        rows="3"
        placeholder="Optional feedback for the student..."
    ></textarea>
</div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-3">
                                <button 
                                    wire:click="rejectReport"
                                    wire:confirm="Are you sure you want to reject this weekly report?"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                >
                                    Reject Report
                                </button>
                                <button 
                                    wire:click="approveReport"
                                    wire:confirm="Are you sure you want to approve this weekly report?"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                                >
                                    Approve Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
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


                            {{-- <!-- Add this before the Daily Activities section -->
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
</div> --}}


                            <!-- Daily Journal Entries -->
                            <div class="border rounded-lg divide-y mb-6 overflow-hidden text-sm">
                                @foreach($weeklyJournals as $date => $data)
                                    <div>
                                        <!-- Header - Date and Toggle -->
                                        {{-- <div class="p-3 {{ !$data['journal'] ? 'bg-gray-50' : 'hover:bg-gray-50' }} cursor-pointer"
                                             wire:click="toggleDailyDetails('{{ $date }}')"> --}}
                                             <div class="p-3 {{ !$data['journal'] ? 'bg-gray-50' : 'hover:bg-gray-50' }}"
                                             >
                                            <div class="">
                                                <!-- Left: Date -->
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">
                                                        {{ Carbon\Carbon::parse($date)->format('l, M d') }}
                                                    </h5>
                                                    {{-- @if(!$data['journal'])
                                                        <p class="text-xs text-red-500 mt-0.5">No Entry</p>
                                                    @endif --}}
                                                </div>
                            
                                                <!-- Right: Status and Toggle Icon -->
                                                <div class="mt-4">
                                                    @if($data['journal'])
                                                        <div class="mt-1 text-gray-800 font-semibold">
                                                            {{ $data['journal']->text }}
                                                        </div>
                                                
                                                        <!-- Tasks List -->
                                                        @if($data['journal']->tasks->isNotEmpty())
                                                            <div class="mt-2">
                                                                <ul class="list-disc list-inside">
                                                                    @foreach($data['journal']->tasks as $task)
                                                                        <li class="text-gray-800 text-sm">
                                                                            {{ $task->description }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Dropdown Content -->
                                        {{-- @if($data['journal'] && $selectedDate === $date)
    <div class="border-t border-gray-200 bg-gray-50 p-4">
        <!-- Attendance Section -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Attendance</h3>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                @if($data['journal']->attendance)
                    <div class="space-y-3">
                        <!-- Status and Approval -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    'bg-green-100 text-green-800' => $data['journal']->attendance->status === 'regular',
                                    'bg-yellow-100 text-yellow-800' => $data['journal']->attendance->status === 'late',
                                    'bg-red-100 text-red-800' => $data['journal']->attendance->status === 'absent',
                                ])>
                                    {{ ucfirst($data['journal']->attendance->status) }}
                                </span>
                                @if($data['journal']->attendance->is_approved === 1)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Approved
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Time Details -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <!-- Time In/Out -->
                            <div class="space-y-1">
                                <p class="text-gray-500">Time In/Out</p>
                                <p class="font-medium">
                                    {{ $data['journal']->attendance->time_in ? Carbon\Carbon::parse($data['journal']->attendance->time_in)->format('h:i A') : 'N/A' }}
                                    @if($data['journal']->attendance->time_out)
                                        - {{ Carbon\Carbon::parse($data['journal']->attendance->time_out)->format('h:i A') }}
                                    @else
                                        <span class="text-yellow-600">(No time out)</span>
                                    @endif
                                </p>
                            </div>
                        
                            <!-- Break Time -->
                            <div class="space-y-1">
                                <p class="text-gray-500">Break Time</p>
                                @if($data['journal']->attendance->start_break)
                                    <p class="font-medium">
                                        {{ Carbon\Carbon::parse($data['journal']->attendance->start_break)->format('h:i A') }}
                                        @if($data['journal']->attendance->end_break)
                                            - {{ Carbon\Carbon::parse($data['journal']->attendance->end_break)->format('h:i A') }}
                                        @else
                                            <span class="text-yellow-600">(No break end)</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="text-gray-400">No break record</p>
                                @endif
                            </div>
                        </div>

                        <!-- Total Hours -->
                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Hours:</span>
                                <span class="text-sm font-medium">{{ $data['journal']->attendance->total_hours ?? '00:00' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No attendance record</p>
                @endif
            </div>
        </div>

        <!-- Tasks Section -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Tasks</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                        <span class="text-sm text-gray-600">Done</span>
                    </div>
                </div>

                @forelse($data['journal']->taskHistories->groupBy('task_id') as $taskHistories)
                    @php
                        $latestHistory = $taskHistories->sortByDesc('changed_at')->first();
                        $task = $latestHistory->task;
                    @endphp
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <div class="flex items-start gap-3">
                            <div @class([
                                'mt-1 w-2 h-2 rounded-full shrink-0',
                                'bg-yellow-400' => $latestHistory->status === 'pending',
                                'bg-green-400' => $latestHistory->status === 'done',
                            ])></div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="mt-1 text-sm text-gray-500">{{ $task->description }}</p>
                                @endif
                                @if($latestHistory->remarks)
                                    <p class="mt-2 text-sm text-gray-600 italic">{{ $latestHistory->remarks }}</p>
                                @endif
                            </div>
                            @if($latestHistory->worked_hours)
                                <span class="text-xs text-gray-500">{{ $latestHistory->worked_hours }}h</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No tasks recorded</p>
                @endforelse
            </div>
        </div>

        <!-- Feedback Section - Only show if rejected -->
        @if(!$data['journal']->is_approved || $data['journal']->is_approved === 2)
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Feedback</h3>
        <textarea
            wire:model="dailyFeedback.{{ $date }}"
            rows="3"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
            placeholder="Enter feedback for this day..."
            @if($data['journal']->is_approved === 2)
                required
            @endif
        ></textarea>
        @error("dailyFeedback.$date")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Reopen Option -->
        <div class="mt-3">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="dailyReopen.{{ $date }}"
                    class="rounded border-gray-300 text-primary focus:ring-primary"
                    @if($data['journal']->is_approved === 2)
                        checked
                    @endif
                >
                <span class="ml-2 text-sm text-gray-600">Allow student to edit this entry</span>
            </label>
        </div>
    </div>
@endif

<!-- Action Buttons -->
@if(!$data['journal']->is_approved)
    <div class="flex justify-end gap-3">
        <button 
            wire:click="rejectDailyEntry('{{ $date }}')"
            wire:confirm="Are you sure you want to reject this daily entry?"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
        >
            <i class="fas fa-times mr-1.5"></i>
            Reject Entry
        </button>
        <button 
            wire:click="approveDailyEntry('{{ $date }}')"
            wire:confirm="Are you sure you want to approve this daily entry?"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
        >
            <i class="fas fa-check mr-1.5"></i>
            Approve Entry
        </button>
    </div>
@else
    <div class="flex justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm text-green-800 bg-green-100 rounded-md">
            <i class="fas fa-check-circle mr-1.5"></i>
            Entry Approved
        </span>
    </div>
@endif
    </div>
@endif --}}
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
@if($report->status !== 'approved')
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
            <i class="fas fa-times mr-1.5"></i>
            Reject Report
        </button>
        <button 
            wire:click="approveReport"
            wire:confirm="Are you sure you want to approve this weekly report?"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
        >
            <i class="fas fa-check mr-1.5"></i>
            Approve Report
        </button>
    </div>
@else
    <div class="flex justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm text-green-800 bg-green-100 rounded-md">
            <i class="fas fa-check-circle mr-1.5"></i>
            Report Approved
        </span>
    </div>
@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
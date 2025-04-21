<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Daily Report Review
        </h2>
        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <i class="fas fa-user mr-1.5"></i>
                {{ $student->first_name }} {{ $student->last_name }}
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <i class="fas fa-calendar mr-1.5"></i>
                {{ Carbon\Carbon::parse($journal->date)->format('l, M d, Y') }}
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <i class="fas fa-clock mr-1.5"></i>
                Submitted {{ $journal->created_at->format('h:i A') }}
            </div>
        </div>
    </div>

    <!-- Attendance Section -->
<div class="mb-6">
    <h3 class="text-sm font-medium text-gray-900 mb-3">Attendance</h3>
    <div class="bg-gray-50 rounded-lg p-4">
        @if($journal->attendance)
            <div class="space-y-3">
                <!-- Status and Approval -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span @class([
                            'px-2 py-1 text-xs font-medium rounded-full',
                            'bg-green-100 text-green-800' => $journal->attendance->status === 'regular',
                            'bg-yellow-100 text-yellow-800' => $journal->attendance->status === 'late',
                            'bg-red-100 text-red-800' => $journal->attendance->status === 'absent',
                        ])>
                            @if($journal->attendance->status === 'regular')
                                Present
                            @elseif($journal->attendance->status === 'late')
                                Late
                            @else
                                Absent
                            @endif
                        </span>
                        
                        <!-- Approval Status -->
                        @if($journal->attendance->is_approved === 1)
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
                        @if($journal->attendance->time_in)
                            <p class="font-medium">
                                {{ Carbon\Carbon::parse($journal->attendance->time_in)->format('h:i A') }}
                                @if($journal->attendance->time_out)
                                    - {{ Carbon\Carbon::parse($journal->attendance->time_out)->format('h:i A') }}
                                @else
                                    <span class="text-yellow-600">(No time out)</span>
                                @endif
                            </p>
                        @else
                            <p class="text-gray-400">No record</p>
                        @endif
                    </div>

                    <!-- Break Time -->
                    <div class="space-y-1">
                        <p class="text-gray-500">Break Time</p>
                        @if($journal->attendance->start_break)
                            <p class="font-medium">
                                {{ Carbon\Carbon::parse($journal->attendance->start_break)->format('h:i A') }}
                                @if($journal->attendance->end_break)
                                    - {{ Carbon\Carbon::parse($journal->attendance->end_break)->format('h:i A') }}
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
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Hours</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $journal->attendance->total_hours ?? '00:00' }}
                        </span>
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
            <div>
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
            @forelse($journal->taskHistories->groupBy('task_id') as $taskHistories)
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

    @if($showFeedbackForm)
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Feedback</h3>
            <textarea wire:model="feedbackNote" rows="3"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                placeholder="Enter feedback for the student..."></textarea>
            @error('feedbackNote')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reopen Option for Rejection -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model="reopenEntry"
                    class="rounded border-gray-300 text-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Allow student to edit this entry</span>
            </label>
        </div>
    @endif

    <!-- Action Buttons -->
    @if(!$isInApprovedWeeklyReport)
    @if($journal->is_approved === 0 || $journal->is_approved === 2)
        <div class="flex justify-end gap-3">
            @if(!$showFeedbackForm)
                <button 
                    wire:click="$set('showFeedbackForm', true)"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                >
                    <i class="fas fa-times mr-1.5"></i>
                    Reject Entry
                </button>
                <button 
                    wire:click="approveEntry"
                    wire:confirm="Are you sure you want to approve this entry?"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                >
                    <i class="fas fa-check mr-1.5"></i>
                    Approve Entry
                </button>
                @else
                <div class="flex justify-end gap-3">
                    <button 
                        wire:click="$set('showFeedbackForm', false)"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        <i class="fas fa-times mr-1.5"></i>
                        Cancel
                    </button>
                    <button 
                        wire:click="rejectEntry"
                        wire:confirm="Are you sure you want to reject this entry?"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                        
                    >
                        <i class="fas fa-paper-plane mr-1.5"></i>
                        Submit Feedback
                    </button>
                </div>
            @endif
        </div>
    @else
        <div class="flex justify-center">
            <span class="inline-flex items-center px-4 py-2 text-sm text-green-800 bg-green-100 rounded-md">
                <i class="fas fa-check-circle mr-1.5"></i>
                Entry Approved
            </span>
        </div>
    @endif
@else
    <div class="flex justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm text-blue-800 bg-blue-100 rounded-md">
            <i class="fas fa-lock mr-1.5"></i>
            This entry is part of an approved weekly report
        </span>
    </div>
@endif
</div>
<div class="border rounded-lg p-4 {{ !$journal ? 'bg-gray-50' : 'bg-white' }}">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h4 class="font-medium text-gray-900">
                {{ Carbon\Carbon::parse($date)->format('l, M d') }}
                @if($journal)
                    <span @class([
                        'px-2 py-1 text-xs font-medium rounded-full',
                        'bg-yellow-100 text-yellow-800' => $journal->is_approved === 0,
                        'bg-green-100 text-green-800' => $journal->is_approved === 1,
                        'bg-red-100 text-red-800' => $journal->is_approved === 2,
                    ])>
                        @if($journal->is_approved === 1)
                            Approved
                        @elseif($journal->is_approved === 2)
                            Rejected
                        @else
                            Pending
                        @endif
                    </span>
                @endif
                @if(Carbon\Carbon::parse($date)->isWeekend())
                    <span class="text-xs font-normal text-yellow-600 ml-2">(Weekend)</span>
                @endif
            </h4>
            @if($journal?->attendance)
                <p class="text-sm text-gray-500">
                    {{ Carbon\Carbon::parse($journal->attendance->time_in)->format('h:i A') }} - 
                    {{ Carbon\Carbon::parse($journal->attendance->time_out)->format('h:i A') }}
                    <span class="ml-2 text-blue-600">
                        ({{ $journal->attendance->total_hours }})
                    </span>
                </p>
            @endif
        </div>
    </div>

    @if($journal)
        <!-- Journal Title -->
        
            <div class="mt-1 text-gray-800 font-semibold">
                {{ $journal->text }}
            </div>
        

        <!-- Tasks List -->
        @if($journal->tasks->isNotEmpty())
            <div class="">
                <ul class="list-disc list-inside">
                    @foreach($journal->tasks as $task)
                        <li class="text-gray-800 text-sm">
                            {{ $task->description }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($journal->is_approved === 2 && $journal->feedback)
            <div class="mt-4 bg-red-50 border border-red-100 rounded-lg p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-xs font-medium text-red-800">Supervisor's Feedback:</h4>
                        <p class="mt-1 text-xs text-red-700">
                            {{ $journal->feedback }}
                        </p>
                        <p class="mt-2 text-xs text-red-600">
                            Rejected on: {{ Carbon\Carbon::parse($journal->reviewed_at)->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @else
        <p class="text-sm text-gray-500 italic">No entries for this day</p>
    @endif
</div>
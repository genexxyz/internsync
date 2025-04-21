<div class="border rounded-lg p-4 {{ !$journal ? 'bg-gray-50' : 'bg-white' }}">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h4 class="font-medium text-gray-900">
                {{ Carbon\Carbon::parse($date)->format('l, M d') }}



                @if($journal)
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    'bg-yellow-100 text-yellow-800' => !$journal->is_approved === 0,
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
                            @else
                                <span class="text-sm text-gray-500"></span>
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
        <div class="prose prose-sm max-w-none text-gray-600 mb-4">
            {{ $journal->text }}
        </div>

        @if($journal->taskHistories->isNotEmpty())
            <div class="mt-4 space-y-3">
                {{-- <h5 class="text-sm font-medium text-gray-700">Tasks</h5> --}}
                @foreach($journal->taskHistories->groupBy('task_id') as $histories)
                    @php
                        $latestHistory = $histories->first();
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-900">{{ $latestHistory->task->description }}</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                                $latestHistory->status === 'done' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' 
                            }}">
                                {{ ucfirst($latestHistory->status) }}
                            </span>
                        </div>
                       
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <p class="text-sm text-gray-500 italic">No entries for this day</p>
    @endif
</div>
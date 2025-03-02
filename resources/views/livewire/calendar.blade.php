<div class="bg-white rounded-lg shadow-sm p-5">
    <!-- Calendar Navigation -->
    <div class="flex justify-between items-center mb-4">
        <button wire:click="changeMonth(-1)" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
            Previous
        </button>
        <h2 class="text-xl font-bold text-gray-600">
            {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
        </h2>
        <button wire:click="changeMonth(1)" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
            Next
        </button>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-2">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="text-center font-bold text-gray-600">{{ $day }}</div>
        @endforeach

        @for ($i = 1; $i <= $daysInMonth; $i++)
            <div 
                class="p-5 border rounded cursor-pointer hover:bg-gray-200 {{ isset($events[Carbon\Carbon::create($currentYear, $currentMonth, $i)->toDateString()]) ? 'bg-blue-100' : '' }}"
                wire:click="selectDate('{{ Carbon\Carbon::create($currentYear, $currentMonth, $i)->toDateString() }}')">
                {{ $i }}
            </div>
        @endfor
    </div>

    <!-- Task Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-lg w-1/2">
                <h2 class="text-lg font-bold mb-4">Tasks on {{ $selectedDate }}</h2>

                @if (count($selectedTasks) > 0)
                    <ul class="space-y-2">
                        @foreach ($selectedTasks as $task)
                            <li class="border p-3 rounded">
                                <p><strong>Text:</strong> {{ $task['text'] ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> {{ $task['remarks'] ?? 'N/A' }}</p>
                                @if (isset($task['time_in']))
                                    <p><strong>Time In:</strong> {{ $task['time_in'] }}</p>
                                    <p><strong>Time Out:</strong> {{ $task['time_out'] }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No tasks available for this day.</p>
                @endif

                <button wire:click="$set('showModal', false)" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                    Close
                </button>
            </div>
        </div>
    @endif
</div>

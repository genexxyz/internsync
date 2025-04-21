<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Attendance Calendar</h2>
        <button wire:click="generateDtr()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 gap-2"><i class="fa fa-file-pdf"></i>Generate DTR</button>
    </div>
    <div class="p-6">

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Calendar Header -->
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <button wire:click="changeMonth(-1)" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <h2 class="text-lg font-semibold text-gray-900">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
            </h2>
            <button wire:click="changeMonth(1)" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-right text-gray-600"></i>
            </button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="p-6">
        <div class="grid grid-cols-7 gap-4">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-center text-sm font-medium text-gray-500">{{ $day }}</div>
            @endforeach
    
            @for ($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $currentDate = Carbon\Carbon::create($currentYear, $currentMonth, $i);
                    $hasTask = isset($events[$currentDate->toDateString()]);
                    $isToday = $currentDate->isToday();
                    $isAbsent = $hasTask && isset($events[$currentDate->toDateString()]['status']) && 
                               $events[$currentDate->toDateString()]['status'] === 'absent';
                @endphp
                
                <div 
                    wire:click="selectDate('{{ $currentDate->toDateString() }}')"
                    class="aspect-square flex flex-col items-center justify-center rounded-lg border {{ 
                        $isToday ? 'border-primary ring-1 ring-primary' : 'border-gray-200'
                    }} {{ 
                        $isAbsent ? 'bg-red-50 hover:bg-red-100' : 
                        ($hasTask ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50') 
                    }} cursor-pointer transition-colors"
                >
                    <span class="{{ $isToday ? 'text-primary font-semibold' : 'text-gray-900' }}">{{ $i }}</span>
                    @if($hasTask)
                        <div class="mt-1 w-1.5 h-1.5 rounded-full {{ 
                            $isAbsent ? 'bg-red-500' : 'bg-blue-500'
                        }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    
        <!-- Legend -->
        <div class="mt-6 flex items-center justify-center space-x-6 text-sm">
            <div class="flex items-center">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></div>
                <span class="text-gray-600">Present</span>
            </div>
            <div class="flex items-center">
                <div class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></div>
                <span class="text-gray-600">Absent</span>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div class="absolute right-0 top-0 pr-4 pt-4">
                            <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                            @if ($selectedTask)
                                <div class="space-y-4">
                                    <!-- Journal Section -->
                                    <div class="border-b border-gray-200 pb-4">
                                        <h4 class="text-sm font-medium text-gray-500 mb-2">Journal Entry</h4>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $selectedTask['is_submitted'] 
                                                    ? 'bg-blue-100 text-blue-800' 
                                                    : 'bg-gray-100 text-gray-800' 
                                            }}">
                                                {{ $selectedTask['is_submitted'] ? 'Submitted' : 'Draft' }}
                                            </span>
                                            @if($selectedTask['is_submitted'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                    $selectedTask['is_approved'] 
                                                        ? 'bg-green-100 text-green-800' 
                                                        : 'bg-yellow-100 text-yellow-800' 
                                                }}">
                                                    {{ $selectedTask['is_approved'] ? 'Approved' : 'Pending Approval' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                        
                                    <!-- Attendance Section -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 mb-2">Attendance Details</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Time In</p>
                                                <p class="font-medium text-gray-900">{{ $selectedTask['time_in'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Time Out</p>
                                                <p class="font-medium text-gray-900">{{ $selectedTask['time_out'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Break Start</p>
                                                <p class="font-medium text-gray-900">{{ $selectedTask['start_break'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Break End</p>
                                                <p class="font-medium text-gray-900">{{ $selectedTask['end_break'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                        
                                        <div class="mt-4 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-gray-500">Total Hours</p>
                                                <p class="font-medium text-gray-900">{{ $selectedTask['total_hours'] ?? '0' }} hours</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $selectedTask['status'] === 'regular' ? 'bg-green-100 text-green-800' :
                                                ($selectedTask['status'] === 'late' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800') 
                                            }}">
                                                {{ ucfirst($selectedTask['status']) }}
                                            </span>
                                        </div>
                                    </div>
                        
                                    @if($selectedTask && !empty($selectedTask['tasks']))
    <div class="mt-4 border-t border-gray-200 pt-4">
        <h4 class="text-sm font-medium text-gray-500 mb-2">Tasks</h4>
        <div class="space-y-2">
            @foreach($selectedTask['tasks'] as $task)
                <div class="bg-white p-3 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <p class="text-sm text-gray-900">{{ $task['description'] }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                            $task['status'] === 'done' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' 
                        }}">
                            {{ ucfirst($task['status']) }}
                        </span>
                    </div>
                    @if(!empty($task['history']))
                        <div class="mt-2 space-y-1">
                            @foreach($task['history'] as $history)
                                <p class="text-xs text-gray-500">
                                    {{ $history['changed_at'] }} - 
                                    <span class="font-medium {{ $history['status'] === 'done' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ ucfirst($history['status']) }}
                                    </span>
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
                                </div>
                            @else
                                <p class="text-gray-500 text-center">No records for this day.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
</div>
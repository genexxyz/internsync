<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center pb-4 border-b">
        <h3 class="text-xl font-semibold text-gray-900">Edit Reopened Entry</h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="max-h-[calc(100vh-16rem)] overflow-y-auto py-6">
        <!-- Date Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Reopened Date</label>
            <select wire:model.live="selectedDate" class="w-full rounded-lg border-gray-300">
                <option value="">Select a date...</option>
                @foreach($reopenRequests as $request)
                    <option value="{{ $request->reopened_date }}">
                        {{ Carbon\Carbon::parse($request->reopened_date)->format('F j, Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($selectedDate)
        <!-- Supervisor's Message -->
        @php
        $selectedRequest = $reopenRequests->where('reopened_date', $selectedDate)->first();
    @endphp
    @if($selectedRequest && ($selectedRequest->message || $selectedRequest->expires_at))
    <div class="mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
            <!-- Message Section -->
            @if($selectedRequest->message)
                <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-comment-alt mr-2"></i>
                    Supervisor's Message/Feedback
                </h4>
                <p class="text-sm text-blue-700">{{ $selectedRequest->message }}</p>
            @endif

            <!-- Timeline Info -->
            <div class="mt-3 pt-3 border-t border-blue-100">
                <div class="flex flex-wrap items-center gap-4 text-xs text-blue-600">
                    <span class="flex items-center">
                        <i class="fas fa-clock mr-1"></i>
                        Reopened on {{ Carbon\Carbon::parse($selectedRequest->created_at)->format('M d, Y h:i A') }}
                    </span>

                    @if($selectedRequest->expires_at)
                        @php
                            $expiresAt = Carbon\Carbon::parse($selectedRequest->expires_at);
                            $now = now();
                            $hoursLeft = $now->diffInHours($expiresAt);
                            $isExpiringSoon = $hoursLeft <= 24 && $expiresAt->isFuture();
                            $isExpired = $expiresAt->isPast();
                        @endphp
                        
                        <span class="flex items-center {{ $isExpired ? 'text-red-600' : ($isExpiringSoon ? 'text-yellow-600' : 'text-blue-600') }}">
                            <i class="fas fa-hourglass-{{ $isExpired ? 'end' : ($isExpiringSoon ? 'half' : 'start') }} mr-1"></i>
                            @if($isExpired)
                                Expired {{ $expiresAt->diffForHumans() }}
                            @else
                            Due by {{ $expiresAt->format('M d, Y h:i A') }} (Expires {{ $expiresAt->diffForHumans() }}) 
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
            <!-- Time Entry -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time In</label>
                    <input type="time" wire:model="timeIn" class="w-full rounded-lg border-gray-300">
                    @error('timeIn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Out</label>
                    <input type="time" wire:model="timeOut" class="w-full rounded-lg border-gray-300">
                    @error('timeOut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Break</label>
                    <input type="time" wire:model="startBreak" class="w-full rounded-lg border-gray-300">
                    @error('startBreak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Break</label>
                    <input type="time" wire:model="endBreak" class="w-full rounded-lg border-gray-300">
                    @error('endBreak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tasks -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-medium text-gray-900">Tasks</h4>
                    <div class="flex gap-2">
                        <input type="text" wire:model="newTask" wire:keydown.enter="addTask" 
                            class="rounded-lg border-gray-300" placeholder="Add new task...">
                        <button wire:click="addTask" class="px-4 py-2 bg-primary text-white rounded-lg">
                            Add
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($tasks as $index => $task)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <input type="text" wire:model="tasks.{{ $index }}.description" 
                                    class="w-full bg-transparent border-0 focus:ring-0">
                            </div>
                            <select wire:model="tasks.{{ $index }}.status" 
                                class="rounded-lg border-gray-300">
                                <option value="pending">Pending</option>
                                <option value="done">Done</option>
                            </select>
                            <button wire:click="removeTask({{ $index }})" class="text-red-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mt-6 pt-4 border-t flex justify-end gap-3">
        <button wire:click="$dispatch('closeModal')" 
            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg">
            Cancel
        </button>
        @if($selectedDate)
            <button wire:click="saveChanges" wire:loading.attr="disabled"
                class="px-4 py-2 text-white bg-primary rounded-lg disabled:opacity-50">
                <span wire:loading.remove>Save Changes</span>
                <span wire:loading>Saving...</span>
            </button>
        @endif
    </div>
</div>
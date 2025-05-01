<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto p-6">
    <!-- Modal header -->
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
            <select wire:model.live="selectedDate" class="w-full rounded-md border-gray-300">
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
            @if($reopenRequests->where('reopened_date', $selectedDate)->first())
                @php
                    $request = $reopenRequests->where('reopened_date', $selectedDate)->first();
                @endphp
                <div class="mb-6 bg-blue-50 border border-blue-100 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800">Supervisor's Message</h4>
                            <p class="mt-1 text-sm text-blue-700">{{ $request->reason }}</p>
                            <p class="mt-2 text-xs text-blue-600">
                                Expires: {{ Carbon\Carbon::parse($request->expires_at)->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Time Entry Section -->
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Time Records</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time In</label>
                        <input type="time" wire:model="timeIn" class="w-full rounded-md border-gray-300">
                        @error('timeIn') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Out</label>
                        <input type="time" wire:model="timeOut" class="w-full rounded-md border-gray-300">
                        @error('timeOut') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Break</label>
                        <input type="time" wire:model="startBreak" class="w-full rounded-md border-gray-300">
                        @error('startBreak') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Break</label>
                        <input type="time" wire:model="endBreak" class="w-full rounded-md border-gray-300">
                        @error('endBreak') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Journal Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <div class="flex space-x-2">
                    <input 
                        type="text"
                        wire:model="journalText" 
                        class="flex-1 rounded-md border-gray-300"
                        placeholder="Enter title for this day..."
                    >
                    <button 
                        wire:click="saveJournalText"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark"
                    >
                        <i class="fas fa-check mr-2"></i>
                        Save Title
                    </button>
                </div>
                @error('journalText') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Tasks Section -->
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Tasks</h4>
                <!-- Add Task Input -->
                <div class="flex space-x-2 mb-4">
                    <input 
                        type="text"
                        wire:model="newTask" 
                        wire:keydown.enter="addTask"
                        class="flex-1 rounded-md border-gray-300"
                        placeholder="{{ empty($journalText) ? 'Please add a title first' : 'Add a task...' }}"
                        @if(empty($journalText)) disabled @endif
                    >
                    <button 
                        wire:click="addTask"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed"
                        @if(empty($journalText)) disabled @endif
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </div>

                <!-- Tasks List -->
                <ul class="space-y-2">
                    @if($journal)
                        @forelse($journal->tasks as $task)
                            <li class="group flex items-center justify-between bg-white rounded-md p-2 shadow-sm hover:shadow-md transition-shadow duration-200">
                                @if($editingTask === $task->id)
                                    <div class="flex-1 flex items-center space-x-2">
                                        <input 
                                            type="text"
                                            wire:model="editedDescription"
                                            class="flex-1 rounded-md border-gray-300 text-sm"
                                            wire:keydown.enter="saveEdited"
                                            wire:keydown.escape="cancelEditing"
                                            autofocus
                                        >
                                        <button 
                                            wire:click="saveEdited"
                                            class="text-green-600 hover:text-green-700"
                                            title="Save"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button 
                                            wire:click="cancelEditing"
                                            class="text-gray-400 hover:text-gray-500"
                                            title="Cancel"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-800">{{ $task->description }}</span>
                                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button 
                                            wire:click="startEditing({{ $task->id }})"
                                            class="text-blue-600 hover:text-blue-700 mr-2"
                                            title="Edit"
                                        >
                                            <i class="fas fa-pen text-sm"></i>
                                        </button>
                                        <button 
                                            wire:click="removeTask({{ $task->id }})"
                                            class="text-red-500 hover:text-red-700"
                                            title="Delete"
                                        >
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 text-center py-4">No tasks added yet.</li>
                        @endforelse
                    @endif
                </ul>
            </div>
        @endif
    </div>

    <!-- Modal Footer -->
    <div class="mt-6 pt-4 border-t flex justify-end space-x-3">
        <button 
            wire:click="$dispatch('closeModal')" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
        >
            Cancel
        </button>
        @if($selectedDate)
            <button 
                wire:click="saveChanges"
                wire:loading.attr="disabled"
                class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark disabled:opacity-50"
            >
                <span wire:loading.remove>Save Changes</span>
                <span wire:loading>Saving...</span>
            </button>
        @endif
    </div>
</div>
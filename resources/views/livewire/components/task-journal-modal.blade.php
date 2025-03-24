<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto p-6">
    <!-- Modal header -->
    <div class="flex justify-between items-center pb-4 border-b">
        <h3 class="text-xl font-semibold text-gray-900">Daily Tasks Journal</h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Main content with scrollable area -->
    <div class="max-h-[calc(100vh-16rem)] overflow-y-auto py-6">
        <!-- Add new task -->
        <div class="mb-6">
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    wire:model.defer="newTask" 
                    wire:keydown.enter="addTask"
                    class="flex-1 rounded-md border-gray-300" 
                    placeholder="Add a new task..."
                >
                <button 
                    wire:click="addTask" 
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add Task
                </button>
            </div>
        </div>

        <!-- Current day tasks -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-3">Today's Tasks</h4>
            <div class="space-y-2">
                @foreach($currentTasks as $task)
                    <div class="flex items-center space-x-3 p-3 bg-white border rounded-lg shadow-sm">
                        <div class="flex-1">
                            @if($editingTask === $task->id)
                                <div class="flex items-center space-x-2">
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
                                <div>
                                    <p class="text-gray-800">{{ $task->description }}</p>
                                    <div class="mt-1 space-y-1">
                                        @foreach($task->histories->take(3) as $history)
                                            <p class="text-xs text-gray-500">
                                                {{ $history->changed_at->format('M d, Y h:ia') }} - 
                                                <span class="font-medium {{ $history->status === 'done' ? 'text-green-600' : 'text-orange-600' }}">
                                                    {{ ucfirst($history->status) }}
                                                </span>
                                                {{-- ({{ $history->journal->date->format('M d, Y') }}) --}}
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <select 
                            wire:model.live="taskStatuses.{{ $task->id }}"
                            wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                            class="rounded-md border-gray-300 text-sm"
                        >
                            <option value="pending">Pending</option>
                            <option value="done">Done</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            @if($editingTask !== $task->id)
                                <button 
                                    wire:click="startEditing({{ $task->id }})"
                                    class="text-blue-600 hover:text-blue-700"
                                    title="Edit"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>
                            @endif
                            <button 
                                wire:click="removeTask({{ $task->id }})" 
                                class="text-red-500 hover:text-red-700"
                                title="Delete"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Previous pending tasks -->
        @if($pendingTasks->isNotEmpty())
            <div class="mt-8">
                <h4 class="font-medium text-gray-900 mb-3">Previous Pending Tasks</h4>
                <div class="space-y-2">
                    @foreach($pendingTasks as $task)
                        <div class="flex items-center space-x-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex-1">
                                <p class="text-gray-800">{{ $task->description }}</p>
                                <div class="mt-1 space-y-1">
                                    @foreach($task->histories->take(3) as $history)
                                        <p class="text-xs text-gray-500">
                                            {{ $history->changed_at->format('M d, Y h:ia') }} - 
                                            <span class="font-medium {{ $history->status === 'done' ? 'text-green-600' : 'text-orange-600' }}">
                                                {{ ucfirst($history->status) }}
                                            </span>
                                            ({{ $history->journal->date->format('M d, Y') }})
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="moveTaskToToday({{ $task->id }})"
                                    class="text-blue-600 hover:text-blue-700 text-sm"
                                >
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Move to Today
                                </button>
                                <select 
                                    wire:model.live="taskStatuses.{{ $task->id }}"
                                    wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="rounded-md border-gray-300 text-sm"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Modal footer -->
    <div class="mt-6 pt-4 border-t flex justify-between items-center">
        <div class="flex-1"></div>
        <button 
            wire:click="$dispatch('closeModal')"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
        >
            Close
        </button>
    </div>
</div>
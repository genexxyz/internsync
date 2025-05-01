<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto p-6">
    <!-- Modal header -->
    <div class="flex justify-between items-center pb-4 border-b">
        <h3 class="text-xl font-semibold text-gray-900">Daily Tasks Journal</h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Main content with scrollable area -->

    <div class="py-6">
        @if(!$currentJournal)
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Journal Title</label>
                    <div class="flex space-x-2">
                        <input 
                            type="text"
                            wire:model="journalText" 
                            class="flex-1 rounded-md border-gray-300"
                            placeholder="Enter a title for today's journal..."
                        >
                        <button 
                            wire:click="createJournal"
                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Create Journal
                        </button>
                    </div>
                </div>
            </div>
        @else
        <!-- Journal Title -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Today's Title</label>
            <div class="flex items-center space-x-2">
                @if($editingJournalTitle)
                    <input 
                        type="text"
                        wire:model="journalText" 
                        class="flex-1 rounded-md border-gray-300"
                        wire:keydown.enter="saveJournalText"
                        wire:keydown.escape="cancelEditingJournalTitle"
                        autofocus
                    >
                    <button 
                        wire:click="saveJournalText" 
                        class="text-green-600 hover:text-green-700 px-3 text-lg"
                        title="Save"
                    >
                        <i class="fas fa-check"></i>
                    </button>
                    <button 
                        wire:click="cancelEditingJournalTitle"
                        class="text-gray-400 hover:text-gray-500 text-lg"
                        title="Cancel"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                @else
                    <h2 class="text-lg font-semibold text-gray-800 flex-1">{{ $journalText }}</h2>
                    <button 
                        wire:click="startEditingJournalTitle"
                        class="text-blue-600 hover:text-blue-700"
                        title="Edit Title"
                    >
                        <i class="fas fa-pen"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Add new task -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Add Task</label>
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    wire:model.defer="newTask" 
                    wire:keydown.enter="addTask"
                    class="flex-1 rounded-md border-gray-300" 
                    placeholder="{{ empty($currentJournal->text) ? 'Please add a title first' : 'Add a task...' }}"
                    @if(empty($currentJournal->text)) disabled @endif
                >
                <button 
                    wire:click="addTask" 
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed"
                    @if(empty($currentJournal->text)) disabled @endif
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add
                </button>
            </div>
            @if(empty($currentJournal->text))
                <p class="mt-2 text-sm text-gray-500">Please add a title before adding tasks.</p>
            @endif
        </div>

        <!-- Tasks List -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-3">Tasks</h4>
            <ul class="space-y-2 list-disc list-inside ml-4">
                @forelse($currentJournal->tasks as $task)
                    <li class="group flex items-center justify-between bg-white rounded-md p-2 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="inline-flex items-center w-full">
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
                        </div>
                    </li>
                @empty
                    <p class="text-sm text-gray-500 italic">No tasks added yet.</p>
                @endforelse
            </ul>
        </div>
        @endif
    </div>

    <!-- Modal footer -->
    <div class="mt-6 pt-4 border-t flex justify-between items-center">
        <button 
            wire:click="discard"
            wire:confirm="Are you sure you want to discard this journal and all its tasks?"
            class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700"
        >
            <i class="fas fa-trash mr-2"></i>
            Discard Journal
        </button>
        <button 
            wire:click="taskJournalClose()"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
        >
            Close
        </button>
    </div>
</div>
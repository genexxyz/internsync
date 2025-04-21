<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">
        Reopen Journal Entry
    </h2>

    <div class="space-y-4">
        <!-- Date Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Select Date to Reopen
            </label>
            <input 
                type="date"
                wire:model="date"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                max="{{ now()->subDay()->format('Y-m-d') }}"
            >
            @error('date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Student Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Select Student
            </label>
            <select 
                wire:model="selectedStudent"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">Select a student...</option>
                @foreach($students as $student)
                    <option value="{{ $student['id'] }}">
                        {{ $student['name'] }}
                    </option>
                @endforeach
            </select>
            @error('selectedStudent')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Message for Student (Optional)
            </label>
            <textarea
                wire:model="message"
                rows="3"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                placeholder="Enter a message explaining why the entry is being reopened..."
            ></textarea>
        </div>

        <div class="text-sm text-gray-500 italic">
            Note: Entry will be reopened for 24 hours from the time of approval.
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-6 flex justify-end space-x-3">
        <button
            wire:click="$dispatch('closeModal')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500"
        >
            Cancel
        </button>
        <button
            wire:click="reopen"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Reopen Entry</span>
            <span wire:loading>Processing...</span>
        </button>
    </div>
</div>
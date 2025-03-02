<div x-data="{ showModal: false }">
    <!-- Trigger Button or Action -->
    <button @click="showModal = true" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
        Cancel Action
    </button>

    <!-- Modal -->
    <div 
        x-show="showModal"
        x-transition
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @keydown.escape.window="showModal = false"
    >
        <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
            <!-- Modal Header -->
            <h3 class="text-xl font-bold text-center mb-4">Confirm Cancellation</h3>

            <!-- Modal Content -->
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to cancel the action? All unsaved changes will be lost.</p>

            <!-- Modal Buttons -->
            <div class="flex justify-between">
                <!-- Cancel Button -->
                <button 
                    @click="showModal = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                >
                    No, Keep Changes
                </button>
                <!-- Confirm Cancel Button -->
                <button 
                    @click="showModal = false; $dispatch('action-canceled')" 
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                >
                    Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>

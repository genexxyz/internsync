<div class="bg-white rounded-lg">
    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Add New Section</h2>
            <button wire:click="$dispatch('closeModal')"
                class="py-2 px-4 hover:bg-gray-100 rounded-full transition-colors duration-200 focus:outline-none">
                <i class="fa fa-xmark text-gray-500 text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Modal Content -->
    
        <form wire:submit.prevent="saveSection">
            <div class="px-6 py-4 space-y-6">
            <!-- Year Level Input -->
            <div class="space-y-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa fa-list-ol text-gray-400"></i>
                    </div>
                    <x-text-input id="year_level" type="number"
                        class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        wire:model="year_level" min="1" max="5" placeholder="Enter year level (1-5)"
                        icon="fa fa-list-ol" required />
                </div>
                <x-input-error :messages="$errors->get('year_level')" class="mt-1" />
            </div>

            <!-- Number of Sections Input -->
            <div class="space-y-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa fa-layer-group text-gray-400"></i>
                    </div>
                    <x-text-input id="sections" type="number"
                        class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        wire:model="sections" min="1" placeholder="Enter number of sections" icon="fa fa-layer-group"
                        required />
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Sections will be automatically named (A, B, C, etc.)
                </p>
                <x-input-error :messages="$errors->get('sections')" class="mt-1" />
            </div>
        </div>
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <button wire:click="$dispatch('closeModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Sections
                    </button>
                </div>
            </div>
        </form>
    


</div>
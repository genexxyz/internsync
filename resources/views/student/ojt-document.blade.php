<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('student.document'), 'label' => 'OJT Document']]" />

    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">OJT Documents</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Acceptance Letter -->
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fa fa-file-lines text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Acceptance Letter</h3>
                        <p class="text-sm text-gray-500">Company acceptance document</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button 
                    onclick="Livewire.dispatch('openModal', { component: 'student.acceptance-letter-form'})"
    class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
    <span class="text-sm font-medium">
        {{ $acceptance_letter && $acceptance_letter->is_generated ? 'Upload Signed Letter' : 'Generate Letter' }}
    </span>
    <i class="fa {{ $acceptance_letter && $acceptance_letter->is_generated ? 'fa-upload' : 'fa-arrow-right' }} text-sm"></i>
</button>
                </div>
            </div>

            <!-- Memorandum of Agreement -->
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fa fa-file-contract text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Memorandum of Agreement</h3>
                        <p class="text-sm text-gray-500">Training agreement document</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button
                        class="w-full py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-200 flex items-center justify-center gap-2">
                        <span class="text-sm font-medium">View</span>
                        <i class="fa fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Endorsement Letter -->
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fa fa-file-signature text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Endorsement Letter</h3>
                        <p class="text-sm text-gray-500">School endorsement document</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button
                        class="w-full py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors duration-200 flex items-center justify-center gap-2">
                        <span class="text-sm font-medium">View</span>
                        <i class="fa fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
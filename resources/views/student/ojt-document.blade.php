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

                    @if($acceptance_letter && !empty($acceptance_letter->signed_path))
                    <button @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
                        detail: {
                            url: '{{ Storage::url($acceptance_letter->signed_path) }}'
                        }
                    }))" class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
                                            <i class="fa fa-eye text-sm"></i>
                                            <span class="text-sm font-medium">View</span>
                                        </button>
        @else
        <button 
        onclick="Livewire.dispatch('openModal', { component: 'student.acceptance-letter-form'})"
class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
<span class="text-sm font-medium">

{{ $acceptance_letter && $acceptance_letter->is_generated ? 'Upload Signed Letter' : 'Generate Letter' }}
</span>
<i class="fa {{ $acceptance_letter && $acceptance_letter->is_generated ? 'fa-upload' : 'fa-arrow-right' }} text-sm"></i>
</button>
        @endif
                    
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
                        <i class="fa fa-eye text-sm"></i>
                        <span class="text-sm font-medium">View</span>
                        
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
                        <i class="fa fa-eye text-sm"></i>
                        <span class="text-sm font-medium">View</span>
                        
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this div at the bottom of your layout file or on pages where you need the PDF viewer -->
    <div x-data="{
        showPdfViewer: false,
        pdfUrl: '',
        init() {
            window.addEventListener('open-pdf-viewer', (e) => {
                this.pdfUrl = e.detail.url;
                this.showPdfViewer = true;
            });
        }
    }" x-show="showPdfViewer" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    
            <!-- Backdrop overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
            <!-- Modal container -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full">
                    <!-- Close button -->
                    <div class="absolute top-4 right-4">
                        <button @click="showPdfViewer = false"
                            class="py-2 px-4 bg-gray-100 hover:bg-gray-300 rounded-full transition-colors duration-200">
                            <i class="fa fa-times text-gray-500"></i>
                        </button>
                    </div>
    
                    <!-- PDF iframe container -->
                    <div class="p-1">
                        <iframe :src="pdfUrl" class="w-full h-[85vh] rounded-lg" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
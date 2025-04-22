<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('student.document'), 'label' => 'OJT Document']]" />

    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">OJT Documents</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Acceptance Letter -->
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
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
                    @if($acceptance_letter && $acceptance_letter->signed_path)
                        <!-- If letter is signed, show view button -->
                        <button @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
                            detail: { url: '{{ Storage::url($acceptance_letter->signed_path) }}' }
                        }))" 
                        class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fa fa-eye text-sm"></i>
                            <span class="text-sm font-medium">View Signed Letter</span>
                        </button>
                    @elseif($acceptance_letter && $acceptance_letter->is_generated)
                        <!-- If letter is generated but not signed -->
                        <button 
                            onclick="Livewire.dispatch('openModal', { component: 'student.acceptance-letter-form'})"
                            class="w-full py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-clock text-sm mr-2"></i>
                            <span class="text-sm font-medium">Check Status</span>
                        </button>
                    @else
                        <!-- If letter is not generated yet -->
                        <button 
                            onclick="Livewire.dispatch('openModal', { component: 'student.acceptance-letter-form'})"
                            class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
                            <span class="text-sm font-medium">Generate Letter</span>
                            <i class="fa fa-arrow-right text-sm"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Memorandum of Agreement -->
            <!-- Memorandum of Agreement Card -->
<div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
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
        @if($moaRequest)
            @php
                $statusConfig = [
                    'requested' => [
                        'bg' => 'bg-blue-50',
                        'text' => 'text-blue-600',
                        'icon' => 'fa-clock',
                        'label' => 'Request Pending'
                    ],
                    'for_pickup' => [
                        'bg' => 'bg-yellow-50',
                        'text' => 'text-yellow-600',
                        'icon' => 'fa-box',
                        'label' => 'Ready for Pickup'
                    ],
                    'picked_up' => [
                        'bg' => 'bg-purple-50',
                        'text' => 'text-purple-600',
                        'icon' => 'fa-truck',
                        'label' => 'Picked Up'
                    ],
                    'received_by_company' => [
                        'bg' => 'bg-green-50',
                        'text' => 'text-green-600',
                        'icon' => 'fa-check-circle',
                        'label' => 'Received by Company'
                    ]
                ];
                $status = $statusConfig[$moaRequest->status] ?? [
                    'bg' => 'bg-gray-50',
                    'text' => 'text-gray-600',
                    'icon' => 'fa-info-circle',
                    'label' => 'Unknown Status'
                ];
            @endphp
            
            <div class="w-full py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg flex items-center justify-center gap-2">
                <i class="fas {{ $status['icon'] }} text-sm"></i>
                <span class="text-sm font-medium">{{ $status['label'] }}</span>
            </div>
            @if($moaRequest->status === 'for_pickup')
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'student.moa-request-form'})"
                    class="w-full mt-2 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-download text-sm"></i>
                    <span class="text-sm font-medium">Pickup Status</span>
                </button>
            @endif
        @else
            <button
                onclick="Livewire.dispatch('openModal', { component: 'student.moa-request-form'})"
                class="w-full py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-200 flex items-center justify-center gap-2">
                <i class="fa fa-plus text-sm"></i>
                <span class="text-sm font-medium">Request MOA</span>
            </button>
        @endif

        @if($moaRequest && $moaRequest->admin_remarks)
            <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600">
                    <i class="fas fa-comment-alt mr-1"></i>
                    {{ $moaRequest->admin_remarks }}
                </p>
            </div>
        @endif
    </div>
</div>

            <!-- Endorsement Letter -->
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
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
                    @if (session('error'))
                        <div class="mb-4 p-2 bg-red-50 text-red-600 rounded-lg text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
            
                    @if (session('success'))
                        <div class="mb-4 p-2 bg-green-50 text-green-600 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
            
                    @if($endorsementRequest)
                        @php
                            $statusConfig = [
                                'requested' => [
                                    'bg' => 'bg-blue-50',
                                    'text' => 'text-blue-600',
                                    'icon' => 'fa-clock',
                                    'label' => 'Request Pending'
                                ],
                                'for_pickup' => [
                                    'bg' => 'bg-yellow-50',
                                    'text' => 'text-yellow-600',
                                    'icon' => 'fa-box',
                                    'label' => 'Ready for Pickup'
                                ],
                                'picked_up' => [
                                    'bg' => 'bg-green-50',
                                    'text' => 'text-green-600',
                                    'icon' => 'fa-check-circle',
                                    'label' => 'Picked Up'
                                ]
                            ];
                            $status = $statusConfig[$endorsementRequest->status] ?? [
                                'bg' => 'bg-gray-50',
                                'text' => 'text-gray-600',
                                'icon' => 'fa-info-circle',
                                'label' => 'Unknown Status'
                            ];
                        @endphp
                        
                        <div class="w-full py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg flex items-center justify-center gap-2">
                            <i class="fas {{ $status['icon'] }} text-sm"></i>
                            <span class="text-sm font-medium">{{ $status['label'] }}</span>
                        </div>
            
                        @if($endorsementRequest->picked_up_at)
                            <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-user mr-1"></i>
                                    Received by: {{ $endorsementRequest->received_by }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Picked up on: {{ $endorsementRequest->picked_up_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                        @endif
            
                        @if($endorsementRequest->admin_remarks)
                            <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-comment-alt mr-1"></i>
                                    {{ $endorsementRequest->admin_remarks }}
                                </p>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('student.request-endorsement') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fa fa-plus text-sm"></i>
                                <span class="text-sm font-medium">Request Letter</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <!-- Evaluation Report -->
    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 p-3 rounded-lg">
                <i class="fa fa-clipboard-list text-2xl text-indigo-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Evaluation Report</h3>
                <p class="text-sm text-gray-500">Performance evaluation</p>
            </div>
        </div>
        <div class="mt-4">
            @if($student->deployment?->evaluation)
                <button 
                    @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
                        detail: { url: '{{ route('student.evaluation.view', ['evaluation' => $student->deployment->evaluation->id]) }}' }
                    }))"
                    class="w-full py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors duration-200 flex items-center justify-center gap-2"
                >
                    <i class="fa fa-eye text-sm"></i>
                    <span class="text-sm font-medium">View Evaluation</span>
                </button>

                @if($student->deployment->evaluation->created_at)
                    <div class="mt-2 p-2 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-calendar mr-1"></i>
                            Evaluated on: {{ $student->deployment->evaluation->created_at->format('M d, Y') }}
                        </p>
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-star mr-1"></i>
                            Score: {{ $student->deployment->evaluation->total_score }}/100
                        </p>
                    </div>
                @endif
            @else
                <div class="w-full py-2 bg-gray-50 text-gray-400 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-clock text-sm"></i>
                    <span class="text-sm font-medium">Pending Evaluation</span>
                </div>
            @endif
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
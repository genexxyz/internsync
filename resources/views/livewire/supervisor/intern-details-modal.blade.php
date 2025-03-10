<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/supervisor/intern-details-modal.blade.php -->
<div class="bg-white rounded-lg w-full max-w-3xl shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Intern Details</h2>
            <button 
                wire:click="$dispatch('closeModal')" 
                class="p-2 rounded-full hover:bg-gray-200 transition-colors duration-200"
            >
                <i class="fa fa-xmark text-gray-500 text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Profile Section -->
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 mb-6">
            <img 
                src="/images/default_avatar.jpg" 
                class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm" 
                alt="Profile Picture"
            >
            
            <div class="flex-1 text-center lg:text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $deployment->student->user->first_name ?? $deployment->student->first_name }}
                    {{ $deployment->student->user->last_name ?? $deployment->student->last_name }}
                </h3>
                
                <p class="text-gray-600 mb-2">
                    <i class="fa fa-id-card text-gray-400 mr-2"></i>
                    Student ID: {{ $deployment->student->student_id }}
                </p>

                <p class="text-gray-600">
                    <i class="fa fa-school text-gray-400 mr-2"></i>
                    {{ $deployment->student->yearSection->course->course_code }} 
                    {{ $deployment->student->yearSection->year_level }}{{ $deployment->student->yearSection->class_section }}
                </p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Contact Information</h4>
                <div class="space-y-2">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-envelope w-5"></i>
                        <span>{{ $deployment->student->user->email }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-phone w-5"></i>
                        <span>{{ $deployment->student->contact ?? 'Not provided' }}</span>
                    </p>
                </div>
            </div>

            <!-- Training Progress -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Training Progress</h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-clock w-5"></i>
                        <span>{{ $totalHours }} hours {{ $totalMinutes }} minutes rendered</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-calendar w-5"></i>
                        <span>Started: {{ $deployment->starting_date ? $deployment->starting_date->format('M d, Y') : 'Not set' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa fa-chart-line w-5"></i>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                            $deployment->status === 'ongoing' ? 'bg-blue-100 text-blue-800' :
                            ($deployment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') 
                        }}">
                            {{ ucfirst($deployment->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acceptance Letter -->
        @if($deployment->student->acceptance_letter)
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Acceptance Letter</h4>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa fa-file-lines text-blue-500 text-xl"></i>
                        <span class="text-gray-600">Signed Acceptance Letter</span>
                    </div>
                    @if($deployment->student->acceptance_letter->signed_path)
                        <button 
                            @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
                                detail: { url: '{{ Storage::url($deployment->student->acceptance_letter->signed_path) }}' }
                            }))"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200"
                        >
                            <i class="fa fa-eye mr-2"></i>
                            View Letter
                        </button>
                    @else
                        <span class="text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-clock mr-1"></i> Pending Signature
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

<!-- PDF Viewer Component -->
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

</div>
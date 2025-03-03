<div class="bg-white rounded-lg w-full max-w-7xl shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Student Profile</h2>
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
                <div class="flex items-center justify-center lg:justify-start gap-2 mb-2">
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $student->first_name ?? '' }}
                        {{ $student->middle_name ?? '' }}
                        {{ $student->last_name ?? '' }}
                        {{ $student->suffix ?? '' }}
                    </h3>
                    @if ($student->user->is_verified)
                        <i class="fa fa-circle-check text-green-500 text-xl" title="Verified"></i>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-2">
                    <i class="fa fa-id-card text-gray-400 mr-2"></i>
                    Student ID: {{$student->student_id}}
                </p>

                <p class="text-gray-600 mb-2">
                    <i class="fa fa-school text-gray-400 mr-2"></i>
                    {{ $student->yearSection->course->course_code . ' ' . $student->yearSection->year_level . $student->yearSection->class_section }}
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
                        <span>{{ $student->user->email ?? '' }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-phone w-5"></i>
                        <span>{{ $student->contact ?? '' }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-location-dot w-5"></i>
                        <span>{{ $student->address ?? '' }}</span>
                    </p>
                </div>
            </div>

            <!-- Deployment Details -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Deployment Details</h4>
                @if (!empty($student->deployment->company_id))
                    <div class="space-y-2">
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-building w-5"></i>
                            <span>{{ $student->deployment->company->company_name ?? '' }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-user-tie w-5"></i>
                            <span>{{ $student->deployment->supervisor->first_name ?? 'N/A' }} {{ $student->deployment->supervisor->last_name ?? '' }}</span>
                        </p>
                    </div>
                @else
                    <button
                        onclick="Livewire.dispatch('openModal', { 
                                    component: 'instructor.assign-modal', 
                                    arguments: { student: {{ $student->id }} } 
                                })"
                        class="bg-blue-500 px-4 py-2 rounded uppercase text-white font-semibold hover:bg-blue-600">
                        Assign
                    </button>
                @endif
            </div>
        </div>
    </div>
    <!-- Supporting Documents -->
    <div class="mb-6">
        <h4 class="font-semibold text-gray-800 mb-4">Supporting Documents</h4>
        @if($student->supporting_doc)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa fa-file-image text-red-500 text-xl"></i>
                        <span class="text-gray-600">{{ basename($student->supporting_doc) }}</span>
                    </div>
                    <button 
                    x-data
                    @click="$dispatch('open-preview', { url: '{{ Storage::url($student->supporting_doc) }}' })"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200"
                >
                    <i class="fa fa-eye mr-2"></i>
                    View Document
                </button>
                </div>
            </div>
        @else
            <p class="text-gray-500">No supporting documents available</p>
        @endif
    </div>
    <!-- Footer -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-end gap-3">
            @if (!$student->user->is_verified)
                <button 
                    wire:click="verifyStudent"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                >
                    <i class="fa fa-check mr-2"></i>
                    Verify Student
                </button>
            @endif
            <button 
                wire:click="deleteStudent"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200"
            >
                <i class="fa fa-trash mr-2"></i>
                Delete Student
            </button>
        </div>
    </div>


     <!-- Document Preview Modal -->
     <!-- Image Preview Modal -->
<div x-data="{ 
    showPreview: false,
    previewUrl: '',
    init() {
        window.addEventListener('open-preview', (e) => {
            this.previewUrl = e.detail.url;
            this.showPreview = true;
        });
    }
}" x-show="showPreview" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal container -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full">
            <!-- Close button -->
            <div class="absolute top-4 right-4 z-10">
                <button @click="showPreview = false"
                    class="p-2 bg-white hover:bg-gray-100 rounded-full transition-colors duration-200 shadow-md">
                    <i class="fa fa-times text-gray-500"></i>
                </button>
            </div>
            
            <!-- Image viewer -->
            <div class="p-1">
                <div class="flex items-center justify-center bg-gray-100 w-full h-[85vh] rounded-lg overflow-auto">
                    <img :src="previewUrl" class="max-w-full max-h-full object-contain" alt="Document preview">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
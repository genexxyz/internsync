<div class="bg-white rounded-lg w-full shadow-lg max-h-[90vh] overflow-y-auto">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 sticky top-0 z-10">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Student Information</h2>
            <div class="flex items-center gap-2">
                @if($canEdit)
                    <button 
                        wire:click="disableStudent"
                        wire:confirm="Are you sure you want to disable this student's account?"
                        class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"
                        title="Disable Student Account"
                    >
                        <i class="fa fa-ban"></i>
                    </button>
                @endif
                <button 
                    wire:click="$dispatch('closeModal')"
                    class="p-2 rounded-full hover:bg-gray-200 transition-colors"
                >
                    <i class="fa fa-xmark text-gray-500 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    @if($student->deployment)
        <div class="px-6 py-4 bg-white border-b">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">Training Progress</h3>
                    <span class="text-sm font-medium text-gray-600">
                        {{ $totalHours }} hours {{ $totalMinutes }} minutes completed
                    </span>
                </div>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full 
                                {{ $progressPercentage >= 100 ? 'text-green-600 bg-green-100' : 'text-blue-600 bg-blue-100' }}">
                                {{ $progressPercentage }}%
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-gray-600">
                                Target: {{ $student->deployment->custom_hours ?? $student->section->course->required_hours }} hours
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-gray-200">
                        <div style="width:{{ min($progressPercentage, 100) }}%" 
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center 
                            {{ $progressPercentage >= 100 ? 'bg-green-500' : 'bg-blue-500' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <img 
                                src="{{ $student->image ? Storage::url($student->image) : '/images/default_avatar.jpg' }}"
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm" 
                                alt="{{ $student->first_name }}'s Profile Picture"
                            >
                            @if ($student->user->status === 0)
                                <span class="absolute -top-1 -right-1 px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full">
                                    Disabled
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Basic Information</h4>
                        <div class="space-y-2">
                            <p class="flex items-center gap-2 text-gray-600">
                                <i class="fa fa-id-card w-5"></i>
                                <span>{{ $student->student_id }}</span>
                            </p>
                            <p class="flex items-center gap-2 text-gray-600">
                                <i class="fa fa-graduation-cap w-5"></i>
                                <span>{{ $student->section->course->course_code }}</span>
                            </p>
                            <p class="flex items-center gap-2 text-gray-600">
                                <i class="fa fa-users w-5"></i>
                                <span>{{ $student->section->year_level }}-{{ $student->section->class_section }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <nav class="-mb-px flex space-x-6 border-b border-gray-200 mb-6">
                    <button wire:click="setTab('info')"
                        class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'info' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fa fa-user mr-2"></i>Details
                    </button>
                    <button wire:click="setTab('documents')"
                        class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'documents' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fa fa-file mr-2"></i>Documents
                    </button>
                    <button wire:click="setTab('reports')"
                        class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'reports' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fa fa-chart-bar mr-2"></i>Reports
                    </button>
                </nav>

                @if($selectedTab === 'info')
                    @include('livewire.student-modal.tabs.info')
                @elseif($selectedTab === 'documents')
                    @include('livewire.student-modal.tabs.documents')
                @elseif($selectedTab === 'reports')
                    @include('livewire.student-modal.tabs.reports')
                @endif
            </div>
        </div>
    </div>
</div>

{{-- <div x-data="{ 
    showPreview: false,
    previewUrl: '',
    init() {
        window.addEventListener('open-preview', (e) => {
            this.previewUrl = e.detail.url;
            this.showPreview = true;
        });
    }
}" x-show="showPreview" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full">
            <button @click="showPreview = false"
                class="absolute top-4 right-4 p-2 bg-white hover:bg-gray-100 rounded-full shadow-md">
                <i class="fa fa-times text-gray-500"></i>
            </button>
            <div class="p-1">
                <div class="flex items-center justify-center bg-gray-100 w-full h-[85vh] rounded-lg overflow-auto">
                    <img :src="previewUrl" class="max-w-full max-h-full object-contain" alt="Document preview">
                </div>
            </div>
        </div>
    </div>
</div> --}}
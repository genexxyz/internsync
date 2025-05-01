<div class="bg-white rounded-lg w-full max-w-7xl shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Instructor Profile</h2>
            <button wire:click="$dispatch('closeModal')"
                class="p-2 rounded-full hover:bg-gray-200 transition-colors duration-200">
                <i class="fa fa-xmark text-gray-500 text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Profile Section -->
        <div class="flex flex-col items-center lg:flex-row lg:items-start gap-6 mb-6">
            <!-- Left Column -->
            <div class="lg:w-1/4">
                <div class="relative inline-block">
                    <img 
                        src="{{ $user->image ? Storage::url($user->image) : '/images/default_avatar.jpg' }}"
                        class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm" 
                        alt="{{ $user->first_name }}'s Profile Picture"
                    >
                    @if ($user->user && $user->user->status === 0)
                        <span class="absolute -top-1 -right-1 px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full">
                            Disabled
                        </span>
                    @endif
                </div>
                
                <!-- Basic Info -->
                <div class="mt-6 space-y-3">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-id-card w-5 text-gray-400"></i>
                        @if(!$isEditing)
                            <span>{{ $user->instructor_id }}</span>
                        @else
                            <input type="text" 
                                wire:model="editableData.instructor_id" 
                                placeholder="Instructor ID"
                                class="w-full border-gray-300 rounded-lg">
                        @endif
                    </p>
                </div>
            </div>
    
            <!-- Right Column -->
            <div class="flex-1">
                <!-- Name Section -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex-1">
                        @if(!$isEditing)
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">
                                    {{ $user->first_name }}
                                    {{ $user->middle_name }}
                                    {{ $user->last_name }}
                                    {{ $user->suffix }}
                                </h3>
                                @if($user->instructorCourses->isNotEmpty())
    <p class="mt-1 text-sm font-medium text-primary">
        <i class="fas fa-user-tie mr-1"></i>
        Program Head of:
        <span class="flex flex-wrap gap-2 mt-1">
            @foreach($user->instructorCourses as $programHead)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                    {{ $programHead->course->course_code }}
                </span>
            @endforeach
        </span>
    </p>
@endif
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" wire:model="editableData.first_name" 
                                            class="w-full border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                        <input type="text" wire:model="editableData.middle_name" 
                                            class="w-full border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" wire:model="editableData.last_name" 
                                            class="w-full border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                                        <input type="text" wire:model="editableData.suffix" 
                                            class="w-full border-gray-300 rounded-lg">
                                    </div>
                                </div>
                                @if($user->instructorCourse)
                                    <p class="text-sm font-medium text-primary flex items-center gap-2">
                                        <i class="fas fa-user-tie"></i>
                                        Program Head - {{ $user->instructorCourse->course->course_code }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- @if ($user->user->is_verified)
                            <i class="fa fa-circle-check text-green-500 text-xl" title="Verified"></i>
                        @endif --}}
                        <button wire:click="toggleEdit" class="p-2 hover:bg-gray-100 rounded-full">
                            <i class="fa {{ $isEditing ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                        </button>
                    </div>
                </div>
    
                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Contact Information</h4>
                    <div class="space-y-3">
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-envelope w-5"></i>
                            <span>{{ $user->user->email }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-phone w-5"></i>
                            @if(!$isEditing)
                                <span>{{ $user->contact }}</span>
                            @else
                                <input type="text" 
                                    wire:model="editableData.contact" 
                                    placeholder="Contact Number"
                                    class="w-full border-gray-300 rounded-lg">
                            @endif
                        </p>
                    </div>
                </div>
    
                <!-- Save Changes Button -->
                @if($isEditing)
                    <div class="flex justify-end">
                        <button wire:click="saveChanges"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            <i class="fa fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <!-- Info Grid -->
        <div class=" mb-6">
            {{-- <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Contact Information</h4>
                <div class="space-y-2">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-envelope w-5"></i>
                        <span>{{ $instructor->user->email ?? '' }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-phone w-5"></i>
                        <span>{{ $instructor->contact ?? '' }}</span>
                    </p>
                </div>
            </div> --}}

            <!-- Sections -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Handled Sections</h4>
                @if ($instructor->sections->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach ($instructor->sections as $section)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                {{ optional($section->course)->course_code ?: 'N/A' }}
                                {{ $section->year_level }}{{ $section->class_section }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No sections assigned</p>
                @endif
            </div>
        </div>

        {{-- <!-- Supporting Documents -->
        <div class="mb-6">
            <h4 class="font-semibold text-gray-800 mb-4">Supporting Documents</h4>
            @if($instructor->supporting_doc)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fa fa-file-image text-red-500 text-xl"></i>
                            <span class="text-gray-600">{{ basename($instructor->supporting_doc) }}</span>
                        </div>
                        <button 
                    x-data
                    @click="$dispatch('open-preview', { url: '{{ Storage::url($instructor->supporting_doc) }}' })"
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
        </div> --}}
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-end gap-3">
            @if($user->user && $user->user->status === 1)
                <button 
                    wire:click="disableInstructor"
                    wire:confirm="Are you sure you want to disable this instructor's account?"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200"
                >
                    <i class="fa fa-ban mr-2"></i>
                    Disable Account
                </button>
            @endif
        </div>
    </div>
    <!-- Document Preview Modal -->
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
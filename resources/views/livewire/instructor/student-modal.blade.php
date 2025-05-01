<div class="bg-white rounded-lg w-full shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Student Profile</h2>
            <div class="flex items-center gap-2">
                {{-- @if(!$student->user->is_verified)
                <button wire:click="verifyStudent"
                    wire:confirm="Are you sure you want to verify this student?"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fa fa-check mr-2"></i>
                Verify Student
            </button>
                @endif --}}
                {{-- <button 
                    wire:click="deleteStudent"
                    wire:confirm="Are you sure you want to delete this student? This action cannot be undone."
                    class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"
                    title="Delete Student"
                >
                    <i class="fa fa-trash"></i>
                </button> --}}
                <button 
                    wire:click="$dispatch('closeModal')"
                    class="p-2 rounded-full hover:bg-gray-200 transition-colors"
                >
                    <i class="fa fa-xmark text-gray-500 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Profile Section -->
        <div class="flex flex-col items-center lg:flex-row lg:items-start gap-6 mb-2">
            <!-- Left Column -->
            <div class="lg:w-1/4">
                <img src="{{ $student->image ? Storage::url($student->image) : '/images/default_avatar.jpg' }}"
                    class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm mx-auto lg:mx-0" 
                    alt="Profile Picture">
                
                <!-- Basic Info -->
                <div class="mt-6 space-y-3">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-id-card w-5 text-gray-400"></i>
                        @if(!$isEditing)
                            <span>{{ $student->student_id }}</span>
                        @else
                            <input type="text" 
                                wire:model="editableData.student_id" 
                                placeholder="Student ID"
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
                                    {{ $student->first_name }}
                                    {{ $student->middle_name }}
                                    {{ $student->last_name }}
                                    {{ $student->suffix }}
                                </h3>
                                <p class="mt-1 text-sm font-medium text-primary">
                                    {{ $student->section->course->course_code }} 
                                    {{ $student->section->year_level }}-{{ $student->section->class_section }}
                                </p>
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
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- @if ($student->user->is_verified)
                            <i class="fa fa-circle-check text-green-500 text-xl" title="Verified"></i>
                        @endif --}}
                        {{-- <button wire:click="toggleEdit" class="p-2 hover:bg-gray-100 rounded-full">
                            <i class="fa {{ $isEditing ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                        </button> --}}
                    </div>
                </div>
        
                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Contact Information</h4>
                    <div class="space-y-3">
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-envelope w-5"></i>
                            <span>{{ $student->user->email }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-phone w-5"></i>
                            @if(!$isEditing)
                                <span>{{ $student->contact }}</span>
                            @else
                                <input type="text" 
                                    wire:model="editableData.contact" 
                                    placeholder="Contact Number"
                                    class="w-full border-gray-300 rounded-lg">
                            @endif
                        </p>
                        <p class="flex items-center gap-2 text-gray-600">
                            <i class="fa fa-location-dot w-5"></i>
                            @if(!$isEditing)
                                <span>{{ $student->address }}</span>
                            @else
                                <input type="text" 
                                    wire:model="editableData.address" 
                                    placeholder="Address"
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

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-6">
                <button wire:click="setTab('info')"
                    class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'info' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                    Basic Information
                </button>
                <button wire:click="setTab('documents')"
                    class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'documents' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                    Documents
                </button>
                <button wire:click="setTab('reports')"
                    class="px-1 py-4 text-sm font-medium {{ $selectedTab === 'reports' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                    Weekly Reports
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div>
            @if($selectedTab === 'info')
               
                    

                    <!-- Deployment Details -->
                    {{-- <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-4">Deployment Details</h4>
                        @if($student->deployment)
                            <div class="space-y-2">
                                <p class="flex items-center gap-2 text-gray-600">
                                    <i class="fa fa-building w-5"></i>
                                    <span>{{ $student->deployment->company->company_name }}</span>
                                </p>
                                <p class="flex items-center gap-2 text-gray-600">
                                    <i class="fa fa-briefcase w-5"></i>
                                    <span>{{ $student->deployment->department->department_name }}</span>
                                </p>
                                <p class="flex items-center gap-2 text-gray-600">
                                    <i class="fa fa-user-tie w-5"></i>
                                    <span>{{ $student->deployment->supervisor->getFullNameAttribute() ?? 'No Supervisor'}}</span>
                                </p>
                                @if ($student->deployment->starting_date != null && $student->deployment->ending_date != null)
                                <p class="flex items-center gap-2 text-gray-600">
                                    <i class="fa fa-calendar w-5"></i>
                                    <span>{{ $student->deployment->starting_date->format('M d, Y') ?? ''}} - 
                                          {{ $student->deployment->ending_date->format('M d, Y') ?? ''}}</span>
                                </p>
                                
                                @endif
                                
                            </div>
                        @else
                            <p class="text-gray-500 italic">Not yet deployed</p>
                        @endif
                    </div> --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-800">Deployment Details</h4>
                            {{-- @if($student->deployment)
                                <button wire:click="toggleDeploymentEdit" class="p-2 hover:bg-gray-100 rounded-full">
                                    <i class="fa {{ $isEditingDeployment ? 'fa-times' : 'fa-pen' }} text-gray-500"></i>
                                </button>
                            @endif --}}
                        </div>
                        @if($student->deployment)
                            <div class="space-y-4">
                                <!-- Existing deployment details -->
                                <div class="space-y-2">
                                    <!-- ...existing fields... -->
                                    <p class="flex items-center gap-2 text-gray-600">
                                        <i class="fa fa-building w-5"></i>
                                        <span>{{ $student->deployment->company->company_name ?? '-' }}</span>
                                    </p>
                                    <p class="flex items-center gap-2 text-gray-600">
                                        <i class="fa fa-briefcase w-5"></i>
                                        <span>{{ $student->deployment->department->department_name ?? '-' }}</span>
                                    </p>
                                    <p class="flex items-center gap-2 text-gray-600">
                                        <i class="fa fa-user-tie w-5"></i>
                                        @if ($student->deployment->supervisor)
                                            <span>{{ $student->deployment->supervisor->getFullNameAttribute() ?? 'No Supervisor'}}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </p>
                                    @if ($student->deployment->starting_date != null && $student->deployment->ending_date != null)
                                    <p class="flex items-center gap-2 text-gray-600">
                                        <i class="fa fa-calendar w-5"></i>
                                        <span>{{ $student->deployment->starting_date->format('M d, Y') ?? ''}} - 
                                              {{ $student->deployment->ending_date->format('M d, Y') ?? ''}}</span>
                                    </p>
                                    
                                    @endif
                                    <!-- Add these new fields -->
                                    @if($isEditingDeployment)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Custom Hours</label>
                                                <input type="number" 
                                                    wire:model="deploymentData.custom_hours" 
                                                    class="w-full border-gray-300 rounded-lg"
                                                    placeholder="Enter required hours">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Student Type</label>
                                                <select wire:model.live="deploymentData.student_type" 
                                                    class="w-full border-gray-300 rounded-lg">
                                                    <option value="regular">Regular</option>
                                                    <option value="special">Special</option>
                                                </select>
                                            </div>
                                            @if($deploymentData['student_type'] === 'special')
                                                <div class="col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Permit Document</label>
                                                    <input type="file" 
                                                        wire:model="permitFile" 
                                                        class="w-full border-gray-300 rounded-lg"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    <p class="mt-1 text-sm text-gray-500">Upload permit for special OJT arrangement</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex justify-end mt-4">
                                            <button wire:click="saveDeployment"
                                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                                Save Changes
                                            </button>
                                        </div>
                                    @else
                                        <p class="flex items-center gap-2 text-gray-600">
                                            <i class="fa fa-clock w-5"></i>
                                            <span>Required Hours: {{ $student->deployment->custom_hours ?? 'Default' }}</span>
                                        </p>
                                        <p class="flex items-center gap-2 text-gray-600">
                                            <i class="fa fa-user-graduate w-5"></i>
                                            <span>Type: {{ ucfirst($student->deployment->student_type ?? 'Regular') }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic">Not yet deployed</p>
                        @endif
                    </div>

            @elseif($selectedTab === 'documents')
                <div class="space-y-4">
                    <!-- Student ID -->
                    {{-- <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa fa-id-card text-blue-500 text-xl"></i>
                                <span class="text-gray-600">Student ID</span>
                            </div>
                            @if($student->supporting_doc)
                                <button x-data
                                    @click="$dispatch('open-preview', { url: '{{ Storage::url($student->supporting_doc) }}' })"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="fa fa-eye mr-2"></i>
                                    View Document
                                </button>
                            @else
                                <span class="text-gray-400">No document uploaded</span>
                            @endif
                        </div>
                    </div> --}}

                    <!-- Acceptance Letter -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa fa-file-contract text-green-500 text-xl"></i>
                                <span class="text-gray-600">Acceptance Letter</span>
                            </div>
                            @if($student->deployment->supervisor_id)
                                <button wire:click="downloadAcceptanceLetter"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="fa fa-download mr-2"></i>
                                    Download
                                </button>
                            @else
                                <span class="text-gray-400">No letter available</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa fa-clipboard-list text-indigo-500 text-xl"></i>
                                <span class="text-gray-600">Evaluation Report</span>
                            </div>
                            @if($student->deployment?->evaluation)
                                <button wire:click="downloadEvaluation"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="fa fa-download mr-2"></i>
                                    Download
                                </button>
                            @else
                                <span class="text-gray-400">No evaluation available</span>
                            @endif
                        </div>
                    </div>
                    @if($student->deployment?->student_type === 'special' && $student->deployment?->permit_path)
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa fa-file-certificate text-purple-500 text-xl"></i>
                <span class="text-gray-600">Special OJT Permit</span>
            </div>
            <button x-data
                @click="$dispatch('open-preview', { url: '{{ Storage::url($student->deployment->permit_path) }}' })"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                <i class="fa fa-eye mr-2"></i>
                View Document
            </button>
        </div>
    </div>
@endif
                </div>

            @elseif($selectedTab === 'reports')
                <div class="space-y-4">
                    @forelse($student->weeklyReports as $report)
                    @if($report->status === 'approved')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-800">Week {{ $report->week_number }}</h5>
                                    <p class="text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($report->start_date)->format('M d, Y') }} - 
                                        {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                            'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($report->status ?: 'pending') }}
                                    </span>
                                    <button wire:click="generateWeeklyReport({{ $report->id }})"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                        <i class="fa fa-download mr-2"></i>
                                        Generate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-file-alt text-gray-400 text-3xl mb-2"></i>
                            <p>No weekly reports submitted yet</p>
                        </div>
                        
                    @endforelse
                </div>
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
    </div>
</div>
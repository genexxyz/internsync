<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/instructor/assign-modal.blade.php -->
<div class="p-6">
    <!-- Student Information Header -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-2">Student Information</h2>
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium">{{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
                <div class="">
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">
                        {{ $student->yearSection->course->course_code }}
                        {{ $student->yearSection->year_level }}{{ $student->yearSection->class_section }}
                    </p>
                </div>
                <!-- Acceptance Letter Preview -->
                <div class="col-span-2 border-t mt-4 pt-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-700">Acceptance Letter</h3>


                        <button @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
        detail: {
            url: '{{ Storage::url($acceptanceLetter->signed_path) }}'
        }
    }))" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <i class="far fa-file-pdf mr-2"></i>
                            View PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Information from Letter -->
    @if($acceptanceLetter)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Submitted Company Details</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Company Name with Search Button -->
                    <div class="col-span-2 flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Company Name</p>
                            <p class="font-medium">{{ $acceptanceLetter->company_name }}</p>
                        </div>
                        <div>
                            @if(!$companyExists && !$isCreatingCompany)
                                <button wire:click="searchExistingCompany"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                                    <i class="fas fa-search mr-1.5"></i>
                                    Search Company
                                </button>
                            @elseif($companyExists)
                                <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg">
                                    <i class="fas fa-check-circle mr-1.5"></i>
                                    Existing Company
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Department with Add Button -->
                    <div class="col-span-2 flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            @if (!empty($acceptanceLetter->department_name))
                                <p class="font-medium">{{ $acceptanceLetter->department_name }}</p>
                            @else
                                <i class="font-medium text-gray-500">No Department Provided</i>

                            @endif

                        </div>
                        @if($companyExists && !in_array($acceptanceLetter->department_name, $existingDepartments) && !empty($acceptanceLetter->department_name))
                            <button wire:click="addNewDepartment"
                                class="inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-dark">
                                <i class="fas fa-plus mr-1.5"></i>
                                Add Department
                            </button>
                        @elseif($companyExists && in_array($acceptanceLetter->department_name, $existingDepartments))
                            <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg">
                                <i class="fas fa-check-circle mr-1.5"></i>
                                Existing Department
                            </span>
                        @endif
                    </div>

                    <!-- Other Details -->
                    <div>
                        <p class="text-sm text-gray-500">Supervisor Name</p>
                        <p class="font-medium">{{ $acceptanceLetter->supervisor_name }}</p>
                    </div>
                    
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Company Address</p>
                        <p class="font-medium">{{ $acceptanceLetter->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Contact Number</p>
                        <p class="font-medium">{{ $acceptanceLetter->contact }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $acceptanceLetter->email}}</p>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Department Selection (only if company exists) -->
        @if($companyExists)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Department Selection</h2>
                <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                    <!-- Student's provided department -->
                    <label class="flex items-start p-3 bg-blue-50 rounded-lg cursor-pointer">
                        <input type="radio" wire:model="selectedDepartment" value="{{ $acceptanceLetter->department_name }}"
                            class="mt-1 text-primary focus:ring-primary" checked>
                        <div class="ml-3">
                            @if (!empty($acceptanceLetter->department_name))
                                <p class="text-sm font-medium text-blue-900">{{ $acceptanceLetter->department_name }}</p>
                            @else
                                <i class="text-sm font-medium text-gray-500">No Department Provided</i>

                            @endif
                            <p class="text-xs text-blue-700">Provided by student</p>
                        </div>
                    </label>

                    <!-- Existing departments -->
                    @foreach($existingDepartments as $dept)
                        @if($dept !== $acceptanceLetter->department_name)
                            <label class="flex items-start p-3 bg-gray-50 rounded-lg cursor-pointer">
                                <input type="radio" wire:model="selectedDepartment" value="{{ $dept }}"
                                    class="mt-1 text-primary focus:ring-primary">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $dept }}</p>
                                    <p class="text-xs text-gray-600">Existing department</p>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Company Registration Form -->
        @if($isCreatingCompany)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Company Registration</h2>
                <div class="bg-white border rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" wire:model="newCompany.company_name"
                                class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                            <input type="text" wire:model="newCompany.contact_person"
                                class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" wire:model="newCompany.contact_number"
                                class="mt-1 w-full rounded-lg border-gray-300">
                        </div> --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" wire:model="newCompany.address" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        {{-- <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model="newCompany.email" class="mt-1 w-full rounded-lg border-gray-300">
                        </div> --}}
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button wire:click="cancelCompanyCreation" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            Cancel
                        </button>
                        <button wire:click="createAndSelectCompany"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                            Register Company
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="flex justify-end border-t space-x-2">
        @if ($assignButton)
        <button wire:click="assignStudent" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
            <i class="fas fa-check mr-1.5"></i>
            Assign Student
        </button>
        
        @endif
        
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


</div>
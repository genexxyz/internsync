<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-8 border-b pb-4">
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Supervisor Profile</h1>
                        <p class="text-gray-600">Welcome to InternSync! Complete your profile in 3 simple steps.</p>
                    </div>

                    <!-- Step Indicator -->
                    <div class="mb-8">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex items-center relative">
                                    <div @class([
                                        'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2',
                                        'bg-blue-600 text-white border-blue-600' => $i <= $currentStep,
                                        'bg-white text-gray-400 border-gray-300' => $i > $currentStep,
                                    ])>
                                        {{ $i }}
                                    </div>
                                    <div class="text-xs font-medium mt-2 absolute -bottom-6 left-1/2 transform -translate-x-1/2 whitespace-nowrap"
                                        style="min-width: 120px; text-align: center;">
                                        @if($i == 1)
                                            Company Information
                                        @elseif($i == 2)
                                            Assign Students
                                        @elseif($i == 3)
                                            Review & Sign
                                        @endif
                                    </div>
                                </div>
                                
                                @if($i < $totalSteps)
                                    <div @class([
                                        'flex-auto border-t-2 transition duration-500 ease-in-out mx-2',
                                        'border-blue-600' => $i < $currentStep,
                                        'border-gray-300' => $i >= $currentStep,
                                    ])></div>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if (session()->has('message'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mt-12">
                        <form wire:submit.prevent="save" class="space-y-8">
                            <!-- Step 1: Company Details -->
                            @if($currentStep == 1)
                                <div class="rounded-lg bg-gray-50 p-6 border border-gray-200">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Company Information</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Name -->
                                        <div>
                                            <x-text-input icon="fa fa-user" id="name" class="block mt-1 w-full" type="text"
                                                wire:model="name" name="name" placeholder="Your Full Name" />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Position -->
                                        <div>
                                            <x-text-input icon="fa fa-user" id="position" class="block mt-1 w-full" type="text"
                                                wire:model="position" name="position" placeholder="Position" />
                                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Company Name -->
                                        <div>
                                            <x-text-input icon="fa fa-building" id="company_name" class="block mt-1 w-full" type="text"
                                                wire:model="company_name" name="company_name" placeholder="Company Name" />
                                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Department Name -->
                                        <div>
                                            <x-text-input icon="fa fa-building" id="department_name" class="block mt-1 w-full" type="text"
                                                wire:model="department_name" name="department_name" placeholder="Department (If Applicable)" />
                                            <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Address -->
                                        <div>
                                            <x-text-input icon="fa fa-location-dot" id="address" class="block mt-1 w-full" type="text"
                                                wire:model="address" name="address" placeholder="Company Address" />
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Contact -->
                                        <div>
                                            <x-text-input icon="fa fa-phone" id="contact" class="block mt-1 w-full" type="text"
                                                wire:model="contact" name="contact" placeholder="Contact Number" />
                                            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Assign Students -->
                            @if($currentStep == 2)
                                <div class="rounded-lg bg-gray-50 p-6 border border-gray-200">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Assign Students</h2>
                                    <p class="text-sm text-gray-600 mb-4">Select students you'll be supervising during their internship:</p>
                                    
                                    @if(count($availableStudents) > 0)
                                        <div class="max-h-96 overflow-y-auto p-2 border rounded-md bg-white">
                                            @foreach($availableStudents as $student)
                                                <div class="flex items-center p-3 hover:bg-gray-50 rounded-md border-b border-gray-100">
                                                    <input type="checkbox" wire:model="selectedStudents" value="{{ $student->id }}" 
                                                        id="student_{{ $student->id }}" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                                                    <label for="student_{{ $student->id }}" class="ml-3 flex flex-col">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $student->student_number }} • 
                                                            {{ $student->yearSection->course->course_code ?? 'No Course' }}
                                                        </span>
                                                        @if($student->deployment && $student->deployment->companyDepartment)
                                                            <span class="text-xs text-blue-600">
                                                                Deployed at: {{ $student->deployment->companyDepartment->company->name }} - 
                                                                {{ $student->deployment->companyDepartment->name }}
                                                            </span>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 text-sm text-gray-500">
                                            Selected: {{ count($selectedStudents) }} student(s)
                                        </div>
                                    @else
                                        <div class="bg-amber-50 p-4 rounded-md border border-amber-200 text-amber-700">
                                            <p class="flex items-center">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No students are available for assignment. All students already have supervisors.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Step 3: Acceptance Letter & Signature -->
@if($currentStep == 3)
<div class="rounded-lg bg-gray-50 p-6 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Review & E-sign</h2>
    
    <!-- Acceptance Letter -->
    <div class="bg-white border rounded-md p-6 mb-6" style="font-family: 'Times New Roman', serif;">
        <div class="mb-10 text-center">
            <h3 class="font-bold text-xl mb-1">SUPERVISOR ACCEPTANCE LETTER</h3>
            <p class="text-sm">InternSync OJT Supervision Program</p>
        </div>
        
        <p class="mb-4"><i>{{ now()->format('F d, Y') }}</i></p>
        
        <p class="mb-4">The Program Head<br/>Pangasinan State University</p>
        
        <p class="mb-4">Dear Sir/Madam,</p>
        
        <p class="mb-4 text-justify">
            I, <u><i>{{ $name }}</i></u>, in my capacity as <u><i>{{ $position }}</i></u> at <u><i>{{ $company_name }}</i></u>
            @if($department_name) , <u><i>{{ $department_name }} Department</i></u>, @endif 
            hereby formally accept the responsibility to serve as an internship supervisor for students from Pangasinan State University.
        </p>
        
        <p class="mb-4 text-justify">
            I understand that my duties include providing guidance, monitoring progress, evaluating performance, and ensuring 
            that students receive meaningful work experiences aligned with their academic program. I commit to maintaining 
            regular communication with the university regarding the students' progress and any concerns that may arise.
        </p>
        
        <p class="mb-4 text-justify">
            @if(count($selectedStudents) > 0)
                I have selected {{ count($selectedStudents) }} student(s) to supervise during their internship program
                and will provide them with appropriate professional guidance throughout their training period.
            @else
                I acknowledge that I currently do not have any students assigned, but understand that 
                students may be assigned to me in the future, at which point I will provide them with appropriate professional guidance.
            @endif
        </p>
        
        <p class="mb-6 text-justify">
            Our company will ensure that all students under my supervision will have access to necessary resources and opportunities 
            to fulfill their required training hours in a safe and supportive environment.
        </p>
        
        <div class="mb-6">
            <p>For any inquiries, I can be reached at:</p>
            <p>Contact Number: <u><i>{{ $contact }}</i></u></p>
            <p>Address: <u><i>{{ $address }}</i></u></p>
        </div>
        
        <div class="mt-12">
            <p>Respectfully,</p>
            
            @if($signature)
                <div class="mb-2 mt-8">
                    <img src="{{ $signature->temporaryUrl() }}" alt="Signature" class="h-24 object-contain">
                </div>
            @else
                <div class="h-24 border-b border-gray-400 mb-2 mt-8">
                </div>
            @endif
            
            <p><u><i>{{ $name }}</i></u></p>
            <p>{{ $position }}</p>
            <p>{{ $company_name }}</p>
        </div>
    </div>
    
    <!-- Upload Signature -->
    <div class="bg-white border rounded-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Your E-signature</h3>
        <p class="text-sm text-gray-600 mb-4">
            Please upload an image of your signature to complete the registration process.
            Your signature will be applied to the acceptance letter above.
        </p>
        
        <div class="flex items-center space-x-4">
            <div class="flex-grow">
                <label for="signature" class="block text-sm font-medium text-gray-700 mb-1">
                    Signature (PNG, JPG)
                </label>
                <input type="file" wire:model="signature" id="signature" 
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0 file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    accept="image/*">
                <div wire:loading wire:target="signature" class="text-sm text-blue-600 mt-1">
                    Uploading...
                </div>
                @error('signature') 
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                @enderror
            </div>
            
            @if($signature)
                <div class="w-24 h-24 border rounded flex items-center justify-center overflow-hidden">
                    <img src="{{ $signature->temporaryUrl() }}" alt="Signature Preview" 
                        class="h-20 object-contain">
                </div>
            @endif
        </div>
    </div>
</div>
@endif

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between">
                                <div>
                                    @if($currentStep > 1)
                                        <button type="button" wire:click="previousStep"
                                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 
                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <i class="fas fa-arrow-left mr-2"></i> Previous
                                        </button>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($currentStep < $totalSteps)
                                        <button type="button" wire:click="nextStep"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700
                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            Next <i class="fas fa-arrow-right ml-2"></i>
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700
                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <i class="fas fa-check mr-2"></i> Complete Registration
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
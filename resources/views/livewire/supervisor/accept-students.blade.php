
<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/supervisor/accept-students.blade.php -->
<div class="p-6">
    <!-- E-signature Upload Section -->
    <div class="bg-white border rounded-lg overflow-hidden mb-6">
        <div class="p-4 bg-blue-50 flex items-center">
            <i class="fas fa-signature text-blue-700 mr-3 text-xl"></i>
            <span class="font-medium text-blue-800">Upload Your E-signature</span>
        </div>
        <div class="p-4">
            <p class="text-sm text-gray-600 mb-4">
                Upload your signature once to use on all acceptance letters. This signature will be applied to all student acceptance letters when you accept them.
            </p>
            
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <input type="file" 
                        wire:model.live="signature" 
                        accept="image/*" 
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    >
                    <div wire:loading wire:target="signature" class="text-xs text-blue-600 mt-1">
                        Uploading...
                    </div>
                    @error('signature') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                @if($signature)
                    <div class="flex-shrink-0 border rounded-md p-2 bg-white">
                        <img src="{{ $signature->temporaryUrl() }}" alt="Signature preview" class="h-16 w-auto object-contain">
                    </div>
                @elseif(Auth::user()->supervisor->signature_path ?? false)
                    <div class="flex-shrink-0 border rounded-md p-2 bg-white">
                        <img src="{{ Auth::user()->supervisor->signature_path }}" alt="Saved signature" class="h-16 w-auto object-contain">
                        <p class="text-xs text-gray-500 mt-1">Your saved signature</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(count($availableStudents) > 0)
        <div class="bg-blue-50 p-4 rounded-lg mb-6 flex items-start space-x-3">
            <div class="text-blue-400">
                <i class="fas fa-info-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-medium text-blue-800">Available Students</h4>
                <p class="text-sm text-blue-600">
                    Select the students you want to accept and click "Accept Selected Students". Each student will have an individual acceptance letter.
                </p>
            </div>
        </div>
        
        <div class="flex justify-end mb-4">
            <button 
                wire:click="acceptStudents"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                @if(count($selectedStudents) == 0) disabled @endif
            >
                <i class="fas fa-check mr-2"></i> Accept Selected Students ({{ count($selectedStudents) }})
            </button>
        </div>
        
        <div class="bg-white border rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 pl-4 pr-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input 
                                type="checkbox" 
                                wire:model.live="selectAll"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            >
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Section
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Company
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($availableStudents as $deployment)
                        <tr class="hover:bg-gray-50" wire:key="deployment-{{ $deployment->id }}">
                            <td class="py-4 pl-4 pr-3 text-sm font-medium">
                                <input 
                                    type="checkbox" 
                                    value="{{ $deployment->id }}" 
                                    wire:model.live="selectedStudents"
                                    id="deployment-{{ $deployment->id }}"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                >
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $deployment->student->user->first_name ?? $deployment->student->first_name }} 
                                            {{ $deployment->student->user->last_name ?? $deployment->student->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $deployment->student->student_id ?? $deployment->student->student_number }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $deployment->student->yearSection->course->course_code ?? '' }}
                                {{ $deployment->student->yearSection->year_level ?? '' }}{{ $deployment->student->yearSection->class_section ?? '' }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $deployment->department->company->company_name ?? $deployment->department->company->name ?? '' }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $deployment->department->department_name ?? $deployment->department->name ?? '' }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                <button
                                    wire:click="viewStudentLetter({{ $deployment->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    <i class="fas fa-file-alt mr-1"></i> View Letter
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="text-yellow-400 mb-4">
                    <i class="fas fa-exclamation-circle text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-yellow-800 mb-2">No Students Available</h3>
                <p class="text-sm text-yellow-700 max-w-lg">
                    No students are available for supervision at this time. This could be because there are no deployments matching your company/department, or all students already have supervisors.
                </p>
            </div>
        </div>
    @endif

    <!-- Per-student Acceptance Letter Modal -->
    @if($showLetterModal && $viewingDeploymentId)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"></div>
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button wire:click="closeLetterModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Student Acceptance Letter</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Review the acceptance letter for this student before accepting them.
                        </p>
                    </div>
                    
                    @php
                        $deployment = \App\Models\Deployment::with(['student.user', 'department.company'])->find($viewingDeploymentId);
                        $student = $deployment ? $deployment->student : null;
                        $studentName = $student ? ($student->user->first_name ?? $student->first_name) . ' ' . ($student->user->last_name ?? $student->last_name) : 'Student';
                    @endphp
                    
                    <!-- Letter Preview -->
                    <div class="border rounded-lg p-6 mb-6 max-h-96 overflow-y-auto"
                                style="font-family: 'Times New Roman', serif;">
                                <p class="text-xl text-center font-bold">ACCEPTANCE LETTER</p>
                                <p class="mt-5"><i>{{ now()->format('F d, Y') }}</i></p>

                                <p class="mt-4">
                                    {{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name}}</p>
                                <p>{{ $deployment->department->company->company_name}}</p>

                                <p class="mt-4">Dear Mr/Ms.
                                    {{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name}},</p>

                                <p class="mt-4">Greetings!</p>

                                <p class="mt-4 text-justify">The {{$settings->school_name}} offers a
                                    {{$deployment->student->section->course->course_name}} program, which requires all
                                    graduating students to complete On-the-Job Training as part of their academic curriculum.
                                    With the resumption of on-site OJT opportunities, the student is expected to complete
                                    {{ $deployment->student->section->course->required_hours }} hours of training, either in
                                    person or online, depending on the company’s health protocols and work arrangements.</p>

                                <p class="mt-4 text-justify">In line with this, we respectfully request your good office to
                                    accommodate {{ $studentName }}, a dedicated and competent
                                    {{$deployment->student->section->course->course_name}} student, for his/her OJT in your
                                    esteemed company. S/he has been carefully selected based on her academic achievements and
                                    technical skills. Additionally, s/he has undergone proper orientation to ensure s/he meets
                                    the performance standards expected by both the school and the industry.</p>

                                <p class="mt-4 text-justify">We sincerely appreciate your time and consideration. Thank you in
                                    advance for your support, and we look forward to a positive collaboration.</p>

                                <p class="mt-4 text-justify">Respectfully yours,</p>

                                <p class="mt-4 text-justify">
                                    {{$deployment->student->section->course->instructorCourses->first()?->instructor->first_name . ' ' . $deployment->student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A'}}<br>Program
                                    Head - {{$deployment->student->section->course->course_name}}</p>

                                <div class="border-t border-dashed border-gray-600 mt-4">
                                    <strong class="mt-4">ACCEPTANCE</strong>
                                    <p>Name of the Company: <u>{{ $deployment->department->company->company_name}}</u></p>
                                    <p>Address: <u>{{ $deployment->department->company->address}}</u></p>
                                    <p>Telephone Number: <u>{{ $deployment->department->company->contact}}</u></p>
                                    <p>Contact Person’s Name:
                                        <u>{{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name}}</u>
                                        @if($signature)
                                            <img src="{{ $signature->temporaryUrl() }}" alt="E-signature"
                                                class="max-h-24 max-w-xs mb-2">
                                        @elseif(Auth::user()->supervisor->signature_path ?? false)
                                            <p>Signature: </p><img src={{ Storage::url(Auth::user()->supervisor->signature_path) }}"
                                                alt="E-signature" class="max-h-24 max-w-xs mb-2">
                                        @else
                                            <div class="w-102 h-16 flex items-end">
                                                <p>Signature: </p><span class="text-gray-400 text-sm border-b border-gray-400 ">(Your
                                                    e-signature will appear here)</span>
                                            </div>
                                        @endif</p>
                                    <p>Position of the Contact Person: <u>{{Auth::user()->supervisor->position}}</u></p>
                                </div>
                            </div>
                    
                    <!-- Modal footer -->
                    <div class="flex justify-between">
                        <div>
                            @if($signature || (Auth::user()->supervisor->signature_path ?? false))
                                <button 
                                    wire:click="generateAcceptanceLetter({{ $viewingDeploymentId }})"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center"
                                >
                                    <i class="fas fa-download mr-2"></i> Download Letter
                                </button>
                            @endif
                        </div>
                        
                        <div class="space-x-3">
                            <button 
                                wire:click="closeLetterModal"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Close
                            </button>
                            
                            @if(in_array((string)$viewingDeploymentId, $selectedStudents))
                                <button 
                                    wire:click="$set('selectedStudents', {{ json_encode(array_values(array_filter($selectedStudents, fn($id) => $id != (string)$viewingDeploymentId))) }})"
                                    class="px-4 py-2 bg-red-50 text-red-700 rounded-md hover:bg-red-100"
                                >
                                    Deselect
                                </button>
                            @else
                                <button 
                                    wire:click="$set('selectedStudents', {{ json_encode(array_merge($selectedStudents, [(string)$viewingDeploymentId])) }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                >
                                    Select Student
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
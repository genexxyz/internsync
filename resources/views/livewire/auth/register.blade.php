<div>
    <form wire:submit.prevent="register" enctype="multipart/form-data">

        <div>
            <x-select-input name="role" :options="[
        'student' => 'Student',
        'instructor' => 'Instructor',
        'supervisor' => 'Supervisor/Client',
    ]" :selected="old('role')" placeholder="Select Role" icon="fa fa-list" />
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>




        <div class="mt-6 grid lg:grid-cols-2 gap-2 xs:grid-cols-1">
            <!-- First Name -->
            <div class="mt-4">

                <x-text-input wire:model="first_name" icon="fa fa-font" id="first_name" class="block mt-1 w-full"
                    type="text" name="first_name" :value="old('first_name')" required autofocus
                    autocomplete="given-name" placeholder="First Name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Name (Optional) -->
            <div class="mt-4">
                <x-text-input wire:model="middle_name" icon="fa fa-font" id="middle_name" class="block mt-1 w-full"
                    type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name"
                    placeholder="Middle Name (If Applicable)" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-text-input wire:model="last_name" icon="fa fa-font" id="last_name" class="block mt-1 w-full"
                    type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name"
                    placeholder="Last Name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Suffix (Optional) -->
            <div class="mt-4">
                <x-text-input wire:model="suffix" icon="fa fa-font" id="suffix" class="block mt-1 w-full" type="text"
                    name="suffix" :value="old('suffix')" autocomplete="honorific-suffix"
                    placeholder="Suffix (If Applicable)" />
                <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
            </div>
        </div>


        {{-- <div class="mt-6">

            <p class="text-gray-600">Birthday</p>
            <div class="grid grid-cols-3 gap-4 w-full lg:w-2/3">


                <!-- Birthday Fields -->
                <div class="mt-2">
                    <x-input-label for="month" :value="__('Month')" />

                    <x-text-input wire:model.lazy="month" icon="fa fa-calendar" id="month" class="block mt-1 w-full"
                        type="text" name="month" :value="old('month')" required placeholder="MM" maxlength="2" />
                    <x-input-error :messages="$errors->get('month')" class="mt-2" />
                </div>

                <div class="mt-2">
                    <x-input-label for="day" :value="__('Day')" />

                    <x-text-input wire:model.lazy="day" icon="fa fa-calendar" id="day" class="block mt-1 w-full"
                        type="text" name="day" :value="old('day')" required placeholder="DD" maxlength="2" />
                    <x-input-error :messages="$errors->get('day')" class="mt-2" />
                </div>

                <div class="mt-2">
                    <x-input-label for="year" :value="__('Year')" />

                    <x-text-input wire:model.lazy="year" icon="fa fa-calendar" id="year" class="block mt-1 w-full"
                        type="text" name="year" required :value="old('year')" placeholder="YYYY" maxlength="4" />
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>
            </div> --}}

            <!-- Display Full Birthday -->
            {{-- @if ($birthday)
            <p>Your birthday: {{ $birthday }}</p>
            @endif --}}
            {{-- @error('birthday')
            <span class="error">{{ $message }}</span>
            @enderror
        </div> --}}



        @if ($role === 'instructor')
            <div class="mt-7" x-data="{ isProgramHead: false }">
                <div class="mt-4">
                    <x-text-input icon="fa fa-id-badge" id="instructor_id" class="block mt-1 w-full" type="text"
                        name="instructor_id" wire:model="instructor_id" :value="old('instructor_id')" required autofocus
                        placeholder="Instructor ID" maxlength="10" />
                    <x-input-error :messages="$errors->get('instructor_id')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <livewire:searchable-dropdown-multiple wire:model="handle_sections" :options="$verifiedSections->map(function ($section) {
                return [
                    'value' => $section->id,
                    'label' => $section->course
                        ? $section->course->course_code .
                        ' ' .
                        $section->year_level .
                        $section->class_section
                        : 'No Course',
                ];
            })->toArray()" name="handle_sections" placeholder="Handled Sections" :multiple="true" />
                    <x-input-error :messages="$errors->get('handle_sections')" class="mt-2" />

                    <!-- Debug Information -->
                    {{-- <div class="mt-2 space-y-2">
                        <div class="text-sm text-gray-500">
                            Selected Sections (JSON):
                            <pre>@json($handle_sections)</pre>
                        </div>
                        <div class="text-sm text-gray-500">
                            Selected Count: {{ count($handle_sections) }}
                        </div>
                        <button type="button" wire:click="debugSections" class="text-sm text-blue-500 underline">
                            Debug Sections
                        </button>
                    </div> --}}
                </div>


                <div class="mt-4 rounded-md border border-gray-300 p-3">

                    <!-- Program Head Checkbox -->
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                x-model="isProgramHead" wire:model="isProgramHead">
                            <span class="ml-2 text-gray-500">I am a Program Head</span>
                        </label>
                    </div>

                    <!-- Course Selection (visible only for Program Heads) -->
                    <div class="mt-4" x-show="isProgramHead" x-cloak>
                        <livewire:searchable-dropdown wire:model="course_id" :options="$courses
                ->map(function ($course) {
                    return [
                        'value' => $course->id,
                        'label' => $course->course_name,
                    ];
                })
                ->toArray()" name="course_id" placeholder="Select Course" :multiple="false" />
                        <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                    </div>
                </div>
                <!-- Academic Year and Year Level -->
                <div class="mt-4">
                    <div>
                        <x-select-input wire:model="academic_year_id" name="academic_year_id" icon="fa fa-calendar"
                            placeholder="A.Y. & Semester" :options="$academicYear
                ->mapWithKeys(fn($year) => [$year->id => $year->academic_year . ' (Semester ' . $year->semester . ')'])
                ->toArray()" :selected="old('academic_year_id')" />
                        <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                    </div>
                </div>
            </div>
        @endif

        @if ($role === 'student')
            <div class="mt-6 grid lg:grid-cols-2 gap-2 xs:grid-cols-1">


                <div class="mt-4">
                    <x-text-input icon="fa fa-id-badge" id="student_id" class="block mt-1 w-full" type="text"
                        wire:model="student_id" name="student_id" :value="old('student_id')" required autofocus
                        maxlength="10" placeholder="Student ID" />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <div class="mt-4">

                    <livewire:searchable-dropdown wire:model="year_section_id" :options="$sections
                ->map(function ($section) {
                    return [
                        'value' => $section->id,
                        'label' => $section->course
                            ? $section->course->course_code .
                            ' ' .
                            $section->year_level .
                            $section->class_section
                            : 'No Course',
                    ];
                })
                ->toArray()" name="year_section_id" placeholder="Course, Year & Section" />
                    <x-input-error :messages="$errors->get('year_section_id')" class="mt-2" />
                </div>
                <!-- Academic Year and Year Level -->
                <div class="mt-4">
                    <div>
                        <x-select-input wire:model="academic_year_id" name="academic_year_id" icon="fa fa-calendar"
                            placeholder="A.Y. & Semester" :options="$academicYear
                ->mapWithKeys(fn($year) => [
                    $year->id => $year->academic_year . ' (Semester ' . $year->semester . ')'
                ])
                ->toArray()"
                            :selected="old('academic_year_id')" />
                        <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-4">
                    <x-select-input name="custom_hours" placeholder="Student Type" icon="fa fa-user" :options="[
                'regular' => 'Regular',
                'special' => 'Special (For Athletes, School Representatives, etc.)',
            ]" :selected="old('custom_hours')" />
                    <x-input-error :messages="$errors->get('custom_hours')" class="mt-2" />

                        {{-- <div class="text-sm text-gray-500">
                            Selected Sections (JSON):
                            <pre>@json($custom_hours)</pre>
                        </div> --}}
                </div>
            </div>
            {{-- @if ($year_section_id)
            <p>{{ $year_section_id }}</p>
            @endif --}}
            <div class="my-7">
                <p class="text-gray-600">Address</p>
                @livewire('address-selector')
            </div>

            {{-- <!-- You can display the full address if needed -->
            @if ($fullAddress)
            <div>
                <label>Full Address:</label>
                <p>{{ $fullAddress }}</p>
            </div>
            @endif --}}


            <!-- Other form fields -->
            <input type="hidden" wire:model="fullAddress" name="address">
        @endif

        @if ($role === 'supervisor')
            <div class="mt-4">
                <livewire:searchable-dropdown wire:model="company_id" :options="$companies
                ->map(function ($company) {
                    return [
                        'value' => $company->id,
                        'label' => $company->company_name,
                    ];
                })
                ->toArray()" name="company_id"
                    placeholder="Company" />
                <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
            </div>
            @if (!empty($companyDepartments))
                <div class="mt-4">
                    <x-select-input wire:model="company_department" name="company_department" icon="fa fa-building"
                        placeholder="Department" :options="$companyDepartments
                        ->mapWithKeys(fn($department) => [
                            $department->id => $department->department_name
                        ])
                        ->toArray()"
                        :selected="old('company_department_id')" />
                    <x-input-error :messages="$errors->get('company_department_id')" class="mt-2" />
                </div>
            @endif
        @endif



        <div class="mt-6">
            <x-text-input wire:model.lazy="contact" icon="fa fa-phone" id="contact" class="block mt-1 w-full"
                type="text" name="contact" :value="old('contact')" required placeholder="Contact No." maxlength="11" />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>


        <div class="mt-6">
            <x-text-input wire:model="email" icon="fa fa-envelope" id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required autocomplete="email" placeholder="Email Address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" icon="fa fa-lock" id="password" class="block mt-1 w-full"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" icon="fa fa-check" id="password_confirmation"
                class="block mt-1 w-full" type="password" name="password_confirmation" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div> --}}



        <div class="mt-4">
            <x-password-input wire:model="password" icon="fa fa-lock" id="password" name="password"
                placeholder="Password" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <div class="mt-4">
            <x-password-input wire:model="password_confirmation" icon="fa fa-check" id="password"
                name="password_confirmation" placeholder="Confirm Password" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>



        <!-- Replace the existing supporting document section with this -->
        <div class="mt-4" x-data="{ 
    photoPreview: null,
    handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
            <div class="flex items-center justify-between">
                <x-input-label for="document" :value="__($role === 'instructor' ? 'Instructor ID' :
        ($role === 'student' ? 'Student ID' :
            ($role === 'supervisor' ? 'Employee ID' : 'Supporting Document')))" />

                @if($photoPreview)
                    <button type="button" @click="photoPreview = null; $refs.photo.value = ''"
                        class="text-sm text-red-500 hover:text-red-700">
                        Remove
                    </button>
                @endif
            </div>

            <div class="mt-2">
                <input type="file" wire:model="document" id="document" x-ref="photo" class="hidden"
                    @change="handleFileUpload($event)" accept=".jpg,.jpeg,.png">

                <div class="flex items-center gap-4">
                    <button type="button" @click="$refs.photo.click()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-400 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fa fa-upload mr-2"></i>
                        Upload File
                    </button>
                    @if($document)
                        <span class="text-sm text-gray-600">
                            File selected: {{ $document->getClientOriginalName() }}
                        </span>
                    @endif
                </div>

                <!-- Preview -->
                <div x-show="photoPreview" class="mt-4">
                    <span class="block rounded-lg w-40 h-40 bg-cover bg-center bg-no-repeat"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <p class="mt-2 text-sm text-gray-500">
                    Accepted file types: PNG, JPEG, JPG. Maximum file size: 2MB.
                </p>
                <x-input-error :messages="$errors->get('document')" class="mt-2" />
            </div>
        </div>

        <!-- Add this before the submit button -->
        <!-- Replace the existing terms and conditions section with this -->
        <div class="mt-4" x-data="{ showTerms: false }">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" wire:model="acceptTerms"
                        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                </div>
                <div class="ml-3">
                    <label for="terms" class="text-sm text-gray-600">
                        I accept the
                        <button type="button" @click="showTerms = true"
                            class="text-primary hover:underline focus:outline-none">
                            Terms and Conditions
                        </button>
                        .
                    </label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('acceptTerms')" class="mt-2" />

            <!-- Terms Modal -->
            <div x-show="showTerms" class="fixed inset-0 z-50 overflow-y-auto"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                <!-- Modal content -->
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="relative bg-white rounded-xl max-w-3xl w-full shadow-2xl p-6 overflow-y-auto max-h-[85vh]"
                        @click.away="showTerms = false">

                        <!-- Close button -->
                        <button @click="showTerms = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>

                        <!-- Modal header -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">Terms & Conditions</h2>
                            {{-- <p class="text-sm text-gray-500 mt-1">Last Updated: Feb 1, 2025</p> --}}
                        </div>

                        <div class="space-y-6 px-2">
                            <!-- Account Section -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    Account Usage
                                </h3>
                                <ul class="list-disc pl-5 text-sm space-y-2 text-gray-600">
                                    <li>Provide accurate information and keep it updated</li>
                                    <li>Keep your login details private and secure</li>
                                    <li>Don't share accounts or submit false information</li>
                                </ul>
                            </div>

                            <!-- Privacy Section -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                    <i class="fas fa-shield-alt text-primary"></i>
                                    Data Privacy (RA 10173)
                                </h3>
                                <ul class="list-disc pl-5 text-sm space-y-2 text-gray-600">
                                    <li>We collect: name, contact, education details, training records</li>
                                    <li>Your rights: access, correct, delete your information</li>
                                    <li>We protect your data according to Philippine law</li>
                                </ul>
                            </div>

                            <!-- Platform Usage -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                    <i class="fas fa-desktop text-primary"></i>
                                    Platform Usage
                                </h3>
                                <div class="grid md:grid-cols-2 gap-6 text-sm">
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <p class="font-medium text-primary mb-2 flex items-center gap-2">
                                            <i class="fas fa-check-circle"></i>
                                            Allowed:
                                        </p>
                                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                                            <li>Track OJT progress</li>
                                            <li>Submit reports</li>
                                            <li>Contact supervisors</li>
                                        </ul>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <p class="font-medium text-red-500 mb-2 flex items-center gap-2">
                                            <i class="fas fa-times-circle"></i>
                                            Prohibited:
                                        </p>
                                        <ul class="list-disc pl-5 space-y-2 text-gray-600">
                                            <li>Share login details</li>
                                            <li>Submit false data</li>
                                            <li>Harass others</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                    Contact Us
                                </h3>
                                <p class="text-sm text-gray-600">
                                    For questions or concerns, reach us at:
                                    <a href="mailto:internsync01@gmail.com"
                                        class="text-primary hover:underline">internsync01@gmail.com</a>
                                </p>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                            <button @click="showTerms = false"
                                class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                I Understand
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update the submit button to be disabled when terms aren't accepted -->
            <div class="flex items-center justify-end mt-7">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
    </form>
</div>
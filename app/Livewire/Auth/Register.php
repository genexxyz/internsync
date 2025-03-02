<?php

namespace App\Livewire\Auth;

use App\Models\Academic;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use Livewire\Component;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Section;
use App\Models\Student;
use App\Models\Supervisor;
use App\Services\PHPMailerService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class Register extends Component
{
    use WithFileUploads;
    public $acceptTerms = false;

    // Common fields
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';


    // Role-specific fields
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $suffix = '';
    // public $month;
    // public $day;
    // public $year;
    // public $birthday;

    public $contact;
    public $document;
    public $photoPreview = null;


    // Additional role-specific fields
    public $instructor_id = '';
    public $student_id = '';
    public $hours;
    public $year_section_id;
    public $handle_sections = [];
    public $academicYear;

    // Address fields
    public $street = '';
    public $barangay = '';
    public $city = '';
    public $province = '';

    // Sections for dropdown
    public $sections;
    public $verifiedSections;
    public $courses;
    public $fullAddress = '';
    public $companies;
    public $isProgramHead = false;
    public $course_id;
    public $academic_year_id = '';
    public $custom_hours = '';
    public $company_id;
    public $company_department;
    public $companyDepartments = [];


    // Add a debug method
    public function debugSections()
    {
        logger('Current Sections:', [
            'handle_sections' => $this->handle_sections,
            'type' => gettype($this->handle_sections)
        ]);
    }

    public function updateFullAddress($address)
    {
        $this->fullAddress = $address;
    }
    public function updateYearSection($selected)
    {
        $this->year_section_id = $selected;
    }
    // Computed property for full address
    protected $listeners = [
        'address-updated' => 'updateFullAddress',
        'option-selected' => 'updateYearSection',
        'valueMultipleUpdated' => 'updatedHandleSections',
        'valueSingleUpdated' => 'updatedSingleSelection',
    ];

    public function updatedHandleSections($value)
    {
        logger('Handle Sections Updated:', [
            'value' => $value,
            'type' => gettype($value)
        ]);

        if (is_array($value)) {
            $this->handle_sections = $value;
        } else {
            $this->handle_sections = [$value];
        }
    }
    public function updatedSingleSelection($value)
    {
        switch ($this->role) {
            case 'instructor':
                $this->course_id = $value;
                break;
            case 'student':
                $this->year_section_id = $value;

                break;
            case 'supervisor':
                $this->company_id = $value;
                if ($this->company_id) {
                    $checkDepartment = Department::where('company_id', $this->company_id)->get();
                    if ($checkDepartment->count() > 0) {
                        $this->companyDepartments = $checkDepartment;
                    } else {
                        $this->companyDepartments = [];
                        $this->company_department = null;
                    }
                }
                break;
        }
    }

    private function getHours($valueSection, $valueType)
{
    $section = Section::find($valueSection);
    
    if ($section && $section->course_id) {
        $course = Course::find($section->course_id);
        
        if ($course) {
            if ($valueType === 'special') {
                $this->hours = $course->custom_hours;
            } else {
                $this->hours = $course->required_hours;
            }
            
            return $this->hours;
        }
    }
    
    return null; // Return null if section or course not found
}


    protected function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:instructor,student,supervisor',
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
            'suffix' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\s.]+$/'],
            // 'month' => 'required|numeric|min:1|max:12',
            // 'day' => 'required|numeric|min:1|max:31',
            // 'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'contact' => 'required|digits:11',
            'acceptTerms' => 'accepted',
            'document' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048', // 2MB max
            ],
        ];

        if ($this->role === 'instructor') {
            $rules['instructor_id'] = 'required|unique:instructors,instructor_id';
            $rules['handle_sections'] = 'required|array|min:1'; // Array validation
            $rules['academic_year_id'] = 'required|exists:academics,id';

            if ($this->isProgramHead) {
                $rules['course_id'] = 'required|exists:courses,id';
            }
        }

        if ($this->role === 'student') {
            $rules['student_id'] = 'required|unique:students,student_id';
            $rules['year_section_id'] = 'required|exists:sections,id';
            $rules['academic_year_id'] = 'required';
            $rules['custom_hours'] = 'required|in:regular,special';
            $rules['fullAddress'] = 'required|string';
        }

        if ($this->role === 'supervisor') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'first_name.regex' => 'First name may only contain letters, spaces, and periods.',
            'middle_name.regex' => 'Middle name may only contain letters, spaces, and periods.',
            'last_name.regex' => 'Last name may only contain letters, spaces, and periods.',
            'suffix.regex' => 'Suffix may only contain letters, spaces, and periods.',
            'acceptTerms.accepted' => 'You must accept the terms and conditions before registering.',
            'document.required' => 'Please upload your ID.',
            'document.mimes' => 'The document must be a PNG, JPEG, or JPG file.',
            'document.max' => 'The document must not be larger than 2MB.',
        ];
    }



    // public function setBirthday()
    // {
    //     try {
    //         // Concatenate and parse into a valid date
    //         $this->birthday = Carbon::createFromDate($this->year, $this->month, $this->day)->format('Y-m-d');
    //     } catch (\Exception $e) {
    //         $this->addError('birthday', 'Invalid date provided.');
    //     }
    // }

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);

    //     // If all fields are set, update the birthday field
    //     if ($this->month && $this->day && $this->year) {
    //         $this->setBirthday();
    //     }
    // }

    private function processDocument($user)
{
    if (!$this->document) {
        return null;
    }

    try {
        $extension = $this->document->getClientOriginalExtension();
        $fileName = sprintf(
            '%s_%s_%s_%s_%s.%s',
            $user->id,
            $this->role,
            Str::slug($this->last_name),
            Str::slug($this->first_name),
            Str::random(8),
            $extension
        );

        // Store in the "public" disk under "documents" folder
        $path = $this->document->storeAs('documents', $fileName, 'public');
        
        // $path will be something like "documents/filename.png"
        // Return just $path (not "storage/$path") so it matches your route
        return $path;
    } catch (\Exception $e) {
        logger($e->getMessage());
        throw new \Exception('Failed to upload document. Please try again.');
    }
}

    public function register()
    {
        $this->validate();

        // Create user with unverified status
        $user = User::create([
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role,
        ]);

        // Process and store the document
        $documentPath = $this->processDocument($user);

        // Generate and send OTP
        // $otp = $user->generateOTP();
        // $this->sendOtpEmail($user, $otp);

        // Create role-specific profile
        $this->createRoleProfile($user, $documentPath);

        // Redirect to OTP verification
        return redirect()->route('verify.email', ['email' => $this->email]);
    }

    protected function createRoleProfile($user, $documentPath)
    {
        $profileData = [
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            // 'birthday' => $this->birthday,
            'contact' => $this->contact,
            'supporting_doc' => $documentPath,
        ];

        switch ($this->role) {
            case 'instructor':
                // Create instructor profile
                $instructor = Instructor::create($profileData + [
                    'instructor_id' => $this->instructor_id,
                ]);

                // Handle multiple sections for instructor
                if (!empty($this->handle_sections)) {
                    $insertData = collect($this->handle_sections)->map(function ($sectionId) use ($instructor) {
                        return [
                            'instructor_id' => $instructor->id,
                            'year_section_id' => (int)$sectionId, // Cast to integer to ensure proper type
                            'academic_year_id' => $this->academic_year_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray();

                    try {
                        DB::table('instructor_sections')->insert($insertData);
                    } catch (\Exception $e) {
                        logger('Error inserting instructor sections:', [
                            'error' => $e->getMessage(),
                            'sections' => $this->handle_sections
                        ]);
                        throw $e;
                    }
                }
                if ($this->isProgramHead) {
                    $insertData = [
                        'instructor_id' => $instructor->id,
                        'course_id' => $this->course_id, // Assuming course_id is set
                        'academic_year_id' => $this->academic_year_id, // Ensure this is set
                        'is_verified' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    try {
                        DB::table('instructor_courses')->insert($insertData);
                    } catch (\Exception $e) {
                        logger('Error inserting instructor courses:', [
                            'error' => $e->getMessage(),
                            'course_id' => $this->course_id
                        ]);
                        throw $e;
                    }
                }
                break;

            case 'student':
                // Create student profile
                $student = Student::create($profileData + [
                    'student_id' => $this->student_id,
                    'year_section_id' => $this->year_section_id,
                    'address' => $this->fullAddress,
                ]);

                // Create deployment record for student
                DB::table('deployments')->insert([
                    'student_id' => $student->id,
                    'year_section_id' => $this->year_section_id,
                    'academic_id' => $this->academic_year_id,
                    'custom_hours' => $this->getHours($this->year_section_id, $this->custom_hours),
                    'instructor_id' => null, // This should be set later when assigned
                    'supervisor_id' => null, // This should be set later when assigned
                    'company_id' => null, // This should be set later when assigned
                    'company_dept_id' => null, // This should be set later when assigned
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                break;
            case 'supervisor':
                $profileData['company_id'] = $this->company_id;
                $profileData['company_department_id'] = $this->company_department;
                Supervisor::create($profileData);
                break;
        }
    }

    protected function sendOtpEmail($user, $otp)
    {
        $mailer = new PHPMailerService();
        $mailer->send(
            $user->email,
            'Email Verification OTP',
            "Your OTP for email verification is: {$otp}"
        );
    }

    public function mount()
    {
        $this->academicYear = Academic::all();
        $this->sections = Section::with('course')
            ->orderBy('year_level', 'asc')
            ->get();
            $this->courses = Course::whereDoesntHave('instructorCourses', function($query) {
                $query->where('is_verified', true);
            })
            ->orderBy('course_name')
            ->get();
        $this->companies = Company::orderBy('company_name')->get();
        // Initialize companyDepartments based on the selected company

        $this->verifiedSections = Section::with('course')
        ->whereDoesntHave('handles', function($query) {
            $query->where('is_verified', true);
        })
        ->orderBy('year_level', 'asc')
        ->get();

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}

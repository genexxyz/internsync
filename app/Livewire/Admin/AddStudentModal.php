<?php

namespace App\Livewire\Admin;

use App\Mail\StudentCredentials;
use App\Models\Course;
use App\Models\Deployment;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;
use App\Models\Academic;

class AddStudentModal extends ModalComponent
{
    public $studentId;
    public $firstName;
    public $middleName;
    public $lastName;
    public $suffix;
    public $email;
    public $contact;
    public $address;
    public $courseId;
    public $sectionId;
    public $type = 'regular';
    public $customHours;

    public $availableCourses = [];
    public $availableSections = [];
    public $selectedCourse = null;

    protected $rules = [
        'studentId' => 'required|unique:students,student_id',
        'firstName' => 'required|string|max:255',
        'middleName' => 'nullable|string|max:255',
        'lastName' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:10',
        'email' => 'required|email|unique:users,email',
        'contact' => 'required|string|max:20',
        'address' => 'required|string',
        'courseId' => 'required',
        'sectionId' => 'required',
        'type' => 'required|in:regular,special',
    ];

    public function mount()
    {
        $this->availableCourses = Course::orderBy('course_code')->get();
    }

    public function updatedCourseId($value)
{
    if ($value) {
        $this->selectedCourse = Course::find($value);
        $this->availableSections = Section::where('course_id', $value)
            ->orderBy('year_level')
            ->orderBy('class_section')
            ->get();
        $this->sectionId = null;

        // Reset type to regular if course doesn't allow custom hours
        if (!$this->selectedCourse->allows_custom_hours && $this->type === 'special') {
            $this->type = 'regular';
        }
    } else {
        $this->availableSections = [];
        $this->selectedCourse = null;
    }
}

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $section = Section::findOrFail($this->sectionId);
            $course = Course::findOrFail($this->courseId);

            // Validate special type
            if ($this->type === 'special' && !$course->allows_custom_hours) {
                $this->addError('type', 'This course does not allow special type students.');
                return;
            }

            // Generate password
            $password = strtolower(str_replace(' ', '', $this->lastName)) . '_' . Str::random(8);

            // Create user
            $user = User::create([
                'email' => $this->email,
                'password' => bcrypt($password),
                'role' => 'student',
                'email_verified_at' => now(),
                'is_verified' => true,
            ]);

            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $this->studentId,
                'first_name' => $this->firstName,
                'middle_name' => $this->middleName,
                'last_name' => $this->lastName,
                'suffix' => $this->suffix,
                'contact' => $this->contact,
                'address' => $this->address,
                'year_section_id' => $this->sectionId,
            ]);

            // Create deployment
            Deployment::create([
                'student_id' => $student->id,
                'year_section_id' => $this->sectionId,
                'academic_id' => Academic::where('ay_default', true)->first()->id,
                'custom_hours' => $this->type === 'special' ? $course->custom_hours : $course->required_hours,
                'student_type' => $this->type,
                'status' => 'pending'
            ]);

            // Send credentials email
            Mail::to($user->email)->send(new StudentCredentials($user->email, $password));

            DB::commit();
            
            $this->dispatch('alert', type: 'success', text: 'Student added successfully.');
            $this->dispatch('refreshStudents');
            $this->dispatch('closeModal');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error adding student:', [
                'error' => $e->getMessage(),
                'data' => $this->all()
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error adding student.');
        }
    }

    public function render()
    {
        return view('livewire.admin.add-student-modal');
    }
}
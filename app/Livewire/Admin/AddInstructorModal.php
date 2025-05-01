<?php

namespace App\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Section;
use App\Models\Program;
use App\Models\Handle;
use App\Models\Academic;
use Illuminate\Support\Str;
use App\Mail\InstructorCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AddInstructorModal extends ModalComponent
{
    public $instructorId;
    public $firstName;
    public $middleName;
    public $lastName;
    public $suffix;
    public $email;
    public $contact;
    public $isProgramHead = false;
    public $courseCodes = [];
    public $handlingSections = [];
    
    // For dropdowns
    public $availableCourses = [];
    public $availableSections = [];
    public $filteredSections = [];
    public $courseSearch = '';
public $sectionSearch = '';

protected $rules = [
    'instructorId' => 'required|unique:instructors,instructor_id',
    'firstName' => 'required|string|max:255',
    'middleName' => 'nullable|string|max:255',
    'lastName' => 'required|string|max:255',
    'suffix' => 'nullable|string|max:10',
    'email' => 'required|email|unique:users,email',
    'contact' => 'required|string|max:20',
    'courseCodes' => 'required_if:isProgramHead,true|array',
    'handlingSections' => 'required_unless:isProgramHead,true|array' // Changed this line
];

public function mount()
{
    $currentAcademic = Academic::where('ay_default', true)->first();
    
    // Get courses that don't have program heads for current academic year
    $this->availableCourses = Course::whereDoesntHave('instructorCourses', function($query) use ($currentAcademic) {
        $query->where('academic_year_id', $currentAcademic->id);
    })->orderBy('course_code')->get();
    
    $this->loadAvailableSections();
}
    public function updatedCourseCodes($value)
{
    if ($this->isProgramHead && !empty($this->courseCodes)) {
        $this->loadAvailableSections();
    }
}


public function getFilteredCoursesProperty()
{
    if (empty($this->courseSearch)) {
        return $this->availableCourses;
    }
    
    return collect($this->availableCourses)
        ->filter(function($course) {
            return str_contains(strtolower($course->course_code), strtolower($this->courseSearch)) ||
                   str_contains(strtolower($course->course_name), strtolower($this->courseSearch));
        })->values();
}
public function getFilteredSectionsProperty()
{
    if (empty($this->availableSections)) {
        return [];
    }

    $sections = collect($this->availableSections);
    
    if (empty($this->sectionSearch)) {
        return $sections;
    }
    
    return $sections->filter(function($section) {
        return str_contains(strtolower($section['name']), strtolower($this->sectionSearch));
    })->values();
}


public function loadAvailableSections()
{
    $currentAcademic = Academic::where('ay_default', true)->first();

    if (!$currentAcademic) {
        $this->availableSections = [];
        return;
    }

    $query = Section::with('course');

    if (!$this->isProgramHead) {
        $query->whereDoesntHave('handle', function($q) use ($currentAcademic) {
            $q->where('academic_year_id', $currentAcademic->id);
        });
    }

    if ($this->isProgramHead && !empty($this->courseCodes)) {
        $query->whereIn('course_id', $this->courseCodes);
    }

    $sections = $query->get();

    $mapped = $sections->map(function ($section) {
        return [
            'id' => $section->id,
            'name' => optional($section->course)->course_code . '-' . $section->year_level . $section->class_section,
            'course_code' => optional($section->course)->course_code,
            'section' => $section->year_level . $section->class_section,
        ];
    });

    // Apply search filtering directly here
    if (!empty($this->sectionSearch)) {
        $search = strtolower(trim($this->sectionSearch));
        $mapped = $mapped->filter(function ($section) use ($search) {
            return str_contains(strtolower($section['name']), $search);
        })->values();
    }

    $this->availableSections = $mapped->toArray();

    // Sync handling sections
    $availableIds = array_column($this->availableSections, 'id');
    $this->handlingSections = array_intersect($this->handlingSections, $availableIds);
}

public function updatedSectionSearch()
{
    $this->loadAvailableSections();
}


public function updatedIsProgramHead($value)
{
    // Reload sections when program head status changes.
    $this->loadAvailableSections();
}
    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Generate password
            $password = strtolower(str_replace(' ', '', $this->lastName)) . '_' . Str::random(8);

            // Create user
            $user = User::create([
                'email' => $this->email,
                'password' => bcrypt($password),
                'role' => 'instructor',
                'email_verified_at' => now(),
                'is_verified' => true
            ]);

            // Create instructor
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'instructor_id' => $this->instructorId,
                'first_name' => $this->firstName,
                'middle_name' => $this->middleName,
                'last_name' => $this->lastName,
                'suffix' => $this->suffix,
                'contact' => $this->contact
            ]);

            $currentAcademic = Academic::where('ay_default', true)->first();

            // Handle program head assignments
            if ($this->isProgramHead && !empty($this->courseCodes)) {
                foreach ($this->courseCodes as $courseId) {
                    $course = Course::find($courseId);
                    
                    // Check if course already has a program head
                    if (Program::where('course_id', $courseId)
                        ->where('academic_year_id', $currentAcademic->id)
                        ->exists()) {
                        throw new \Exception("Course {$course->course_code} already has a program head.");
                    }
                    
                    Program::create([
                        'instructor_id' => $instructor->id,
                        'course_id' => $courseId,
                        'academic_year_id' => $currentAcademic->id,
                        'is_verified' => true
                    ]);
                }
            }

            // Handle section assignments
            if (!empty($this->handlingSections)) {
                foreach ($this->handlingSections as $sectionId) {
                    Handle::create([
                        'instructor_id' => $instructor->id,
                        'year_section_id' => $sectionId,
                        'academic_year_id' => $currentAcademic->id,
                        'is_verified' => true
                    ]);
                }
            }

            // Send credentials email
            Mail::to($user->email)->send(new InstructorCredentials($user->email, $password));

            DB::commit();
            
            $this->dispatch('alert', type: 'success', text: 'Instructor added successfully.');
            $this->dispatch('refreshInstructors');
            $this->dispatch('closeModal');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', text: 'Error adding instructor: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.add-instructor-modal');
    }
}
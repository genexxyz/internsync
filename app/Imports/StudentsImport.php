<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Section;
use App\Models\Deployment;
use Illuminate\Support\Str;
use App\Mail\StudentCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class StudentsImport
{
    protected $academic_id;
    public $errors = [];

    public function __construct($academic_id)
    {
        $this->academic_id = $academic_id;
    }

    public function import($filepath)
    {
        $handle = fopen($filepath, 'r');
        $headers = array_map('strtolower', fgetcsv($handle));
        $rowNumber = 2;

        while (($row = fgetcsv($handle)) !== false) {
            DB::beginTransaction();
            try {
                if (count($row) !== count($headers)) {
                    $this->errors[] = "Row {$rowNumber}: Column count doesn't match headers";
                    $rowNumber++;
                    continue;
                }

                $data = array_combine($headers, $row);
                
                if (!$this->validateRow($data, $rowNumber)) {
                    $rowNumber++;
                    continue;
                }

                // Check existing email/student ID
                if (User::where('email', $data['email'])->exists()) {
                    $this->errors[] = "Row {$rowNumber}: Email {$data['email']} already exists.";
                    $rowNumber++;
                    continue;
                }

                if (Student::where('student_id', $data['student_id'])->exists()) {
                    $this->errors[] = "Row {$rowNumber}: Student ID {$data['student_id']} already exists.";
                    $rowNumber++;
                    continue;
                }

                // Parse course section (e.g., BSIT-4A)
                $parts = explode('-', trim($data['course_section']));
                if (count($parts) !== 2) {
                    throw new \Exception("Invalid section format. Use format: COURSECODE-SECTION (e.g., BSIT-4A)");
                }

                $courseCode = $parts[0];
                $sectionPart = $parts[1];

                // Get year level and section
                preg_match('/(\d+)([A-Z]+)/', $sectionPart, $matches);
                if (count($matches) !== 3) {
                    throw new \Exception("Invalid section format. Use format: YEARLEVELSECTION (e.g., 4A)");
                }

                $yearLevel = $matches[1];
                $classSection = $matches[2];

                // Find course and section
                $course = Course::where('course_code', $courseCode)->first();
                if (!$course) {
                    throw new \Exception("Course code {$courseCode} not found.");
                }

                $section = Section::where('course_id', $course->id)
                    ->where('year_level', $yearLevel)
                    ->where('class_section', $classSection)
                    ->first();

                if (!$section) {
                    throw new \Exception("Section {$data['course_section']} not found.");
                }

                // Generate password
                $password = strtolower(str_replace(' ', '', $data['last_name'])) . '_' . Str::random(8);

                // Create user
                $user = User::create([
                    'email' => $data['email'],
                    'password' => bcrypt($password),
                    'role' => 'student',
                    'email_verified_at' => now(),
                    'is_verified' => true,
                ]);

                // Create student
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_id' => $data['student_id'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? '',
                    'last_name' => $data['last_name'],
                    'suffix' => $data['suffix'] ?? '',
                    'contact' => $data['contact'],
                    'address' => $data['address'],
                    'year_section_id' => $section->id
                ]);

                // Create deployment record
                Deployment::create([
                    'student_id' => $student->id,
                    'year_section_id' => $section->id,
                    'academic_id' => $this->academic_id,
                    'custom_hours' => $data['type'] === 'special' ? $course->custom_hours : $course->required_hours,
                    'student_type' => $data['type'],
                    'status' => 'pending'
                ]);

                // Send credentials email
                Mail::to($user->email)->send(new StudentCredentials($user->email, $password));

                DB::commit();
                

            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                logger()->error('Student import error:', [
                    'row' => $row,
                    'error' => $e->getMessage(),
                    'data' => $data ?? null
                ]);
            }
            $rowNumber++;
        }

        fclose($handle);
    }

    protected function validateRow($data, $rowNumber)
{
    $required = ['student_id', 'email', 'first_name', 'last_name', 'contact', 'address', 'course_section', 'type'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $this->errors[] = "Row {$rowNumber}: {$field} is required.";
            return false;
        }
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $this->errors[] = "Row {$rowNumber}: Invalid email format.";
        return false;
    }

    // Extract course code before type validation
    $parts = explode('-', trim($data['course_section']));
    if (count($parts) !== 2) {
        $this->errors[] = "Row {$rowNumber}: Invalid section format. Use format: COURSECODE-SECTION (e.g., BSIT-4A)";
        return false;
    }
    $courseCode = $parts[0];

    if (!in_array(strtolower($data['type']), ['regular', 'special'])) {
        $this->errors[] = "Row {$rowNumber}: Type must be either 'regular' or 'special'.";
        return false;
    }

    // Check if special type is allowed for the course
    if (strtolower($data['type']) === 'special') {
        $course = Course::where('course_code', $courseCode)->first();
        if (!$course) {
            $this->errors[] = "Row {$rowNumber}: Course code {$courseCode} not found.";
            return false;
        }
        if (!$course->allows_custom_hours) {
            $this->errors[] = "Row {$rowNumber}: Course {$courseCode} does not allow special type students.";
            return false;
        }
        // Validate minimum custom hours
        if ($course->custom_hours < 200) {
            $this->errors[] = "Row {$rowNumber}: Custom hours for {$courseCode} must be at least 200 hours.";
            return false;
        }
    } else {
        // Validate minimum required hours for regular students
        $course = Course::where('course_code', $courseCode)->first();
        if (!$course) {
            $this->errors[] = "Row {$rowNumber}: Course code {$courseCode} not found.";
            return false;
        }
        if ($course->required_hours < 200) {
            $this->errors[] = "Row {$rowNumber}: Required hours for {$courseCode} must be at least 200 hours.";
            return false;
        }
    }

    return true;
}
}
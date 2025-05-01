<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Section;
use App\Models\Program;
use App\Models\Handle;
use Illuminate\Support\Str;
use App\Mail\InstructorCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class InstructorsImport
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
        $headers = array_map('strtolower', fgetcsv($handle)); // Convert headers to lowercase
        $rowNumber = 2; // Start from row 2 (after headers)

        while (($row = fgetcsv($handle)) !== false) {
            DB::beginTransaction();
            try {
                // Make sure we have enough columns
                if (count($row) !== count($headers)) {
                    $this->errors[] = "Row {$rowNumber}: Column count doesn't match headers";
                    $rowNumber++;
                    continue;
                }

                // Create associative array from headers and row data
                $data = array_combine($headers, $row);
                
                if (!$this->validateRow($data, $rowNumber)) {
                    $rowNumber++;
                    continue;
                }

                // Check if email already exists
                if (User::where('email', $data['email'])->exists()) {
                    $this->errors[] = "Row {$rowNumber}: Email {$data['email']} already exists.";
                    $rowNumber++;
                    continue;
                }

                // Check if instructor_id already exists
                if (Instructor::where('instructor_id', $data['instructor_id'])->exists()) {
                    $this->errors[] = "Row {$rowNumber}: Instructor ID {$data['instructor_id']} already exists.";
                    $rowNumber++;
                    continue;
                }

                // Generate password
                $password = strtolower(str_replace(' ', '', $data['last_name'])) . '_' . Str::random(8);

                // Create user
                $user = User::create([
                    'email' => $data['email'],
                    'password' => bcrypt($password),
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                    'is_verified' => true,
                    
                ]);

                // Create instructor
                $instructor = Instructor::create([
                    'user_id' => $user->id,
                    'instructor_id' => $data['instructor_id'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? '',
                    'last_name' => $data['last_name'],
                    'suffix' => $data['suffix'] ?? '',
                    'contact' => $data['contact']
                ]);

                // Handle program head assignments - Allow multiple courses
                if (strtoupper($data['is_program_head']) === 'YES') {
                    $courseCodes = array_map('trim', explode(',', $data['course_code']));
                    
                    foreach ($courseCodes as $courseCode) {
                        $course = Course::where('course_code', $courseCode)->first();
                        if (!$course) {
                            throw new \Exception("Course code {$courseCode} not found.");
                        }
                        
                        // Check if course already has a program head for this academic year
                        $existingHead = Program::where('course_id', $course->id)
                            ->where('academic_year_id', $this->academic_id)
                            ->first();
                            
                        if ($existingHead) {
                            $existingInstructor = Instructor::find($existingHead->instructor_id);
                            throw new \Exception("Course {$courseCode} already has a program head: {$existingInstructor->getFullNameAttribute()}.");
                        }
                        
                        Program::create([
                            'instructor_id' => $instructor->id,
                            'course_id' => $course->id,
                            'academic_year_id' => $this->academic_id,
                            'is_verified' => true
                        ]);
                    }
                }

                // Handle section assignments with validation
                if (!empty($data['handling_sections'])) {
                    $sectionCodes = array_map('trim', explode(',', $data['handling_sections']));
                    
                    foreach ($sectionCodes as $sectionCode) {
                        // Parse course code and section from the format BSIS-4A
                        $parts = explode('-', trim($sectionCode));
                        if (count($parts) !== 2) {
                            throw new \Exception("Invalid section format for {$sectionCode}. Use format: COURSECODE-SECTION (e.g., BSIS-4A)");
                        }
                        
                        $courseCode = $parts[0];
                        $sectionPart = $parts[1];
                        
                        // Get year level and section from the section part (e.g., 4A)
                        preg_match('/(\d+)([A-Z]+)/', $sectionPart, $matches);
                        if (count($matches) !== 3) {
                            throw new \Exception("Invalid section format for {$sectionPart}. Use format: YEARLEVELSECTION (e.g., 4A)");
                        }
                        
                        $yearLevel = $matches[1];
                        $classSection = $matches[2];
                        
                        // Find the course
                        $course = Course::where('course_code', $courseCode)->first();
                        if (!$course) {
                            throw new \Exception("Course code {$courseCode} not found.");
                        }
                        
                        // Find the section with course_id
                        $section = Section::where('course_id', $course->id)
                            ->where('year_level', $yearLevel)
                            ->where('class_section', $classSection)
                            ->first();
                            
                        if (!$section) {
                            throw new \Exception("Section {$sectionCode} not found.");
                        }
                        
                        // Check if section is already assigned to another instructor
                        $existingHandle = Handle::where('year_section_id', $section->id)
                            ->where('academic_year_id', $this->academic_id)
                            ->first();
                            
                        if ($existingHandle) {
                            $existingInstructor = Instructor::find($existingHandle->instructor_id);
                            throw new \Exception("Section {$sectionCode} is already assigned to {$existingInstructor->full_name}");
                        }
                        
                        Handle::create([
                            'instructor_id' => $instructor->id,
                            'year_section_id' => $section->id,
                            'academic_year_id' => $this->academic_id,
                            'is_verified' => true
                        ]);
                    }
                }

                // Send credentials email
                Mail::to($user->email)->send(new InstructorCredentials($user->email, $password));

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                logger()->error('Instructor import error:', [
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
        $required = ['instructor_id', 'email', 'first_name', 'last_name', 'contact'];
        
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

        return true;
    }
}
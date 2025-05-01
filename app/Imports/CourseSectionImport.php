<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
class CourseSectionImport
{
    protected $errors = [];
    protected $academic_year_id;

    public function __construct($academic_year_id)
    {
        $this->academic_year_id = $academic_year_id;
    }

    public function import($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('File not found');
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception('Could not open file');
        }

        // Skip header row
        fgetcsv($handle);
        
        $rowNumber = 2; // Start from row 2 (after header)
        $courses = [];

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = [
                    'course_code' => $row[0] ?? '',
                    'course_name' => $row[1] ?? '',
                    'required_hours' => $row[2] ?? '',
                    'allows_custom_hours' => strtolower($row[3] ?? '') === 'YES',
                    'custom_hours' => $row[4] ?? null,
                    'sections' => array_filter(array_map('trim', explode(',', $row[5] ?? '')))
                ];

                if ($this->validateRow($data, $rowNumber)) {
                    $courses[] = $data;
                }

            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: {$e->getMessage()}";
            }
            $rowNumber++;
        }

        fclose($handle);

        if (!empty($this->errors)) {
            return [
                'success' => false,
                'errors' => $this->errors
            ];
        }

        return $this->processValidCourses($courses);
    }

    protected function validateRow($data, $rowNumber)
    {
        if (empty($data['course_code'])) {
            $this->errors[] = "Row {$rowNumber}: Course code is required.";
            return false;
        }

        if (empty($data['course_name'])) {
            $this->errors[] = "Row {$rowNumber}: Course name is required.";
            return false;
        }

        if (!is_numeric($data['required_hours']) || $data['required_hours'] < 200) {
            $this->errors[] = "Row {$rowNumber}: Required hours must be at least 200 hours.";
            return false;
        }

        if ($data['allows_custom_hours'] && (!is_numeric($data['custom_hours']) || $data['custom_hours'] < 200)) {
            $this->errors[] = "Row {$rowNumber}: Custom hours must be at least 200 hours when allowed.";
            return false;
        }

        if (empty($data['sections'])) {
            $this->errors[] = "Row {$rowNumber}: At least one section is required.";
            return false;
        }

        foreach ($data['sections'] as $section) {
            if (!preg_match('/^[A-Z0-9-]+$/', $section)) {
                $this->errors[] = "Row {$rowNumber}: Invalid section format '{$section}'. Use only letters, numbers, and hyphens.";
                return false;
            }
        }

        return true;
    }

    
protected function processValidCourses($courses)
{
    try {
        DB::beginTransaction();

        foreach ($courses as $courseData) {
            $course = Course::create([
                'course_code' => $courseData['course_code'],
                'course_name' => $courseData['course_name'],
                'required_hours' => $courseData['required_hours'],
                'allows_custom_hours' => $courseData['allows_custom_hours'],
                'custom_hours' => $courseData['custom_hours'],
                'academic_year_id' => $this->academic_year_id
            ]);

            foreach ($courseData['sections'] as $sectionName) {
                // Extract year level and letter separately for each section
                preg_match('/^(\d+)([A-Z]+)$/', $sectionName, $matches);
                
                if (count($matches) === 3) {
                    $yearLevel = $matches[1];
                    $sectionLetter = $matches[2];
                } else {
                    // Default values if pattern doesn't match
                    $yearLevel = '1';
                    $sectionLetter = 'A';
                }

                Section::create([
                    'course_id' => $course->id,
                    'year_level' => $yearLevel,
                    'class_section' => $sectionLetter
                ]);
            }
        }

        DB::commit();
        return [
            'success' => true,
            'message' => 'Courses and sections imported successfully'
        ];

    } catch (\Exception $e) {
        DB::rollBack();
        return [
            'success' => false,
            'errors' => ['Failed to import courses: ' . $e->getMessage()]
        ];
    }
}

    public function getErrors()
    {
        return $this->errors;
    }
}
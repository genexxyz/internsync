<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public $class_section;
    public function index(): View{
        $courses = Course::all();
        $breadcrumbs = [
            ['url' => route('admin.courses'), 'label' => 'Courses'], // Link to the users list
            
        ];
        
        return view('admin.courses', compact('courses','breadcrumbs'));
    }

    public function show($course_code, $year_level, $class_section)
    {
        // Fetch the course by its course_code
        $courses = Course::where('course_code', $course_code)->firstOrFail();

        // Fetch sections related to this course
        $section = $courses->sections()
        ->where('course_id', $courses->id)
        ->where('class_section', $class_section)
        ->firstOrFail();


        
        $breadcrumbs = [
            ['url' => route('admin.courses'), 'label' => 'Courses'], 
            ['url' => route('admin.courses.show', $course_code), 'label' => $course_code],
             // Current course page
             ['url' => route('admin.courses.sections.show', [$course_code, $section->year_level, $section->class_section]), 'label' => $section->year_level . $section->class_section],
        ];

        // Return the view with the data
        return view('admin.courses.sections.show', compact('courses', 'section', 'breadcrumbs'));
    }
}

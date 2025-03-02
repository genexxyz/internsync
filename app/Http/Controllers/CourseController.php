<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(): View
    {
        // Fetch all courses
        $courses = Course::all();
        
        // Define breadcrumbs
        $breadcrumbs = [
            ['url' => route('admin.courses'), 'label' => 'Courses'], // Correct link to courses index page
        ];
        
        // Return the view with courses and breadcrumbs data
        return view('admin.courses', compact('courses', 'breadcrumbs'));
    }

    public function show($course_code)
    {
        // Fetch the course by its course_code
        $course = Course::where('course_code', $course_code)->firstOrFail();

        // Fetch sections related to this course
        $sections = Section::where('course_id', $course->id)->get();

        // Define breadcrumbs
        $breadcrumbs = [
            ['url' => route('admin.courses'), 'label' => 'Courses'], // Link to the courses list
            ['url' => route('admin.courses.show', $course_code), 'label' => $course->course_code], // Current course page
        ];

        // Return the view with the course, sections, and breadcrumbs data
        return view('admin.courses.show', compact('course', 'sections', 'breadcrumbs'));
    }
}

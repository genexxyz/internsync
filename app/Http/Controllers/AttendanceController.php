<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function sectionAttendance($course_code, $year_level, $class_section): View
    {
        $course = Course::where('course_code', $course_code)->firstOrFail();
        
        $section = Section::where('course_id', $course->id)
            ->where('year_level', $year_level)
            ->where('class_section', $class_section)
            ->firstOrFail();

        $breadcrumbs = [
            ['url' => route('instructor.taskAttendance'), 'label' => 'Task and Attendance'],
            ['url' => route('instructor.taskAttendance.section.show', [$course_code, $year_level, $class_section]), 
             'label' => $course_code . ' ' . $year_level . $class_section]
        ];

        return view('instructor.task-and-attendance.show', compact('section', 'course', 'breadcrumbs'));
    }
}
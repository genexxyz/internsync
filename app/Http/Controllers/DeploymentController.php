<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeploymentController extends Controller
{
    public function sectionDeployment($course_code, $year_level, $class_section): View
    {
        $course = Course::where('course_code', $course_code)->firstOrFail();

        $section = Section::where('course_id', $course->id)->where('year_level', $year_level)->where('class_section', $class_section)->firstOrFail();
        $students = Student::all();


        $breadcrumbs = [
            ['url' => route('instructor.deployments.section'), 'label' => 'Deployments'],
             // Current course page
             ['url' => route('instructor.deployments.section.show', [$course_code, $year_level, $class_section]), 'label' =>$course_code . ' ' . $year_level . $class_section],
        ];
        return view('instructor.deployments.show', compact('students', 'breadcrumbs', 'section', 'course'));
    }
}

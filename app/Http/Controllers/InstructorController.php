<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Deployment;
use App\Models\Handle;
use App\Models\Instructor;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function index(): View
    {
        return view('instructor.dashboards');
    }

    public function taskAttendance(): View
    {
        $instructor = $this->getInstructor();

    // Initialize query builder
    $sectionsQuery = Section::query()
        ->with(['course', 'students']);

    if ($instructor->instructorCourse && $instructor->instructorCourse->is_verified) {
        // For verified program head - get all sections in their course
        $sectionsQuery->where('course_id', $instructor->instructorCourse->course_id);
    } else {
        // For regular instructors or unverified program heads - get only sections they handle
        $sectionsQuery->whereHas('handles', function($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id)
                ->where('is_verified', true);
        });
    }

    $sections = $sectionsQuery->get();

        return view('instructor.task-and-attendance', [
            'instructor' => $instructor,
        'sections' => $sections,
        'isProgramHead' => (bool) ($instructor->instructorCourse?->is_verified)
        ]);
    }

    public function deployments(): View
{
    $instructor = $this->getInstructor();

    // Initialize query builder
    $sectionsQuery = Section::query()
        ->with(['course', 'students']);

    if ($instructor->instructorCourse && $instructor->instructorCourse->is_verified) {
        // For verified program head - get all sections in their course
        $sectionsQuery->where('course_id', $instructor->instructorCourse->course_id);
    } else {
        // For regular instructors or unverified program heads - get only sections they handle
        $sectionsQuery->whereHas('handles', function($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id)
                ->where('is_verified', true);
        });
    }

    $sections = $sectionsQuery->get();

    return view('instructor.deployments.sections', [
        'instructor' => $instructor,
        'sections' => $sections,
        'isProgramHead' => (bool) ($instructor->instructorCourse?->is_verified)
    ]);
}

private function getInstructor(): Instructor
{
    return Instructor::where('user_id', Auth::id())
        ->with([
            'sections.course',
            'instructorCourse.course',
            'instructorCourse' => function($query) {
                $query->where('is_verified', true);
            }
        ])
        ->firstOrFail();
}
    
}
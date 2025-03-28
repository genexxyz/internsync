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
    $instructor = $this->getInstructor();
    $today = now()->format('Y-m-d');
    
    // Initialize query builders based on role
    if ($instructor->instructorCourse && $instructor->instructorCourse->is_verified) {
        // For Program Head - Get all students in their course
        $studentsQuery = Student::whereHas('section', function($query) use ($instructor) {
            $query->where('course_id', $instructor->instructorCourse->course_id);
        });
        
        $deployedStudents = $studentsQuery->clone()
            ->whereHas('deployment', function($query) {
                $query->where('status', 'ongoing');
            })->count();
            
        $totalStudents = $studentsQuery->count();
        
        // Get companies where students from this course are deployed
        $companies = Deployment::whereHas('student.section', function($query) use ($instructor) {
            $query->where('course_id', $instructor->instructorCourse->course_id);
        })
        ->distinct('supervisor_id')
        ->count('supervisor_id');
        
    } else {
        // For Regular Instructor - Get only students from handled sections
        $studentsQuery = Student::whereHas('section.handles', function($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id)
                ->where('is_verified', true);
        });
        
        $deployedStudents = $studentsQuery->clone()
            ->whereHas('deployment', function($query) {
                $query->where('status', 'ongoing');
            })->count();
            
        $totalStudents = $studentsQuery->count();
        
        // Get companies where handled students are deployed
        $companies = Deployment::whereHas('student.section.handles', function($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id)
                ->where('is_verified', true);
        })
        ->distinct('supervisor_id')
        ->count('supervisor_id');
    }
    
    // Calculate not deployed students
    $notDeployedStudents = $totalStudents - $deployedStudents;
    
    // Get today's attendance for relevant students
    $presentToday = $studentsQuery->clone()
        ->whereHas('attendances', function($query) use ($today) {
            $query->whereDate('date', $today)
                ->where('status', 'regular');
        })->count();
        
    $absentToday = $studentsQuery->clone()
        ->whereHas('attendances', function($query) use ($today) {
            $query->whereDate('date', $today)
                ->where('status', 'absent');
        })->count();
    
    // Get evaluated deployments count
    // $evaluated = $studentsQuery->clone()
    //     ->whereHas('deployment', function($query) {
    //         $query->whereNotNull('evaluation_date');
    //     })->count();

    return view('instructor.dashboards', compact(
        'totalStudents',
        'deployedStudents',
        'notDeployedStudents',
        'presentToday',
        'absentToday',
        'companies',
        // 'evaluated'
    ));
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

        return view('instructor.task-and-attendance.sections', [
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

public function supervisors(): View
    {
        return view('instructor.supervisors');
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
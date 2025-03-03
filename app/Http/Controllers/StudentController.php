<?php

namespace App\Http\Controllers;

use App\Models\AcceptanceLetter;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
{
    public $company;
    public $supervisor;
    public function index(): View
    {
        $student = Auth::user()->student;
        $deployment = $student->deployment;
    
    
        $totalHours = Attendance::getTotalApprovedHours($student->id);
        // Get weekly reports count and latest reports
    $weeklyReports = Report::where('student_id', $student->id)
    ->orderBy('week_number', 'desc')
    ->take(3)
    ->get();

// Get today's attendance
$todayAttendance = Attendance::where('student_id', $student->id)
    ->where('date', now()->toDateString())
    ->first();

// Get attendance count
$attendanceCount = Attendance::where('student_id', $student->id)
    ->where('is_approved', true)
    ->count();

return view('student.dashboard', compact(
    'deployment',
    'totalHours',
    'weeklyReports',
    'todayAttendance',
    'attendanceCount'
));
    }

    public function journey(): View
{
    $student = Auth::user()->student;
    
    $deployment = $student->deployment()
        ->with(['company', 'supervisor'])
        ->first();
        $totalHours = Attendance::getTotalApprovedHours($student->id);
    return view('student.ojt-journey', [
        'student' => $student,
        'deployment' => $deployment,
        'hours_rendered' => $totalHours
    ]);
}



    public function taskAttendance(): View
    {
        return view('student.task-and-attendance');
    }

    public function ojtDocument(): View
{
    $student = Student::where('user_id', Auth::user()->id)->firstOrFail();
    $acceptance_letter = AcceptanceLetter::where('student_id', $student->id)->first();
    
    return view('student.ojt-document', compact('student', 'acceptance_letter'));
}
}

<?php

namespace App\Http\Controllers;

use App\Models\AcceptanceLetter;
use App\Models\Company;
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
        return view('student.dashboard');
    }

    public function journey(): View
{
    // Retrieve the authenticated user's student record directly
    $student = Student::with(['deployment', 'user', 'yearSection'])
        ->where('user_id', Auth::user()->id) // Assuming `student_id` is a column on the `users` table
        ->first();

    // Safely load the company and supervisor associated with the deployment
    $company = $student && $student->deployment 
        ? Company::find($student->deployment->company_id) 
        : null;

    $supervisor = $student && $student->deployment 
        ? Supervisor::find($student->deployment->supervisor_id) 
        : null;

    // Return the view with the necessary data
    return view('student.ojt-journey', compact('student', 'company', 'supervisor'));
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

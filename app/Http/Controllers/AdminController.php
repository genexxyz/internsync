<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Course;
use App\Models\Deployment;
use App\Models\Instructor;
use App\Models\Section;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $courses = Course::all();
        $students = Student::all();
        $instructors = Instructor::all();
        $supervisors = Supervisor::all();
        $companies = Company::all();
        $deployments = Deployment::all()->where('supervisor_id', '!=', null);
        return view('admin.dashboard')
            ->with('courses', $courses)
            ->with('instructors', $instructors)
            ->with('students',  $students)
            ->with('supervisors', $supervisors)
            ->with('companies', $companies)
            ->with('deployments', $deployments);
    }

    public function instructors(): View
    {
        return view('admin.instructors');
    }

    public function supervisors(): View
    {
        return view('admin.supervisors');
    }

    

    

}

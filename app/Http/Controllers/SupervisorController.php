<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use Illuminate\View\View;

class SupervisorController extends Controller
{
    public function index(): View
    {
        return view('supervisor.dashboard');
    }

    public function weeklyReports(): View
    {
        return view('supervisor.weekly-reports');
    }

    public function evaluation(): View
    {
        return view('supervisor.evaluation');
    }
}

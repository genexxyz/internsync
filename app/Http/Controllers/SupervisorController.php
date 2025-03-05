<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupervisorController extends Controller
{

    public function index(): View
    {
$supervisor = Supervisor::where('user_id',Auth::id())->with([
    'deployments'
])
->firstOrFail();;
        
        return view('supervisor.dashboard', [
            'supervisor' => $supervisor
        ]);
    }

    public function interns(): View
    {
        return view('supervisor.interns');
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

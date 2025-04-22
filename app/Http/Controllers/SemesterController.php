<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Academic;

class SemesterController extends Controller
{
    public function notice()
    {
        $currentAcademic = Academic::where('ay_default', true)->first();
        return view('semester-notice', compact('currentAcademic'));
    }
}
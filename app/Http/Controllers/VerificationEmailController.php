<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationEmailController extends Controller
{
    public function verify(): View
    {
        return view('auth.verify-email');
    }
}

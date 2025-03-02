<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $breadcrumbs = [
            ['url' => route('admin.company'), 'label' => 'Companies'], // Correct link to courses index page
        ];
        return view('admin.companies', compact('breadcrumbs'));
    }
}

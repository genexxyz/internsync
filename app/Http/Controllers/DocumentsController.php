<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        return view('admin.documents.acceptance-letters');
    }

    public function endorsement()
    {
        return view('admin.documents.endorsement-letters');
    }

    public function moa()
    {
        return view('admin.documents.moa');
    }
}

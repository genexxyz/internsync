<?php
// filepath: /opt/lampp/htdocs/internsync/app/Http/Controllers/TestUploadController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestUploadController extends Controller
{
    public function showForm()
    {
        return view('test-upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('document')->store('documents', 'public');

        return back()->with('success', 'File uploaded successfully')->with('path', $path);
    }
}
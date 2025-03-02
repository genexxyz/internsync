<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // Validate input
    $request->validate([
        'first_name' => ['required', 'regex:/^[a-zA-Z\s.]+$/'],
        'middle_name' => ['nullable', 'regex:/^[a-zA-Z\s.]+$/'],
        'last_name' => ['required', 'regex:/^[a-zA-Z\s.]+$/'],
        'suffix' => ['nullable', 'regex:/^[a-zA-Z\s.]+$/'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required'],
    ], [
        'first_name.regex' => 'First name may only contain letters, spaces, and periods.',
        'middle_name.regex' => 'Middle name may only contain letters, spaces, and periods.',
        'last_name.regex' => 'Last name may only contain letters, spaces, and periods.',
        'suffix.regex' => 'Suffix may only contain letters, spaces, and periods.',
    ]);

    // Create the User first
    $user = User::create([
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
    ]);

    // Attach User ID as foreign key in respective role-specific table
    if ($request->role == 'student') {
        Student::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
        ]);
    } elseif ($request->role == 'instructor') {
        Instructor::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
        ]);
    } elseif ($request->role == 'supervisor') {
        Supervisor::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
        ]);
    } elseif ($request->role == 'admin') {
        Admin::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
        ]);
    }

    // Fire registration event
    event(new Registered($user));

    // Log in the user
    Auth::login($user);

    // Redirect to the dashboard (or email verification route if necessary)
    $user = Auth::user();

        // Redirect based on user role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default => redirect('/unauthorized'),
        };
}


}

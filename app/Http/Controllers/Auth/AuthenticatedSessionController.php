<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        if (Auth::check()) {
            return redirect()->route(match (Auth::user()->role) {
                'admin' => 'admin.dashboard',
                'student' => 'student.dashboard',
                'instructor' => 'instructor.dashboard',
                'supervisor' => 'supervisor.dashboard',
                default => 'unauthorized',
            });
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Get the authenticated user
        $user = Auth::user();
        $checkEmail = User::where('email', $user->email)->first();
        if (!$checkEmail->isEmailVerified()) {
            Auth::logout();
            return redirect()->route('verify.email', ['email' => $user->email])
                ->with('message', 'Please verify your email address before logging in.');
        }
        // Redirect based on user role if the form is completed
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default => redirect('/unauthorized'),
        };
    }

    return back()->withErrors([
        'email' => 'Invalid login credentials.',
    ]);
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        Auth::guard('web')->logout();
        
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect('/');

    }
}

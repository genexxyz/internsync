<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SupervisorFirstLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'supervisor') {
            $user = Auth::user();
            $supervisor = $user->supervisor;
            
            // Check if this is their first login or profile is incomplete
            if ($supervisor && !$supervisor->is_profile_complete) {
                return redirect()->route('supervisor.complete-form');
            }
        }
        
        return $next($request);
    }
}
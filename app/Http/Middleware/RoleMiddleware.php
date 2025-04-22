<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Academic;
use App\Models\Program;
use App\Models\Handle;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    protected $excludedRoutes = [
        'login',
        'logout',
        'semester.notice',
        'profile.edit',
        'profile.update',
        'unauthorized',
        '404'
    ];

    public function handle($request, Closure $next, $role)
    {
        // First check if user is authenticated and has correct role
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect()->route('404');
        }

        // Skip semester check for excluded routes and admin role
        if (in_array($request->route()->getName(), $this->excludedRoutes) || $role === 'admin') {
            return $next($request);
        }

        // Get current academic year
        $currentAcademic = Academic::where('ay_default', true)->first();

        if (!$currentAcademic) {
            return redirect()->route('semester.notice');
        }

        // Get user's academic year based on role
        $userAcademicId = $this->getUserAcademicId(Auth::user());

        if ($userAcademicId && $userAcademicId !== $currentAcademic->id) {
            return redirect()->route('semester.notice');
        }

        return $next($request);
    }

    private function getUserAcademicId($user)
    {
        return match ($user->role) {
            'student' => $user->student->deployment?->academic_id,
            'instructor' => $this->getInstructorAcademicId($user->instructor),
            'supervisor' => $user->supervisor->deployments()->latest()->first()?->academic_id,
            default => null
        };
    }

    private function getInstructorAcademicId($instructor)
    {
        // Check instructor_courses first
        $programAcademic = Program::where('instructor_id', $instructor->id)
            ->latest()
            ->first()?->academic_year_id;

        if ($programAcademic) {
            return $programAcademic;
        }

        // If no program found, check instructor_sections
        $handleAcademic = Handle::where('instructor_id', $instructor->id)
            ->latest()
            ->first()?->section?->academic_id;

        return $handleAcademic;
    }
}
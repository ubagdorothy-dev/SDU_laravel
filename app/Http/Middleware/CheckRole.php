<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // If no roles specified, allow access
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role matches any of the specified roles
        foreach ($roles as $role) {
            if ($user->role == $role) {
                return $next($request);
            }
        }

        // If no match found, redirect to appropriate dashboard based on role
        switch ($user->role) {
            case 'unit director':
            case 'unit_director':
                return redirect()->route('admin.dashboard');
            case 'head':
                return redirect()->route('office_head.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                return redirect('login');
        }
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If user is heading to generic /dashboard, redirect to role-specific
            if ($request->routeIs('dashboard')) {
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('donor')) {
                    return redirect()->route('donor.dashboard');
                } elseif ($user->hasRole('hospital')) {
                    return redirect()->route('hospital.dashboard');
                }
            }
        }

        return $next($request);
    }
}

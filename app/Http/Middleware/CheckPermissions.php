<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
    // This middleware checks if the authenticated user has the required permission.
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Redirect if the user is not authenticated.
        if (!Auth::check()) {
            return redirect()->route('landing');
        }

        // Redirect if the user lacks the needed permission.
        if (!Auth::user()->hasAccess($permission)) {
            return redirect()->route('landing');
        }

        // Proceed with the request.
        return $next($request);
    }
}

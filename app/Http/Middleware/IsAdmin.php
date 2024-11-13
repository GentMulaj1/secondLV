<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the logged-in user is an admin
        if (Auth::user() && Auth::user()->role === 'admin') {
            return $next($request);  // Proceed if user is admin
        }
        return response()->json(['error' => 'Unauthorized'], 403);  // Deny if not an admin
    }
}
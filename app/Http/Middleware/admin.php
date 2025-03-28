<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Redirect to login if not authenticated
            return redirect()->route('login');
        }

        if (Auth::user()->role == '1') {
            // Proceed if the user is a superadmin
            return $next($request);
        }

        // Logout and redirect if the user is not authorized
        Auth::logout();
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
    
}

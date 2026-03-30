<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // User is logged in, proceed with the request
            $response = $next($request);

            // Add CORS header
            return $response->header('Access-Control-Allow-Origin', '*');
        }

        // If the user is not logged in, redirect to login page
        return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
    }
}

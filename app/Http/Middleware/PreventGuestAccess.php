<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventGuestAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in via your Auth check or custom session
        if (Auth::check() || session()->has('logged_in_id')) {
            $role = session('logged_in_role');
            
            // Redirect them back to their respective dashboard instead of showing login
            if ($role === 'admin') {
                return redirect()->route('adminhome');
            }
            return redirect()->route('userhome');
        }

        $response = $next($request);

        // Prevent browser caching on guest pages (Login/Register)
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized access');
        }

        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}


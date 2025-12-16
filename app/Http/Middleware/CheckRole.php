<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles (e.g., 'admin', 'peternak_premium')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user has one of the allowed roles
        if (!in_array($request->user()->role, $roles)) {
            return redirect()->route('dashboard')->withErrors([
                'access' => 'Anda tidak memiliki akses ke halaman ini.'
            ]);
        }

        return $next($request);
    }
}

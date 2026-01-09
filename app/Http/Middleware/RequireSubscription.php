<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Admin always has access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has active premium subscription
        if ($user->hasActivePremium()) {
            return $next($request);
        }

        // Check if user is on trial
        if ($user->isOnTrial()) {
            return $next($request);
        }

        // No active subscription - redirect to subscription page
        return redirect()->route('langganan')->with('warning', 'Silakan pilih paket langganan untuk mengakses fitur ini.');
    }
}

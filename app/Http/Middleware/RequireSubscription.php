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

        // ALWAYS allow access to Settings and Subscription pages
        // Users need to access these even when locked to renew subscription
        if ($request->routeIs('settings.*') || $request->routeIs('langganan.*')) {
            return $next($request);
        }

        // Check if user has active premium subscription
        if ($user->hasActivePremium()) {
            $request->attributes->set('read_only_mode', false);
            return $next($request);
        }

        // Check if user is on trial
        if ($user->isOnTrial()) {
            $request->attributes->set('read_only_mode', false);
            return $next($request);
        }

        // === EXPIRED SUBSCRIPTION HANDLING ===

        // Hard lock check (30+ days expired)
        // Completely block access and redirect to subscription page
        if ($user->isHardLocked()) {
            return redirect()->route('langganan.index')
                ->with('error', 'Langganan Anda telah berakhir lebih dari 30 hari. Silakan perpanjang untuk melanjutkan menggunakan FarmGo.');
        }

        // Read-only mode (4-30 days expired)
        // Allow viewing data but block create/edit/delete operations
        if ($user->isReadOnlyMode()) {
            // Block write operations (POST, PUT, PATCH, DELETE)
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Exception: Allow export routes even in read-only mode
                if (!$request->routeIs('ekspor.*')) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Akun dalam mode baca saja. Perpanjang langganan untuk melanjutkan.'
                        ], 403);
                    }

                    return back()->with('error', 'Akun dalam mode baca saja. Perpanjang langganan untuk melanjutkan.');
                }
            }

            // Allow read operations (GET) - set flag for UI to disable buttons
            $request->attributes->set('read_only_mode', true);
            return $next($request);
        }

        // Grace period (0-3 days after expiry)
        // Full access but with warning banners
        if ($user->isInGracePeriod()) {
            $request->attributes->set('read_only_mode', false);
            return $next($request);
        }

        // No active subscription and not in any grace/trial period
        return redirect()->route('langganan.index')
            ->with('warning', 'Silakan pilih paket langganan untuk mengakses fitur ini.');
    }
}

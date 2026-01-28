<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    /**
     * Show the email verification notice page.
     */
    public function notice()
    {
        // If already verified, redirect to dashboard
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email-notice');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        // Find the user by ID
        $user = \App\Models\User::findOrFail($id);

        // Verify the hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email Anda sudah terverifikasi sebelumnya. Silakan login untuk melanjutkan.');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Logout if currently logged in (for security)
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan ke dashboard.');
    }

    /**
     * Resend the email verification notification (without authentication).
     */
    public function resend(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Rate limiting: max 3 requests per 5 minutes per email + IP
        $key = 'resend-verification:' . $request->ip() . ':' . $request->email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Terlalu banyak permintaan. Silakan coba lagi dalam {$minutes} menit.",
            ])->withInput();
        }

        // Get user
        $user = \App\Models\User::where('email', $request->email)->first();

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Email sudah terverifikasi. Silakan login.',
            ])->withInput();
        }

        // Check if Google user
        if ($user->google_id) {
            return back()->withErrors([
                'email' => 'Akun ini terdaftar dengan Google. Silakan login menggunakan tombol "Masuk dengan Google" di halaman login.',
            ])->withInput();
        }

        // Send verification email (always send when manually requested)
        $user->sendEmailVerificationNotification();

        RateLimiter::hit($key, 300); // 5 minutes decay

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email verifikasi telah dikirim!'
            ]);
        }

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda! Silakan cek inbox atau folder spam.');
    }
}

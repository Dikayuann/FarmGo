<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Rate limiting: max 3 requests per hour per email
        $key = 'forgot-password:' . $request->ip() . ':' . $request->email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Terlalu banyak permintaan reset password. Silakan coba lagi dalam {$minutes} menit."
            ]);
        }

        $validationRules = [
            'email' => 'required|email|exists:users,email',
        ];

        $validationMessages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ];

        // Only validate Turnstile in production
        if (config('app.env') === 'production') {
            $validationRules['cf-turnstile-response'] = ['required', new Turnstile];
            $validationMessages['cf-turnstile-response.required'] = 'Silakan selesaikan verifikasi Cloudflare Turnstile terlebih dahulu.';
        }

        $request->validate($validationRules, $validationMessages);

        // Check if user is a Google OAuth user (no password set)
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->google_id !== null) {
            return back()->withErrors([
                'email' => 'Akun Anda terdaftar melalui Google. Silakan login menggunakan tombol "Masuk dengan Google" di halaman login.'
            ])->withInput();
        }

        // Check if email is verified
        if ($user && !$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Email Anda belum diverifikasi. Silakan verifikasi email terlebih dahulu sebelum reset password.'
            ])->withInput();
        }

        // Send password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Hit rate limiter
        RateLimiter::hit($key, 3600); // 1 hour decay

        if ($status === Password::RESET_LINK_SENT) {
            // Mask email for privacy (e.g., te***@example.com)
            $maskedEmail = $this->maskEmail($request->email);

            return back()->with([
                'success' => 'Link reset password telah dikirim!',
                'masked_email' => $maskedEmail
            ]);
        }

        return back()->withErrors([
            'email' => 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.'
        ]);
    }

    /**
     * Mask email for privacy (e.g., test@example.com -> te***@example.com)
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        // Show first 2 characters, mask the rest
        $nameLength = strlen($name);
        if ($nameLength <= 2) {
            $masked = $name[0] . '***';
        } else {
            $visibleChars = min(2, $nameLength);
            $masked = substr($name, 0, $visibleChars) . '***';
        }

        return $masked . '@' . $domain;
    }
}

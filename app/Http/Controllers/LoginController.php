<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class LoginController extends Controller
{
    /**
     * Maximum login attempts before lockout
     */
    protected int $maxAttempts = 3;

    /**
     * Lockout duration in seconds (5 minutes)
     */
    protected int $decaySeconds = 300;

    /**
     * Get the rate limiter key for the given request
     * Uses email + IP so different IPs can't bypass each other
     */
    protected function throttleKey(Request $request): string
    {
        return 'login-attempt:' . Str::transliterate(
            Str::lower($request->input('email', '')) . '|' . $request->ip()
        );
    }

    /**
     * Format remaining lockout time as human-readable string
     */
    protected function formatLockoutTime(int $seconds): string
    {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;

        if ($minutes > 0 && $secs > 0) {
            return "{$minutes} menit {$secs} detik";
        } elseif ($minutes > 0) {
            return "{$minutes} menit";
        } else {
            return "{$secs} detik";
        }
    }

    public function view()
    {
        // If user is already authenticated, redirect to appropriate page
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect('/admin');
            }

            if (!$user->hasActivePremium() && !$user->isOnTrial()) {
                return redirect()->route('langganan.index')
                    ->with('info', 'Silakan pilih paket langganan untuk melanjutkan.');
            }

            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        // Check if rate limited (too many failed attempts)
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $timeRemaining = $this->formatLockoutTime($seconds);

            // Record lockout attempt in history
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $this->recordLoginHistory($user, $request, 'locked');
            }

            return back()->withErrors([
                'email' => 'Akun terkunci karena terlalu banyak percobaan login gagal.',
                'info' => "Silakan tunggu {$timeRemaining} lagi untuk mencoba kembali.",
            ])->withInput($request->only('email'))
              ->with('lockout_seconds', $seconds);
        }

        // Validasi input
        $validationRules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validationMessages = [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ];

        // Only validate Turnstile in production
        if (config('app.env') === 'production') {
            $validationRules['cf-turnstile-response'] = ['required', new Turnstile];
            $validationMessages['cf-turnstile-response.required'] = 'Silakan selesaikan verifikasi Cloudflare Turnstile terlebih dahulu.';
        }

        $request->validate($validationRules, $validationMessages);

        // Cek apakah user dengan email tersebut ada
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Email tidak terdaftar — don't count as failed attempt to avoid enumeration
            return back()->withErrors([
                'email' => 'Email tidak terdaftar. Silakan daftar terlebih dahulu.'
            ])->withInput($request->only('email'));
        }

        // Validate email domain (block temporary/disposable emails)
        $emailValidator = new \App\Rules\NotDisposableEmail();
        if (!$emailValidator->passes('email', $request->email)) {
            return back()->withErrors([
                'email' => 'Email temporary/disposable tidak diperbolehkan. Akun Anda telah diblokir.'
            ])->withInput($request->only('email'));
        }

        // Cek apakah user terdaftar via Google (password = null)
        if ($user->google_id && !$user->password) {
            return back()->withErrors([
                'email' => 'Akun ini terdaftar dengan Google. Silakan login menggunakan tombol "Masuk dengan Google".'
            ])->withInput($request->only('email'));
        }

        // Attempt login dengan email dan password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Regenerate session untuk security
            $request->session()->regenerate();

            // Check role dan redirect sesuai role
            $user = Auth::user();

            // Record successful login history
            $this->recordLoginHistory($user, $request, 'success');

            if ($user->isAdmin()) {
                return redirect('/admin');
            }

            // Check if email is verified (skip for Google users)
            if (!$user->google_id && !$user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verification.notice')
                    ->with('email', $request->email);
            }

            // Check if user has active subscription
            if (!$user->hasActivePremium() && !$user->isOnTrial()) {
                return redirect()->route('langganan.index')
                    ->with('info', 'Silakan pilih paket langganan untuk mulai menggunakan FarmGo.');
            }

            return redirect()->intended(route('dashboard'));
        }

        // Password salah — increment rate limiter
        RateLimiter::hit($key, $this->decaySeconds);

        $currentAttempts = RateLimiter::attempts($key);
        $attemptsLeft = $this->maxAttempts - $currentAttempts;

        // Record failed login attempt
        $this->recordLoginHistory($user, $request, 'failed');

        // Account just got locked
        if ($attemptsLeft <= 0) {
            $seconds = RateLimiter::availableIn($key);
            $timeRemaining = $this->formatLockoutTime($seconds);

            return back()->withErrors([
                'password' => 'Password salah.',
                'info' => "Akun terkunci selama {$timeRemaining} karena 3x percobaan gagal.",
            ])->withInput($request->only('email'))
              ->with('lockout_seconds', $seconds);
        }

        // Still has attempts left
        return back()->withErrors([
            'password' => 'Password salah.',
            'info' => "Sisa percobaan: {$attemptsLeft}. Setelah 3x gagal, akun akan terkunci selama 5 menit.",
        ])->withInput($request->only('email'));
    }

    /**
     * Record login history with device and location information
     */
    protected function recordLoginHistory($user, Request $request, $status = 'success')
    {
        try {
            $detector = new \App\Services\DeviceDetector();
            $deviceInfo = $detector->detect();

            \App\Models\LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $deviceInfo['device_type'],
                'device_name' => $deviceInfo['device_name'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'login_status' => $status,
                'login_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to record login history: ' . $e->getMessage());
        }
    }
}


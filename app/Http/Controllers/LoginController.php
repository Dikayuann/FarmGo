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

        // Cek apakah akun sedang terkunci
        if (session('login_locked_until')) {
            $lockedUntil = session('login_locked_until');
            if (now()->lt($lockedUntil)) {
                $remainingMinutes = now()->diffInMinutes($lockedUntil) + 1;
                return redirect()->back()->withErrors([
                    'email' => "Akun Anda terkunci. Silakan coba lagi dalam {$remainingMinutes} menit."
                ]);
            } else {
                // Reset jika waktu lockout sudah habis
                session()->forget(['login_attempts', 'login_locked_until']);
            }
        }

        return view('login');
    }

    /**
     * Clear login lockout (for development/testing purposes)
     */
    public function clearLockout()
    {
        session()->forget(['login_attempts', 'login_locked_until']);
        return redirect('/login')->with('success', 'Login attempts telah direset. Silakan coba login kembali.');
    }

    public function login(Request $request)
    {
        // Cek lockout terlebih dahulu
        if (session('login_locked_until')) {
            $lockedUntil = session('login_locked_until');
            if (now()->lt($lockedUntil)) {
                $remainingMinutes = now()->diffInMinutes($lockedUntil) + 1;
                return back()->withErrors([
                    'email' => "Terlalu banyak percobaan login gagal. Akun Anda terkunci selama {$remainingMinutes} menit."
                ])->withInput($request->only('email'));
            } else {
                // Reset jika waktu lockout sudah habis
                session()->forget(['login_attempts', 'login_locked_until']);
            }
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
            // Email tidak terdaftar - langsung beri tahu tanpa rate limiting
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
                'email' => 'Akun ini terdaftar dengan Google. Silakan login menggunakan tombol Google.'
            ])->withInput($request->only('email'));
        }

        // Attempt login dengan email dan password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Reset login attempts on successful login
            session()->forget(['login_attempts', 'login_locked_until']);

            // Regenerate session untuk security
            $request->session()->regenerate();

            // Check role dan redirect sesuai role
            $user = Auth::user();

            // Record successful login history
            $this->recordLoginHistory($user, $request, 'success');

            if ($user->isAdmin()) {
                // Admin redirect ke admin panel
                return redirect('/admin');
            }

            // Check if email is verified (skip for Google users)
            if (!$user->google_id && !$user->hasVerifiedEmail()) {
                // CRITICAL: Logout user to prevent bypass
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verification.notice')
                    ->with('email', $request->email);
            }

            // Check if user has active subscription
            if (!$user->hasActivePremium() && !$user->isOnTrial()) {
                // Redirect to subscription page if no active subscription
                return redirect()->route('langganan.index')->with('info', 'Silakan pilih paket langganan untuk mulai menggunakan FarmGo.');
            }

            // Peternak (premium/trial) redirect ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Password salah - track failed login attempts
        $attempts = session('login_attempts', 0) + 1;
        session(['login_attempts' => $attempts]);

        // Lock account after 3 failed attempts
        if ($attempts >= 3) {
            session(['login_locked_until' => now()->addMinutes(5)]);
            return back()->withErrors([
                'password' => 'Terlalu banyak percobaan login gagal. Akun Anda terkunci selama 5 menit.'
            ])->withInput($request->only('email'));
        }

        $remainingAttempts = 3 - $attempts;
        return back()->withErrors([
            'password' => "Password salah. Sisa percobaan: {$remainingAttempts}"
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
            // Silently fail if login history recording fails
            // Don't block the login process
            \Log::error('Failed to record login history: ' . $e->getMessage());
        }
    }
}

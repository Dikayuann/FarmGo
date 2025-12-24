<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function view()
    {
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Cek apakah user dengan email tersebut ada
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Email tidak terdaftar - langsung beri tahu tanpa rate limiting
            return back()->withErrors([
                'email' => 'Email tidak terdaftar. Silakan daftar terlebih dahulu.'
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

            if ($user->isAdmin()) {
                // Admin redirect ke admin panel
                return redirect('/admin');
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
}

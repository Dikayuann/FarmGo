<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user ke halaman login Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah user authorize
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User sudah ada dengan Google ID, langsung login
                Auth::login($user);
                request()->session()->regenerate();

                // Redirect based on role
                if ($user->isAdmin()) {
                    return redirect('/admin');
                }
                return redirect()->route('dashboard');
            }

            // Cek apakah email sudah terdaftar (untuk linking account)
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Email sudah terdaftar, link dengan Google account
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($existingUser);
                request()->session()->regenerate();

                // Redirect based on role
                if ($existingUser->isAdmin()) {
                    return redirect('/admin');
                }
                return redirect()->route('dashboard');
            }

            // User belum ada, buat user baru
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // Password null untuk OAuth users
                'role' => User::ROLE_TRIAL, // Default role untuk user baru
            ]);

            Auth::login($newUser);
            request()->session()->regenerate();

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // Handle error, redirect ke login dengan error message
            return redirect()->route('login')->withErrors([
                'google' => 'Gagal login dengan Google. Silakan coba lagi.'
            ]);
        }
    }
}

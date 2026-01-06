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
            $googleEmail = $googleUser->getEmail();

            // Cek apakah user sudah ada berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User sudah ada dengan Google ID, langsung login
                Auth::login($user);
                request()->session()->regenerate();

                // Redirect based on role with email parameter for localStorage
                if ($user->isAdmin()) {
                    return redirect('/admin?google_email=' . urlencode($googleEmail));
                }
                return redirect()->route('dashboard', ['google_email' => $googleEmail]);
            }

            // Cek apakah email sudah terdaftar (untuk linking account)
            $existingUser = User::where('email', $googleEmail)->first();

            if ($existingUser) {
                // Email sudah terdaftar, link dengan Google account
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $googleUser->getAvatar(), // Save Google profile photo
                ]);

                Auth::login($existingUser);
                request()->session()->regenerate();

                // Redirect based on role with email parameter
                if ($existingUser->isAdmin()) {
                    return redirect('/admin?google_email=' . urlencode($googleEmail));
                }
                return redirect()->route('dashboard', ['google_email' => $googleEmail]);
            }

            // User belum ada, buat user baru
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleEmail,
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(), // Save Google profile photo
                'password' => null, // Password null untuk OAuth users
                'role' => User::ROLE_TRIAL, // Default role untuk user baru
            ]);

            Auth::login($newUser);
            request()->session()->regenerate();

            // Redirect new users to pricing page to choose subscription
            return redirect()->route('langganan', ['google_email' => $googleEmail, 'first_time' => '1']);

        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Handle error, redirect ke login dengan error message
            return redirect()->route('login')->withErrors([
                'google' => 'Gagal login dengan Google. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Handle Google One Tap credential
     */
    public function handleOneTap(Request $request)
    {
        try {
            $credential = $request->input('credential');

            if (!$credential) {
                return response()->json([
                    'success' => false,
                    'message' => 'No credential provided'
                ], 400);
            }

            // Verify the Google ID token
            $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($credential);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credential'
                ], 401);
            }

            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];
            $avatar = $payload['picture'] ?? null;

            // Check if user exists
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleId,
                        'avatar_url' => $avatar, // Save Google profile photo
                    ]);
                }

                Auth::login($user);
                request()->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'email' => $email,
                    'redirect' => $user->isAdmin() ? '/admin' : route('dashboard')
                ]);
            }

            // Create new user
            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar_url' => $avatar, // Save Google profile photo
                'password' => null,
                'role' => User::ROLE_TRIAL,
            ]);

            Auth::login($newUser);
            request()->session()->regenerate();

            // Redirect new users to pricing page
            return response()->json([
                'success' => true,
                'email' => $email,
                'redirect' => route('langganan', ['first_time' => '1'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Google One Tap Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'credential_present' => $request->has('credential'),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentik gagal. Silakan gunakan tombol Google standar atau coba lagi.'
            ], 500);
        }
    }
}

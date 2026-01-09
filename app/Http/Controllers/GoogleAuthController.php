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
     * Redirect user ke halaman login Google (untuk LOGIN)
     */
    public function redirectToGoogleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect user ke halaman login Google (untuk REGISTER)
     */
    public function redirectToGoogleRegister()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah user authorize
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            $googleEmail = $googleUser->getEmail();
            $googleId = $googleUser->getId();
            $googleName = $googleUser->getName();
            $googleAvatar = $googleUser->getAvatar();

            // Cek apakah user sudah ada berdasarkan google_id ATAU email
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $googleEmail)
                ->first();

            if ($user) {
                // User exists - update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleId,
                        'avatar_url' => $googleAvatar,
                    ]);
                }

                // Login user
                Auth::login($user);
                $request->session()->regenerate();

                // Check role and subscription
                if ($user->isAdmin()) {
                    return redirect('/admin?google_email=' . urlencode($googleEmail));
                }

                // Check if user has active subscription
                if (!$user->hasActivePremium() && !$user->isOnTrial()) {
                    return redirect()->route('langganan', ['google_email' => $googleEmail])
                        ->with('info', 'Selamat datang kembali! Silakan pilih paket langganan untuk melanjutkan.');
                }

                return redirect()->route('dashboard', ['google_email' => $googleEmail])
                    ->with('success', 'Berhasil login dengan Google!');
            }

            // User doesn't exist - create new user
            $newUser = User::create([
                'name' => $googleName,
                'email' => $googleEmail,
                'google_id' => $googleId,
                'avatar_url' => $googleAvatar,
                'password' => null, // Password null untuk OAuth users
                'role' => User::ROLE_TRIAL, // Temporary role, akan diupdate setelah pilih paket
            ]);

            Auth::login($newUser);
            $request->session()->regenerate();

            // Redirect new users to pricing page
            return redirect()->route('langganan', [
                'google_email' => $googleEmail,
                'first_time' => '1'
            ])->with('success', 'Akun berhasil dibuat! Silakan pilih paket langganan.');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::warning('Google OAuth Invalid State - User may have refreshed or taken too long', [
                'message' => $e->getMessage(),
            ]);

            // Redirect back to login with friendly message
            return redirect()->route('login')->with('info', 'Silakan coba login dengan Google sekali lagi.');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'type' => get_class($e)
            ]);

            return redirect()->route('login')->withErrors([
                'google' => 'Gagal login dengan Google. Silakan coba lagi atau gunakan email & password.'
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
                        'avatar_url' => $avatar,
                    ]);
                }

                Auth::login($user);
                request()->session()->regenerate();

                // Check if user has active subscription
                $redirectUrl = $user->isAdmin() ? '/admin' : route('dashboard');

                if (!$user->isAdmin() && !$user->hasActivePremium() && !$user->isOnTrial()) {
                    $redirectUrl = route('langganan');
                }

                return response()->json([
                    'success' => true,
                    'email' => $email,
                    'redirect' => $redirectUrl,
                    'needs_subscription' => !$user->isAdmin() && !$user->hasActivePremium() && !$user->isOnTrial()
                ]);
            }

            // Create new user
            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar_url' => $avatar,
                'password' => null,
                'role' => User::ROLE_TRIAL, // Temporary role, akan diupdate setelah pilih paket
            ]);

            Auth::login($newUser);
            request()->session()->regenerate();

            // Redirect new users to pricing page
            return response()->json([
                'success' => true,
                'email' => $email,
                'redirect' => route('langganan', ['first_time' => '1']),
                'is_new_user' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Google One Tap Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'credential_present' => $request->has('credential'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Autentikasi gagal. Silakan gunakan tombol Google standar atau coba lagi.'
            ], 500);
        }
    }
}

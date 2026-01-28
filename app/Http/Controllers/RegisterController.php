<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Rules\NotDisposableEmail;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class RegisterController extends Controller
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

        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validationRules = [
            'full_name' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'unique:users,email', new NotDisposableEmail()],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)8[0-9]{8,11}$/'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ];

        $validationMessages = [
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx (10-13 digit) atau +628xxxxxxxxxx',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ];

        // Only validate Turnstile in production
        if (config('app.env') === 'production') {
            $validationRules['cf-turnstile-response'] = ['required', new Turnstile];
            $validationMessages['cf-turnstile-response.required'] = 'Silakan selesaikan verifikasi Cloudflare Turnstile terlebih dahulu.';
        }

        $request->validate($validationRules, $validationMessages);

        // Buat user baru
        $user = User::create([
            'name' => $request->full_name,
            'farm_name' => $request->farm_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_TRIAL, // Temporary role, akan diupdate setelah pilih paket
        ]);

        // Auto-login after registration
        Auth::login($user);

        // Redirect to subscription page
        return redirect()->route('langganan.index')
            ->with('success', 'Akun berhasil dibuat! Silakan pilih paket langganan untuk melanjutkan.');
    }
}

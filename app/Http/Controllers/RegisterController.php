<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                return redirect()->route('langganan')
                    ->with('info', 'Silakan pilih paket langganan untuk melanjutkan.');
            }

            return redirect()->route('dashboard');
        }

        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'full_name' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->full_name,
            'farm_name' => $request->farm_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_TRIAL, // Temporary role, akan diupdate setelah pilih paket
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke pricing page untuk memilih langganan
        return redirect()->route('langganan', ['first_time' => '1']);
    }
}

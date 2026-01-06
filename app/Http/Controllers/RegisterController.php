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
        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input dengan password validation yang kuat
        $request->validate([
            'full_name' => 'required|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // minimal 1 huruf kecil
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[0-9]/',      // minimal 1 angka
            ],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->full_name,
            'farm_name' => $request->farm_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_TRIAL, // Default role untuk user baru
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke pricing page untuk memilih langganan
        return redirect()->route('langganan', ['first_time' => '1']);
    }
}

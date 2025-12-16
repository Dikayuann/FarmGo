<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function view()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        //validasi login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user terdaftar via Google (password = null)
        $user = User::where('email', $request->email)->first();

        if ($user && $user->google_id && !$user->password) {
            return back()->withErrors([
                'email' => 'Akun ini terdaftar dengan Google. Silakan login menggunakan tombol Google.'
            ])->withInput($request->only('email'));
        }

        // Attempt login dengan email dan password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Check role dan redirect sesuai role
            $user = Auth::user();

            if ($user->isAdmin()) {
                // Admin redirect ke admin panel
                return redirect('/admin');
            }

            // Peternak (premium/trial) redirect ke dashboard
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->withInput($request->only('email'));
    }
}

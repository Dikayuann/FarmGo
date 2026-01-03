<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'farm_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Check if user has a password (not OAuth-only user)
        if (!$user->password) {
            return back()->withErrors(['current_password' => 'Anda belum memiliki password. Gunakan fitur "Set Password" untuk membuat password.']);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Set password for OAuth users
     */
    public function setPassword(Request $request)
    {
        $user = Auth::user();

        // Check if user already has a password
        if ($user->password) {
            return back()->withErrors(['password' => 'Anda sudah memiliki password. Gunakan fitur "Ganti Password" untuk mengubah password.']);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // Set password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil dibuat! Sekarang Anda bisa login menggunakan email dan password.');
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB
        ], [
            'avatar.required' => 'Foto profil wajib dipilih',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update user avatar
        $user->update([
            'avatar' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete user avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            // Delete avatar file
            Storage::disk('public')->delete($user->avatar);

            // Update user avatar to null
            $user->update([
                'avatar' => null,
            ]);

            return back()->with('success', 'Foto profil berhasil dihapus!');
        }

        return back()->with('info', 'Tidak ada foto profil untuk dihapus.');
    }
}


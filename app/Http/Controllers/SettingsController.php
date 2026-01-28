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
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)8[0-9]{8,11}$/'],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx (10-13 digit) atau +628xxxxxxxxxx',
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
     * Update user avatar (BASE64 METHOD - NO FILE UPLOAD ISSUES)
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        try {
            // Log incoming request for debugging
            \Log::info('Avatar upload request received', [
                'user_id' => $user->id,
                'has_avatar_base64' => $request->has('avatar_base64'),
                'has_filename' => $request->has('filename'),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method(),
            ]);

            // Validate base64 input
            $validated = $request->validate([
                'avatar_base64' => 'required|string',
                'filename' => 'required|string',
            ]);

            $base64Data = $validated['avatar_base64'];
            $filename = $validated['filename'];

            // Extract base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                // Validate image type
                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format gambar tidak valid. Hanya JPG, PNG, atau GIF.'
                    ], 400);
                }

                $base64Data = base64_decode($base64Data);

                if ($base64Data === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal decode gambar.'
                    ], 400);
                }

                // Validate file size (2MB)
                if (strlen($base64Data) > 2 * 1024 * 1024) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ukuran gambar terlalu besar. Maksimal 2MB.'
                    ], 400);
                }

                // Delete old avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                    \Log::info('Old avatar deleted', ['old_file' => $user->avatar]);
                }

                // Generate unique filename
                $newFilename = 'avatars/' . uniqid() . '_' . time() . '.' . $type;

                // Save to storage
                Storage::disk('public')->put($newFilename, $base64Data);

                // Update user
                $user->update([
                    'avatar' => $newFilename,
                    'avatar_url' => null, // Clear Google avatar
                ]);

                \Log::info('Avatar uploaded successfully (base64)', [
                    'user_id' => $user->id,
                    'filename' => $newFilename,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui!',
                    'avatar_url' => asset('storage/' . $newFilename)
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Format data gambar tidak valid.'
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Avatar upload failed (base64)', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah foto profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user avatar
     */
    public function deleteAvatar()
    {
        try {
            $user = Auth::user();

            \Log::info('Delete avatar request', [
                'user_id' => $user->id,
                'has_avatar' => !empty($user->avatar),
                'has_avatar_url' => !empty($user->avatar_url),
                'avatar_value' => $user->avatar,
            ]);

            $deleted = false;

            // Delete local avatar if exists
            if ($user->avatar) {
                \Log::info('Deleting local avatar file', ['path' => $user->avatar]);
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
                $deleted = true;
            }

            // Also clear Google avatar URL if exists
            if ($user->avatar_url) {
                $user->update(['avatar_url' => null]);
                $deleted = true;
            }

            if (!$deleted) {
                \Log::warning('No avatar to delete', ['user_id' => $user->id]);
            }

            // Check if request wants JSON (AJAX)
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil dihapus!'
                ]);
            }

            return back()->with('success', 'Foto profil berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Avatar delete failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus foto profil.'
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus foto profil. Silakan coba lagi.');
        }
    }
}

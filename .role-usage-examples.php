<?php

/**
 * Contoh Penggunaan Role Middleware
 * 
 * File ini memberikan contoh bagaimana menggunakan middleware 'role' untuk proteksi route
 * berdasarkan user role di aplikasi FarmGo.
 */

use App\Http\Controllers\DashboardController;
use App\Models\User;

// ============================================
// CONTOH 1: Route yang bisa diakses semua user yang login
// ============================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ============================================
// CONTOH 2: Route khusus untuk ADMIN saja
// ============================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', function () {
        // Hanya admin yang bisa akses
        return view('admin.users');
    });

    Route::get('/settings', function () {
        // Hanya admin yang bisa akses
        return view('admin.settings');
    });
});

// ============================================
// CONTOH 3: Route untuk PREMIUM dan ADMIN
// (Trial users tidak bisa akses)
// ============================================
Route::middleware(['auth', 'role:peternak_premium,admin'])->group(function () {
    Route::get('/advanced-analytics', function () {
        // Premium dan Admin bisa akses
        return view('premium.analytics');
    });

    Route::get('/export-data', function () {
        // Premium dan Admin bisa akses
        return view('premium.export');
    });

    Route::get('/ai-recommendations', function () {
        // Premium dan Admin bisa akses
        return view('premium.ai');
    });
});

// ============================================
// CONTOH 4: Route untuk TRIAL, PREMIUM, dan ADMIN
// (Semua peternak bisa akses, tapi perlu login)
// ============================================
Route::middleware(['auth', 'role:peternak_trial,peternak_premium,admin'])->group(function () {
    Route::get('/ternak', function () {
        return view('ternak');
    })->name('ternak');

    Route::get('/kesehatan', function () {
        return view('kesehatan');
    })->name('kesehatan');
});

// ============================================
// CONTOH 5: Conditional Logic berdasarkan Role di Controller
// ============================================
class ExampleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check role menggunakan helper methods
        if ($user->isAdmin()) {
            // Admin logic
            $data = $this->getAdminData();
        } elseif ($user->isPremium()) {
            // Premium logic
            $data = $this->getPremiumData();
        } else {
            // Trial logic (limited)
            $data = $this->getTrialData();
        }

        return view('dashboard', compact('data'));
    }

    public function premiumFeature()
    {
        $user = auth()->user();

        // Check if user can access premium features
        if (!$user->canAccessPremiumFeatures()) {
            return redirect()->route('dashboard')->withErrors([
                'upgrade' => 'Fitur ini hanya tersedia untuk pengguna Premium. Silakan upgrade akun Anda.'
            ]);
        }

        // Premium feature logic
        return view('features.premium');
    }
}

// ============================================
// CONTOH 6: Conditional UI di Blade Template
// ============================================
/*
{{-- Di file blade, misal dashboard.blade.php --}}

@if(auth()->user()->isAdmin())
    <a href="/admin/panel">Admin Panel</a>
@endif

@if(auth()->user()->canAccessPremiumFeatures())
    <a href="/premium-feature">Premium Features</a>
@else
    <div class="upgrade-banner">
        <p>Upgrade ke Premium untuk unlock fitur ini!</p>
        <a href="/upgrade">Upgrade Sekarang</a>
    </div>
@endif

@if(auth()->user()->isTrial())
    <div class="trial-notice">
        Anda menggunakan versi Trial. Beberapa fitur terbatas.
    </div>
@endif
*/

// ============================================
// CONTOH 7: Manual Role Upgrade (via Tinker or Controller)
// ============================================
/*
// Via PHP Artisan Tinker:
php artisan tinker

// Upgrade user dari trial ke premium
$user = User::find(1);
$user->role = User::ROLE_PREMIUM;
$user->save();

// Set user sebagai admin
$user = User::where('email', 'admin@farmgo.com')->first();
$user->role = User::ROLE_ADMIN;
$user->save();
*/

// ============================================
// CONTOH 8: Creating Admin User via Seeder
// ============================================
/*
// database/seeders/AdminSeeder.php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin FarmGo',
            'email' => 'admin@farmgo.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}

// Run: php artisan db:seed --class=AdminSeeder
*/

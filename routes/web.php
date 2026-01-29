<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\TernakController;
use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('landing.index');
});

// Contact Form Route (with rate limiting: 5 submissions per 10 minutes)
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('contact.store');

// Newsletter Subscription Route (with rate limiting: 3 subscriptions per 10 minutes)
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])
    ->middleware('throttle:3,10')
    ->name('newsletter.subscribe');

Route::middleware(['auth'])->group(function () {
    // Langganan Routes (no subscription required - users need access to subscribe)
    Route::get('/langganan', [App\Http\Controllers\LanggananController::class, 'index'])->name('langganan');
    Route::get('/langganan/checkout/{package}', [App\Http\Controllers\LanggananController::class, 'showCheckout'])->name('langganan.checkout');
    Route::post('/langganan/payment', [App\Http\Controllers\LanggananController::class, 'createPayment'])->name('langganan.payment');
    Route::post('/langganan/trial', [App\Http\Controllers\LanggananController::class, 'activateTrial'])->name('langganan.trial');
    Route::get('/langganan/pending/{orderId}', [App\Http\Controllers\LanggananController::class, 'showPendingPayment'])->name('langganan.pending');
    Route::post('/langganan/check-status/{orderId}', [App\Http\Controllers\LanggananController::class, 'checkPaymentStatus'])->name('langganan.check-status');
    Route::get('/langganan/history', [App\Http\Controllers\LanggananController::class, 'paymentHistory'])->name('langganan.history');
    Route::post('/langganan/cancel', [App\Http\Controllers\LanggananController::class, 'cancelSubscription'])->name('langganan.cancel');

    // Notification Routes (no subscription required - users need to see subscription notifications)
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/latest-langganan', [App\Http\Controllers\NotificationController::class, 'getLatestLanggananNotification'])->name('notifications.latest-langganan');

    // Settings Routes (no subscription required - users need access to profile)
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::post('/settings/set-password', [App\Http\Controllers\SettingsController::class, 'setPassword'])->name('settings.set-password');
    Route::post('/settings/avatar', [App\Http\Controllers\SettingsController::class, 'updateAvatar'])->name('settings.update-avatar');
    Route::delete('/settings/avatar', [App\Http\Controllers\SettingsController::class, 'deleteAvatar'])->name('settings.delete-avatar');

});

// Protected routes - require active subscription
Route::middleware(['auth', 'require.subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Calendar Event Routes
    Route::get('/api/calendar-data', [DashboardController::class, 'getCalendarData'])->name('calendar.data');
    Route::post('/api/calendar-events/{id}/complete', [DashboardController::class, 'markEventComplete'])->name('calendar.mark-complete');

    // Ternak Resource Routes
    Route::resource('ternak', TernakController::class);

    // Kesehatan (Health Records)
    Route::get('/animals/{id}/weight-history', [KesehatanController::class, 'getWeightHistory'])->name('animals.weight-history');
    Route::resource('kesehatan', KesehatanController::class);

    // Vaksinasi Resource Routes
    Route::resource('vaksinasi', App\Http\Controllers\VaksinasiController::class);

    // Reproduksi Resource Routes
    Route::resource('reproduksi', App\Http\Controllers\ReproduksiController::class);
    Route::get('/reproduksi/{perkawinan}/add-offspring', [App\Http\Controllers\ReproduksiController::class, 'addOffspring'])
        ->name('reproduksi.add-offspring');
    Route::post('/reproduksi/{perkawinan}/offspring', [App\Http\Controllers\ReproduksiController::class, 'storeOffspring'])
        ->name('reproduksi.store-offspring');

    // Heat Detection Routes
    Route::resource('heat-detection', App\Http\Controllers\HeatDetectionController::class);

    // AI Assistant Routes
    Route::post('/ai-assistant/chat', [App\Http\Controllers\AiAssistantController::class, 'chat'])->name('ai-assistant.chat');

    // Export Routes
    Route::get('/ekspor', [App\Http\Controllers\ExportController::class, 'index'])->name('ekspor.index');
    Route::post('/ekspor/animals', [App\Http\Controllers\ExportController::class, 'exportAnimals'])->name('ekspor.animals');
    Route::post('/ekspor/health-records', [App\Http\Controllers\ExportController::class, 'exportHealthRecords'])->name('ekspor.health-records');
    Route::post('/ekspor/reproduction', [App\Http\Controllers\ExportController::class, 'exportReproduction'])->name('ekspor.reproduction');
    Route::post('/ekspor/comprehensive', [App\Http\Controllers\ExportController::class, 'exportComprehensive'])->name('ekspor.comprehensive');
});

// Settings Routes (accessible without subscription requirement)
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::post('/settings/set-password', [App\Http\Controllers\SettingsController::class, 'setPassword'])->name('settings.set-password');
    Route::post('/settings/avatar', [App\Http\Controllers\SettingsController::class, 'updateAvatar'])->name('settings.update-avatar');
});

// Subscription/Langganan Routes (accessible without require.subscription middleware)
Route::get('/langganan', [App\Http\Controllers\LanggananController::class, 'index'])->middleware('auth')->name('langganan.index');
Route::get('/langganan/checkout/{package}', [App\Http\Controllers\LanggananController::class, 'showCheckout'])->middleware('auth')->name('langganan.checkout');
Route::post('/langganan/payment', [App\Http\Controllers\LanggananController::class, 'createPayment'])->middleware('auth')->name('langganan.payment');
Route::get('/langganan/pending/{orderId}', [App\Http\Controllers\LanggananController::class, 'showPendingPayment'])->middleware('auth')->name('langganan.pending');
Route::get('/langganan/payment-status/{orderId}', [App\Http\Controllers\LanggananController::class, 'checkPaymentStatus'])->middleware('auth')->name('langganan.payment-status');
Route::post('/langganan/activate-trial', [App\Http\Controllers\LanggananController::class, 'activateTrial'])->middleware('auth')->name('langganan.activate-trial');
Route::get('/langganan/history', [App\Http\Controllers\LanggananController::class, 'paymentHistory'])->middleware('auth')->name('langganan.history');
Route::post('/langganan/cancel', [App\Http\Controllers\LanggananController::class, 'cancelSubscription'])->middleware('auth')->name('langganan.cancel');

Route::get('/login', [LoginController::class, 'view']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route untuk clear lockout (jika user terkunci)
Route::get('/login/clear-lockout', [LoginController::class, 'clearLockout'])->name('login.clear');

Route::get('/register', [RegisterController::class, 'view'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:5,10')
    ->name('register.submit');

// Forgot Password Routes
Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('/reset-password/{token}', [App\Http\Controllers\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', [App\Http\Controllers\EmailVerificationController::class, 'notice'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/resend', [App\Http\Controllers\EmailVerificationController::class, 'resend'])
    ->middleware('throttle:3,5')
    ->name('verification.resend');

// API route for email validation with rate limiting
Route::get('/api/check-email', function (Illuminate\Http\Request $request) {
    // Rate limiting: max 20 requests per minute per IP
    $key = 'check-email:' . $request->ip();

    if (Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 20)) {
        return response()->json([
            'error' => 'Too many requests. Please try again later.'
        ], 429);
    }

    Illuminate\Support\Facades\RateLimiter::hit($key, 60); // 60 seconds decay

    $email = $request->query('email');

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['exists' => false]);
    }

    $exists = \App\Models\User::where('email', $email)->exists();
    return response()->json(['exists' => $exists]);
});

// Google OAuth routes - Separate for Login and Register
Route::get('/auth/google/login', [GoogleAuthController::class, 'redirectToGoogleLogin'])->name('auth.google.login');
Route::get('/auth/google/register', [GoogleAuthController::class, 'redirectToGoogleRegister'])->name('auth.google.register');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/auth/google/one-tap', [GoogleAuthController::class, 'handleOneTap'])->name('auth.google.oneTap');

// Legacy route for backward compatibility
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogleLogin'])->name('auth.google');

// Secure logout route with POST method (primary)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')
        ->with('success', 'Anda telah berhasil logout.')
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
})->name('logout');

// GET logout route (fallback for CSRF token expiry)
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')
        ->with('info', 'Sesi Anda telah berakhir. Silakan login kembali.')
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
})->name('logout.get');

// Midtrans callback (no auth required, called by Midtrans server)
Route::post('/langganan/callback', [App\Http\Controllers\LanggananController::class, 'handleCallback'])->name('langganan.callback');

// Redirect /admin/login to main login page (security measure)
Route::get('/admin/login', function () {
    return redirect('/login');
});

// Admin-only routes - Protected with admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin backup download route
    Route::get('/admin/download-backup/{file}', function ($file) {
        $path = storage_path('app/backups/' . $file);

        if (!file_exists($path)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($path);
    })->name('filament.admin.download-backup');
});


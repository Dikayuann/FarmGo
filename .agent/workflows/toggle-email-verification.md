---
description: Cara mengaktifkan/menonaktifkan verifikasi email
---

# Toggle Email Verification

Panduan ini menjelaskan cara mengaktifkan atau menonaktifkan fitur verifikasi email di aplikasi FarmGo.

## Menonaktifkan Verifikasi Email (Status Saat Ini: ✅ NONAKTIF)

Verifikasi email saat ini sudah **DINONAKTIFKAN**. User bisa langsung login setelah registrasi tanpa perlu verifikasi email.

## Mengaktifkan Kembali Verifikasi Email

Jika nanti ingin mengaktifkan kembali verifikasi email, ikuti langkah berikut:

### 1. Edit File User Model

Buka file: `app/Models/User.php`

**Tambahkan** import statement di bagian atas (sekitar line 5):

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;
```

**Ubah** class declaration (sekitar line 13) dari:

```php
class User extends Authenticatable implements FilamentUser
```

Menjadi:

```php
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
```

### 2. Verifikasi Routes

Routes untuk email verification sudah tersedia di `routes/web.php`:

- `/email/verify` - Halaman notice verifikasi email
- `/email/verify/{id}/{hash}` - Link verifikasi dari email
- `/email/resend` - Resend verification email

### 3. Tambahkan Middleware (Opsional)

Jika ingin memaksa user untuk verifikasi email sebelum mengakses dashboard, tambahkan middleware `verified` di routes:

```php
Route::middleware(['auth', 'verified', 'require.subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ... routes lainnya
});
```

### 4. Konfigurasi Email

Pastikan konfigurasi email di `.env` sudah benar:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=your-email@zohomail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@zohomail.com
MAIL_FROM_NAME="FarmGo"
```

### 5. Test Email Configuration

Akses route `/test-email` untuk memastikan email bisa terkirim dengan baik.

## Catatan Penting

- **User yang sudah terdaftar**: Jika mengaktifkan kembali verifikasi email, user lama yang belum terverifikasi akan diminta untuk verifikasi
- **Development vs Production**: Untuk development/testing, lebih praktis menonaktifkan verifikasi email
- **Email Templates**: Template email verifikasi tersedia di `app/Notifications/VerifyEmailNotification.php`

## Troubleshooting

Jika ada masalah setelah mengaktifkan verifikasi email:

1. Clear cache aplikasi:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

2. Pastikan semua user di database memiliki `email_verified_at` yang terisi (untuk user lama):

```sql
UPDATE users SET email_verified_at = NOW() WHERE email_verified_at IS NULL;
```

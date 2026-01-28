<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_PETERNAK = 'peternak'; // Default role for new users (no subscription yet)
    const ROLE_PREMIUM = 'peternak_premium';
    const ROLE_TRIAL = 'peternak_trial';

    /**
     * Boot method to clear cache when users are modified
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache on create, update, or delete
        static::created(function () {
            Cache::forget('users_total_count');
            Cache::forget('users_admin_count');
            Cache::forget('users_premium_count');
            Cache::forget('users_trial_count');
        });

        static::updated(function () {
            Cache::forget('users_total_count');
            Cache::forget('users_admin_count');
            Cache::forget('users_premium_count');
            Cache::forget('users_trial_count');
        });

        static::deleted(function () {
            Cache::forget('users_total_count');
            Cache::forget('users_admin_count');
            Cache::forget('users_premium_count');
            Cache::forget('users_trial_count');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'farm_name',
        'phone',
        'avatar',
        'avatar_url',
        'role',
        'trial_started_at',
        'trial_ends_at',
        'status_langganan',
        'batas_ternak',
        'batas_vaksin',
        'batas_reproduksi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_started_at' => 'datetime',
            'trial_ends_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is premium peternak
     */
    public function isPremium(): bool
    {
        return $this->role === self::ROLE_PREMIUM;
    }

    /**
     * Check if user is trial peternak
     */
    public function isTrial(): bool
    {
        return $this->role === self::ROLE_TRIAL;
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the user's avatar URL
     * Priority: 1. avatar (local upload), 2. default (initials)
     * Google avatar is IGNORED to avoid errors
     */
    public function getAvatarUrlAttribute(): string
    {
        // Priority 1: Check local avatar upload
        if (!empty($this->attributes['avatar'])) {
            $avatarPath = $this->attributes['avatar'];

            // Check if 'avatars/' prefix is already in the path
            if (!str_starts_with($avatarPath, 'avatars/')) {
                $avatarPath = 'avatars/' . $avatarPath;
            }

            return asset('storage/' . $avatarPath);
        }

        // Priority 2: Default - return empty (use initials in view)
        return '';
    }

    /**
     * Check if user can access premium features
     */
    public function canAccessPremiumFeatures(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PREMIUM]);
    }

    /**
     * Get all notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    /**
     * Check if user can add more reproduction records
     */
    public function canAddReproduction(): bool
    {
        // Premium users have unlimited reproduction records
        if ($this->hasActivePremium()) {
            return true;
        }

        // Trial users are limited by batas_reproduksi
        if ($this->isOnTrial()) {
            // Get all betina IDs owned by this user
            $betinaIds = \App\Models\Animal::where('user_id', $this->id)
                ->where('jenis_kelamin', 'betina')
                ->pluck('id');

            // Count perkawinans for those betinas
            $currentCount = \App\Models\Perkawinan::whereIn('betina_id', $betinaIds)->count();

            return $currentCount < ($this->batas_reproduksi ?? 5);
        }

        // Users without subscription cannot add reproduction records
        return false;
    }

    /**
     * Determine if user can access Filament admin panel
     * Only admin role can access
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user is on trial
     */
    public function isOnTrial(): bool
    {
        if (!$this->trial_ends_at) {
            return false;
        }

        return $this->trial_ends_at->isFuture() && $this->role === self::ROLE_TRIAL;
    }

    /**
     * Get trial days remaining
     */
    public function trialDaysRemaining(): int
    {
        if (!$this->isOnTrial()) {
            return 0;
        }

        return now()->diffInDays($this->trial_ends_at, false);
    }

    /**
     * Check if trial has expired
     */
    public function trialExpired(): bool
    {
        if (!$this->trial_ends_at) {
            return false;
        }

        return $this->trial_ends_at->isPast();
    }

    /**
     * Start trial period
     */
    public function startTrial(int $days = 7): void
    {
        $this->update([
            'role' => self::ROLE_TRIAL,
            'status_langganan' => 'trial',
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays($days),
            'batas_ternak' => 10,
            'batas_vaksin' => 10,
            'batas_reproduksi' => 5, // Trial users can track up to 5 reproduction records
        ]);
    }

    /**
     * End trial and downgrade to basic
     */
    public function endTrial(): void
    {
        $this->update([
            'role' => self::ROLE_TRIAL,
            'status_langganan' => 'trial',
            'batas_ternak' => 0,
            'batas_vaksin' => 0,
            'batas_reproduksi' => 0,
        ]);
    }

    /**
     * Get animals relationship
     */
    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    /**
     * Get langganans relationship
     */
    public function langganans()
    {
        return $this->hasMany(Langganan::class, 'user_id');
    }

    /**
     * Get login histories relationship
     */
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class)->orderBy('login_at', 'desc');
    }

    /**
     * Check if user has active premium subscription
     * Overrides the role-based check to use langganan table
     */
    public function hasActivePremium(): bool
    {
        if ($this->isAdmin())
            return true;

        $activeLangganan = $this->langganans()
            ->where('status', 'aktif')  // Fix: database value is 'aktif' not 'active'
            ->where('tanggal_berakhir', '>=', now())
            ->whereIn('paket_langganan', ['premium_monthly', 'premium_yearly'])  // Fix: check both premium types
            ->first();

        return $activeLangganan !== null;
    }

    /**
     * Check if user can add more animals
     */
    public function canAddAnimal(): bool
    {
        if ($this->isAdmin())
            return true;

        $currentCount = $this->animals()->count();

        // Premium users have unlimited animals
        if ($this->hasActivePremium()) {
            return true;
        }

        // Trial users: max 10 animals
        return $currentCount < 6;
    }

    /**
     * Get remaining animal quota
     */
    public function getRemainingAnimalQuota(): int
    {
        if ($this->hasActivePremium()) {
            return PHP_INT_MAX; // Unlimited
        }

        $currentCount = $this->animals()->count();
        return max(0, 10 - $currentCount);
    }

    /**
     * Check if user can export data
     */
    public function canExportData(): bool
    {
        if ($this->isAdmin())
            return true;
        return $this->hasActivePremium();
    }

    /**
     * Get subscription status details
     */
    public function getSubscriptionStatus(): array
    {
        $activeLangganan = $this->langganans()
            ->where('status', 'active')
            ->where('tanggal_berakhir', '>=', now())
            ->first();

        if (!$activeLangganan) {
            return [
                'type' => 'trial',
                'status' => 'expired',
                'days_remaining' => 0,
            ];
        }

        return [
            'type' => $activeLangganan->paket_langganan,
            'status' => 'active',
            'days_remaining' => now()->diffInDays($activeLangganan->tanggal_berakhir),
            'expires_at' => $activeLangganan->tanggal_berakhir,
        ];
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return Notifikasi::forUser($this->id)->unread()->count();
    }

    /**
     * Get recent notifications
     */
    public function recentNotifications(int $limit = 5)
    {
        return Notifikasi::forUser($this->id)
            ->with(['animal', 'perkawinan'])
            ->orderBy('tanggal_kirim', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token, $this->email));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }

    /**
     * Check if verification email was sent recently (within 24 hours)
     * Used to determine if we should resend or not
     */
    public function hasRecentVerificationEmail()
    {
        if ($this->email_verified_at) {
            return false; // Already verified, no need to check
        }

        // FarmGo uses custom 'notifikasis' table, not Laravel's default notifications
        // Check if user registered recently (within 24 hours) as proxy for email sent time
        // Since verification email is sent immediately on registration

        $registeredAt = $this->created_at;
        $hoursSinceRegistration = $registeredAt->diffInHours(now());

        // If registered within 24 hours, consider email still valid
        return $hoursSinceRegistration < 24;
    }

    /**
     * ============================================
     * SUBSCRIPTION STATUS HELPERS
     * ============================================
     */

    /**
     * Get subscription expiry date from trial or premium subscription
     */
    protected function getSubscriptionExpiryDate()
    {
        // Check if has active premium subscription
        $activeLangganan = $this->langganans()
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now())
            ->first();

        if ($activeLangganan) {
            return \Carbon\Carbon::parse($activeLangganan->tanggal_berakhir);
        }

        // Fallback to trial_ends_at
        if ($this->trial_ends_at) {
            return \Carbon\Carbon::parse($this->trial_ends_at);
        }

        return null;
    }

    /**
     * Check if user is in grace period (0-3 days after trial/subscription ends)
     * During grace period, user still has full access but gets warning banners
     */
    public function isInGracePeriod(): bool
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return false;
        }

        $now = now();

        // Grace period: 1-3 days after expiry
        return $now->greaterThan($expiry) && $now->diffInDays($expiry, false) >= -3 && $now->diffInDays($expiry, false) < 0;
    }

    /**
     * Check if user is in read-only mode (4-30 days after expiry)
     * In read-only mode, user can view all data but cannot create/edit/delete
     */
    public function isReadOnlyMode(): bool
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return false;
        }

        $now = now();
        $daysSinceExpiry = abs($now->diffInDays($expiry, false));

        // Read-only: 4-30 days after expiry (expiry is in the past)
        return $now->greaterThan($expiry) && $daysSinceExpiry > 3 && $daysSinceExpiry <= 30;
    }

    /**
     * Check if user should be hard locked (30+ days after expiry)
     * Hard locked users are redirected to subscription page and cannot access dashboard
     */
    public function isHardLocked(): bool
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return false;
        }

        $now = now();

        // Hard lock: 30+ days after expiry
        return $now->greaterThan($expiry) && abs($now->diffInDays($expiry, false)) > 30;
    }

    /**
     * Get days until subscription expires (negative if expired)
     * Positive = days remaining, Negative = days since expiry
     */
    public function getDaysUntilExpiry(): int
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return 0;
        }

        return now()->diffInDays($expiry, false);
    }

    /**
     * Get days since expiry (0 if not expired)
     */
    public function getDaysSinceExpiry(): int
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return 0;
        }

        $now = now();

        if ($now->lessThanOrEqualTo($expiry)) {
            return 0; // Not expired yet
        }

        return abs($now->diffInDays($expiry, false));
    }

    /**
     * Check if trial is ending soon (within 7 days)
     */
    public function isTrialEndingSoon(): bool
    {
        $expiry = $this->getSubscriptionExpiryDate();

        if (!$expiry) {
            return false;
        }

        $daysUntil = $this->getDaysUntilExpiry();
        return $daysUntil > 0 && $daysUntil <= 7;
    }
}

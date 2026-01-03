<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_PREMIUM = 'peternak_premium';
    const ROLE_TRIAL = 'peternak_trial';

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
            'batas_reproduksi' => 10,
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
     * Check if user has active premium subscription
     * Overrides the role-based check to use langganan table
     */
    public function hasActivePremium(): bool
    {
        if ($this->isAdmin())
            return true;

        $activeLangganan = $this->langganans()
            ->where('status', 'active')
            ->where('tanggal_berakhir', '>=', now())
            ->where('paket_langganan', 'premium')
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
        return $currentCount < 10;
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
     * Get avatar URL accessor
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // If it's a Google avatar URL, return as is
        if (str_contains($this->avatar, 'googleusercontent.com') || str_contains($this->avatar, 'http')) {
            return $this->avatar;
        }

        // Otherwise, it's a local file
        // Check if 'avatars/' prefix is already in the path
        $avatarPath = str_starts_with($this->avatar, 'avatars/')
            ? $this->avatar
            : 'avatars/' . $this->avatar;

        return asset('storage/' . $avatarPath);
    }
}

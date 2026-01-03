<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Langganan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paket_langganan',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'harga',
        'metode_pembayaran',
        'transaction_reference',
        'auto_renew',
        'cancelled_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif' &&
            $this->tanggal_berakhir >= now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->tanggal_berakhir < now();
    }

    /**
     * Get days remaining
     */
    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->tanggal_berakhir);
    }

    /**
     * Activate subscription
     */
    public function activate(): void
    {
        // Only set tanggal_mulai if not already set
        $updates = [
            'status' => 'aktif',
        ];

        if (!$this->tanggal_mulai || $this->tanggal_mulai->isFuture()) {
            $updates['tanggal_mulai'] = now();
        }

        $this->update($updates);

        // Update user role and limits
        $this->updateUserSubscriptionStatus();
    }

    /**
     * Cancel subscription
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'dibatalkan',
            'auto_renew' => false,
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'kadaluarsa',
        ]);

        // Downgrade user to trial
        $this->user->update([
            'role' => User::ROLE_TRIAL,
            'status_langganan' => 'trial',
            'batas_ternak' => 10,
            'batas_vaksin' => 10,
            'batas_reproduksi' => 10,
        ]);
    }

    /**
     * Update user subscription status based on package
     */
    protected function updateUserSubscriptionStatus(): void
    {
        $limits = $this->getPackageLimits();

        $this->user->update([
            'role' => User::ROLE_PREMIUM,
            'status_langganan' => 'premium',
            'batas_ternak' => $limits['ternak'],
            'batas_vaksin' => $limits['vaksin'],
            'batas_reproduksi' => $limits['reproduksi'],
        ]);
    }

    /**
     * Get package limits
     */
    protected function getPackageLimits(): array
    {
        return match ($this->paket_langganan) {
            'premium_monthly', 'premium_yearly' => [
                'ternak' => 999999,      // Unlimited
                'vaksin' => 999999,      // Unlimited
                'reproduksi' => 999999,  // Unlimited
            ],
            default => [
                'ternak' => 10,
                'vaksin' => 10,
                'reproduksi' => 10,
            ],
        };
    }

    /**
     * Scope untuk langganan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now());
    }

    /**
     * Scope untuk langganan yang akan kadaluarsa
     */
    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', 'aktif')
            ->whereBetween('tanggal_berakhir', [
                now(),
                now()->addDays($days)
            ]);
    }
}

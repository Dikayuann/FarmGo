<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id',
        'animal_id',
        'perkawinan_id',
        'jenis_notifikasi',
        'pesan',
        'tanggal_kirim',
        'status',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the animal that owns the notification
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Get the mating record that owns the notification
     */
    public function perkawinan(): BelongsTo
    {
        return $this->belongsTo(Perkawinan::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'belum_dibaca');
    }

    /**
     * Scope for user's notifications
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for upcoming notifications
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('tanggal_kirim', '<=', now()->addDays($days))
            ->where('tanggal_kirim', '>=', now())
            ->where('status', 'belum_dibaca');
    }

    /**
     * Scope for health emergency notifications
     */
    public function scopeHealthEmergency($query)
    {
        return $query->where('jenis_notifikasi', 'kesehatan_darurat');
    }
}

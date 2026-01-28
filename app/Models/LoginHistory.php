<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'device_name',
        'browser',
        'platform',
        'location',
        'login_status',
        'login_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'login_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful logins.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('login_status', 'success');
    }

    /**
     * Scope a query to only include failed logins.
     */
    public function scopeFailed($query)
    {
        return $query->where('login_status', 'failed');
    }

    /**
     * Get device icon based on device type.
     */
    public function getDeviceIconAttribute()
    {
        return match ($this->device_type) {
            'mobile' => '📱',
            'tablet' => '📱',
            'desktop' => '💻',
            default => '🖥️'
        };
    }
}

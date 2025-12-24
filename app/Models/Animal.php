<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Animal extends Model
{
    protected $table = 'animals';

    protected $fillable = [
        'kode_hewan',
        'nama_hewan',
        'jenis_hewan',
        'ras_hewan',
        'tanggal_lahir',
        'jenis_kelamin',
        'berat_badan',
        'status_kesehatan',
        'qr_url',
        'user_id',
        'perkawinan_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan' => 'decimal:2',
    ];

    /**
     * Get the user that owns the animal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vaccinations for the animal
     */
    public function vaksinasis(): HasMany
    {
        return $this->hasMany(Vaksinasi::class);
    }

    /**
     * Get the notifications for the animal
     */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    /**
     * Get age in years and months
     */
    public function getUsiaAttribute(): string
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }

        $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
        $now = \Carbon\Carbon::now();

        // Calculate the difference
        $diff = $birthDate->diff($now);

        $years = $diff->y;
        $months = $diff->m;

        if ($years > 0) {
            return $years . ' tahun' . ($months > 0 ? ' ' . $months . ' bulan' : '');
        }

        if ($months > 0) {
            return $months . ' bulan';
        }

        return $diff->d . ' hari';
    }

    /**
     * Scope to filter by jenis
     */
    public function scopeByJenis($query, $jenis)
    {
        if ($jenis && $jenis !== 'all') {
            return $query->where('jenis_hewan', $jenis);
        }
        return $query;
    }

    /**
     * Scope to filter by status kesehatan
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status_kesehatan', $status);
        }
        return $query;
    }

    /**
     * Scope to search by name or kode
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_hewan', 'like', "%{$search}%")
                    ->orWhere('kode_hewan', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Get the health records for the animal
     */
    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }
}

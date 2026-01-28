<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Vaksinasi extends Model
{
    protected $table = 'vaksinasis';

    protected $fillable = [
        'animal_id',
        'tanggal_vaksin',
        'jenis_vaksin',
        'dosis',
        'rute_pemberian',
        'masa_penarikan',
        'nama_dokter',
        'jadwal_berikutnya',
        'catatan',
    ];

    protected $casts = [
        'tanggal_vaksin' => 'date',
        'jadwal_berikutnya' => 'date',
        'masa_penarikan' => 'integer',
    ];

    /**
     * Boot method to clear cache when vaccinations are modified
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($vaksinasi) {
            self::clearUserCaches($vaksinasi);
        });

        static::updated(function ($vaksinasi) {
            self::clearUserCaches($vaksinasi);
        });

        static::deleted(function ($vaksinasi) {
            self::clearUserCaches($vaksinasi);
        });
    }

    /**
     * Clear user-specific caches
     */
    private static function clearUserCaches($vaksinasi)
    {
        $userId = $vaksinasi->animal->user_id ?? null;
        if ($userId) {
            Cache::forget("dashboard_data_user_{$userId}");
            Cache::forget("vaksinasi_stats_user_{$userId}");
        }
    }

    /**
     * Get the animal that owns the vaccination
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Scope to filter by animal
     */
    public function scopeByAnimal($query, $animalId)
    {
        if ($animalId && $animalId !== 'all') {
            return $query->where('animal_id', $animalId);
        }
        return $query;
    }

    /**
     * Scope to filter by jenis vaksin
     */
    public function scopeByJenisVaksin($query, $jenisVaksin)
    {
        if ($jenisVaksin && $jenisVaksin !== 'all') {
            return $query->where('jenis_vaksin', 'like', "%{$jenisVaksin}%");
        }
        return $query;
    }

    /**
     * Scope to search by animal name or code
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->whereHas('animal', function ($q) use ($search) {
                $q->where('nama_hewan', 'like', "%{$search}%")
                    ->orWhere('kode_hewan', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Get rute pemberian label
     */
    public function getRutePemberianLabelAttribute(): string
    {
        return match ($this->rute_pemberian) {
            'oral' => 'Oral',
            'injeksi_im' => 'Injeksi IM (Intramuskular)',
            'injeksi_sc' => 'Injeksi SC (Subkutan)',
            'injeksi_iv' => 'Injeksi IV (Intravena)',
            default => $this->rute_pemberian,
        };
    }
}

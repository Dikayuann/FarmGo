<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class HealthRecord extends Model
{
    protected $table = 'health_records';

    protected $fillable = [
        'animal_id',
        'tanggal_pemeriksaan',
        'jenis_pemeriksaan',
        'berat_badan',
        'suhu_tubuh',
        'status_kesehatan',
        'gejala',
        'diagnosis',
        'tindakan',
        'obat',
        'biaya',
        'catatan',
        'pemeriksaan_berikutnya',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'datetime',
        'pemeriksaan_berikutnya' => 'date',
        'berat_badan' => 'decimal:2',
        'suhu_tubuh' => 'decimal:1',
        'biaya' => 'decimal:2',
    ];

    /**
     * Boot method to clear cache when health records are modified
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($healthRecord) {
            self::clearUserCaches($healthRecord);
        });

        static::updated(function ($healthRecord) {
            self::clearUserCaches($healthRecord);
        });

        static::deleted(function ($healthRecord) {
            self::clearUserCaches($healthRecord);
        });
    }

    /**
     * Clear user-specific caches
     */
    private static function clearUserCaches($healthRecord)
    {
        $userId = $healthRecord->animal->user_id ?? null;
        if ($userId) {
            Cache::forget("dashboard_data_user_{$userId}");
            Cache::forget("health_tasks_user_{$userId}");
            Cache::forget("kesehatan_stats_user_{$userId}");
        }
    }

    /**
     * Get the animal that owns the health record
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
     * Scope to filter by jenis pemeriksaan
     */
    public function scopeByJenisPemeriksaan($query, $jenis)
    {
        if ($jenis && $jenis !== 'all') {
            return $query->where('jenis_pemeriksaan', $jenis);
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
}

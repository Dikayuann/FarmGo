<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
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
        'berat_badan_awal',
        'status_ternak',
        'qr_url',
        'user_id',
        'perkawinan_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan' => 'decimal:2',
        'berat_badan_awal' => 'decimal:2',
    ];

    /**
     * Boot method to clear cache when animals are modified
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache on create, update, or delete
        static::created(function ($animal) {
            self::clearUserCaches($animal->user_id);
            Cache::forget('animals_total_count');
            Cache::forget('animals_by_type_count');
        });

        static::updated(function ($animal) {
            self::clearUserCaches($animal->user_id);
            Cache::forget('animals_total_count');
            Cache::forget('animals_by_type_count');
        });

        static::deleted(function ($animal) {
            self::clearUserCaches($animal->user_id);
            Cache::forget('animals_total_count');
            Cache::forget('animals_by_type_count');
        });
    }

    /**
     * Clear all user-specific caches
     */
    private static function clearUserCaches($userId)
    {
        Cache::forget("dashboard_data_user_{$userId}");
        Cache::forget("health_tasks_user_{$userId}");
        Cache::forget("ternak_stats_user_{$userId}");
        Cache::forget("user_animals_list_{$userId}");
    }

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
     * Scope to filter by status ternak
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status_ternak', $status);
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

    /**
     * Get the mating record this animal was born from (if offspring)
     */
    public function perkawinan(): BelongsTo
    {
        return $this->belongsTo(Perkawinan::class, 'perkawinan_id');
    }

    /**
     * Get all matings where this animal is the male parent
     */
    public function asJantan(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'jantan_id');
    }

    /**
     * Get all matings where this animal is the male parent (Alias)
     */
    public function reproduksisAsJantan(): HasMany
    {
        return $this->asJantan();
    }

    /**
     * Get all matings where this animal is the female parent
     */
    public function asBetina(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'betina_id');
    }

    /**
     * Get all matings where this animal is the female parent (Alias)
     */
    public function reproduksisAsBetina(): HasMany
    {
        return $this->asBetina();
    }

    /**
     * Get parent animals information through the mating record
     */
    public function getParentsAttribute(): ?array
    {
        if (!$this->perkawinan_id || !$this->perkawinan) {
            return null;
        }

        return [
            'jantan' => $this->perkawinan->jantan,
            'betina' => $this->perkawinan->betina,
            'mating_date' => $this->perkawinan->tanggal_perkawinan,
            'method' => $this->perkawinan->metode_perkawinan,
        ];
    }

    /**
     * Check if this betina is eligible for breeding
     */
    public function isEligibleForBreeding(): bool
    {
        // Only betina can breed
        if ($this->jenis_kelamin !== 'betina') {
            return false;
        }

        // Check minimum age for breeding
        if ($this->tanggal_lahir) {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $now = now();

            // If birth date is in the future, animal is not eligible
            if ($birthDate->isFuture()) {
                return false; // Invalid birth date
            }

            $ageInMonths = $birthDate->diffInMonths($now);
            $minAgeMonths = match ($this->jenis_hewan) {
                'sapi' => 18,      // Sapi minimal 18 bulan
                'kambing' => 12,   // Kambing minimal 12 bulan  
                'domba' => 12,     // Domba minimal 12 bulan
                default => 12,
            };

            if ($ageInMonths < $minAgeMonths) {
                return false; // Too young to breed
            }
        }

        // Get latest perkawinan
        $latestPerkawinan = Perkawinan::where('betina_id', $this->id)
            ->orderBy('tanggal_perkawinan', 'desc')
            ->first();

        if (!$latestPerkawinan) {
            return true; // Never bred, eligible
        }

        // Check status
        if ($latestPerkawinan->status_reproduksi === 'bunting') {
            return false; // Pregnant, not eligible
        }

        if ($latestPerkawinan->status_reproduksi === 'melahirkan') {
            // Check recovery period
            $recoveryDays = match ($this->jenis_hewan) {
                'sapi' => 60,
                'kambing' => 45,
                'domba' => 45,
                default => 45,
            };

            if (!$latestPerkawinan->tanggal_melahirkan) {
                return false; // No birth date, not eligible
            }

            $daysSinceBirth = now()->diffInDays($latestPerkawinan->tanggal_melahirkan);

            if ($daysSinceBirth < $recoveryDays) {
                return false; // Still in recovery
            }
        }

        return true; // Eligible
    }

    /**
     * Check if this jantan is eligible for breeding (age-based only)
     */
    public function isJantanEligibleForBreeding(): bool
    {
        // Only jantan can be checked with this method
        if ($this->jenis_kelamin !== 'jantan') {
            return false;
        }

        // Check minimum age for breeding
        if ($this->tanggal_lahir) {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $now = now();

            // If birth date is in the future, animal is not eligible
            if ($birthDate->isFuture()) {
                return false; // Invalid birth date
            }

            $ageInMonths = $birthDate->diffInMonths($now);
            $minAgeMonths = match ($this->jenis_hewan) {
                'sapi' => 18,      // Sapi minimal 18 bulan
                'kambing' => 12,   // Kambing minimal 12 bulan  
                'domba' => 12,     // Domba minimal 12 bulan
                default => 12,
            };

            if ($ageInMonths < $minAgeMonths) {
                return false; // Too young to breed
            }
        }

        return true; // Eligible based on age
    }

    /**
     * Get breeding status message for display
     */
    public function getBreedingStatusMessage(): ?string
    {
        if ($this->jenis_kelamin !== 'betina') {
            return null;
        }

        // Check age first
        if ($this->tanggal_lahir) {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $now = now();

            // If birth date is in the future, return error message
            if ($birthDate->isFuture()) {
                return "Tanggal lahir tidak valid (di masa depan)";
            }

            $ageInMonths = $birthDate->diffInMonths($now);
            $ageInDays = $birthDate->diffInDays($now);
            $minAgeMonths = match ($this->jenis_hewan) {
                'sapi' => 18,
                'kambing' => 12,
                'domba' => 12,
                default => 12,
            };

            if ($ageInMonths < $minAgeMonths) {
                // Format age display based on how old the animal is
                if ($ageInDays < 30) {
                    $ageDisplay = floor($ageInDays) . " hari";
                } elseif ($ageInMonths < 3) {
                    $weeks = floor($ageInDays / 7);
                    $ageDisplay = "{$weeks} minggu";
                } else {
                    $ageDisplay = "{$ageInMonths} bulan";
                }
                return "Belum cukup umur ({$ageDisplay}, Min: {$minAgeMonths} bulan)";
            }
        }

        // Check latest perkawinan
        $latestPerkawinan = \App\Models\Perkawinan::where('betina_id', $this->id)
            ->orderBy('tanggal_perkawinan', 'desc')
            ->first();

        if (!$latestPerkawinan) {
            return null; // No message, eligible
        }

        if ($latestPerkawinan->status_reproduksi === 'bunting') {
            $estimasi = \Carbon\Carbon::parse($latestPerkawinan->estimasi_kelahiran);
            $sisaHari = now()->diffInDays($estimasi, false);
            return "Sedang bunting (sisa {$sisaHari} hari)";
        }

        if ($latestPerkawinan->status_reproduksi === 'melahirkan') {
            $recoveryDays = match ($this->jenis_hewan) {
                'sapi' => 60,
                'kambing' => 45,
                'domba' => 45,
                default => 45,
            };

            if (!$latestPerkawinan->tanggal_melahirkan) {
                return "Baru melahirkan (tanggal tidak tercatat)";
            }

            $daysSinceBirth = now()->diffInDays($latestPerkawinan->tanggal_melahirkan);

            if ($daysSinceBirth < $recoveryDays) {
                $sisaHari = $recoveryDays - $daysSinceBirth;
                return "Masa pemulihan (sisa {$sisaHari} hari)";
            }
        }

        return null; // Eligible
    }

    /**
     * Get breeding status message for jantan
     */
    public function getJantanBreedingStatusMessage(): ?string
    {
        if ($this->jenis_kelamin !== 'jantan') {
            return null;
        }

        // Check age
        if ($this->tanggal_lahir) {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $now = now();

            // If birth date is in the future, return error message
            if ($birthDate->isFuture()) {
                return "Tanggal lahir tidak valid";
            }

            $ageInMonths = $birthDate->diffInMonths($now);
            $ageInDays = $birthDate->diffInDays($now);
            $minAgeMonths = match ($this->jenis_hewan) {
                'sapi' => 18,
                'kambing' => 12,
                'domba' => 12,
                default => 12,
            };

            if ($ageInMonths < $minAgeMonths) {
                // Format age display based on how old the animal is
                if ($ageInDays < 30) {
                    $ageDisplay = floor($ageInDays) . " hari";
                } elseif ($ageInMonths < 3) {
                    $weeks = floor($ageInDays / 7);
                    $ageDisplay = "{$weeks} minggu";
                } else {
                    $ageDisplay = "{$ageInMonths} bulan";
                }
                return "Belum cukup umur ({$ageDisplay}, Min: {$minAgeMonths} bulan)";
            }
        }

        return null; // Eligible
    }
}

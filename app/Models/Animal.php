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
        'status_ternak',
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
     * Get all matings where this animal is the female parent
     */
    public function asBetina(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'betina_id');
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
     * Get breeding status message for this betina
     */
    public function getBreedingStatusMessage(): ?string
    {
        if ($this->jenis_kelamin !== 'betina') {
            return null;
        }

        $latestPerkawinan = Perkawinan::where('betina_id', $this->id)
            ->orderBy('tanggal_perkawinan', 'desc')
            ->first();

        if (!$latestPerkawinan) {
            return 'Siap dikawinkan';
        }

        if ($latestPerkawinan->status_reproduksi === 'bunting') {
            $sisaHari = $latestPerkawinan->sisa_hari ?? 0;
            return "Sedang bunting (sisa {$sisaHari} hari)";
        }

        if ($latestPerkawinan->status_reproduksi === 'melahirkan') {
            $recoveryDays = match ($this->jenis_hewan) {
                'sapi' => 60,
                'kambing' => 45,
                'domba' => 45,
                default => 45,
            };

            if ($latestPerkawinan->tanggal_melahirkan) {
                $daysSinceBirth = now()->diffInDays($latestPerkawinan->tanggal_melahirkan);
                $remainingDays = $recoveryDays - $daysSinceBirth;

                if ($remainingDays > 0) {
                    return "Masa pemulihan ({$remainingDays} hari lagi)";
                }
            }
        }

        return 'Siap dikawinkan';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Perkawinan extends Model
{
    protected $table = 'perkawinans';

    protected $fillable = [
        'kode_perkawinan',
        'jantan_type',
        'jantan_id',
        'jantan_external_name',
        'jantan_external_breed',
        'jantan_external_owner',
        'jantan_external_reg_number',
        'semen_code',
        'semen_producer',
        'semen_breed',
        'betina_id',
        'tanggal_birahi',
        'tanggal_perkawinan',
        'metode_perkawinan',
        'inseminator_name',
        'ib_time',
        'straw_count',
        'status_reproduksi',
        'tanggal_melahirkan',
        'jumlah_anak',
        'estimasi_kelahiran',
        'reminder_birahi_berikutnya',
        'reminder_status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_birahi' => 'date',
        'tanggal_perkawinan' => 'date',
        'tanggal_melahirkan' => 'date',
        'estimasi_kelahiran' => 'date',
        'reminder_birahi_berikutnya' => 'date',
    ];

    /**
     * Get the male parent animal
     */
    public function jantan(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'jantan_id');
    }

    /**
     * Get the female parent animal
     */
    public function betina(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'betina_id');
    }

    /**
     * Get all offspring from this mating
     */
    public function offspring(): HasMany
    {
        return $this->hasMany(Animal::class, 'perkawinan_id');
    }

    /**
     * Get notification reminders for this mating
     */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'perkawinan_id');
    }

    /**
     * Get the duration of pregnancy in days
     */
    public function getDurasiKebuntinganAttribute(): ?int
    {
        if (!$this->tanggal_perkawinan || !$this->tanggal_melahirkan) {
            return null;
        }

        $matingDate = Carbon::parse($this->tanggal_perkawinan);
        $birthDate = Carbon::parse($this->tanggal_melahirkan);

        return $matingDate->diffInDays($birthDate);
    }

    /**
     * Get remaining days until estimated birth
     */
    public function getSisaHariAttribute(): ?int
    {
        if (!$this->estimasi_kelahiran) {
            return null;
        }

        $today = Carbon::today();
        $estimatedBirth = Carbon::parse($this->estimasi_kelahiran);

        // Return 0 if already passed, otherwise return days remaining
        if ($estimatedBirth < $today) {
            return 0;
        }

        return $today->diffInDays($estimatedBirth);
    }

    /**
     * Scope to filter by reproduction status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status_reproduksi', $status);
        }
        return $query;
    }

    /**
     * Scope to get matings with upcoming reminders
     */
    public function scopeUpcomingReminders($query, $days = 14)
    {
        $today = Carbon::today();
        $futureDate = $today->copy()->addDays($days);

        return $query->where('reminder_status', 'aktif')
            ->whereNotNull('reminder_birahi_berikutnya')
            ->whereBetween('reminder_birahi_berikutnya', [$today, $futureDate])
            ->orderBy('reminder_birahi_berikutnya', 'asc');
    }

    /**
     * Scope to filter by user ownership (through jantan or betina)
     */
    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('jantan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orWhereHas('betina', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Calculate gestation period based on animal type
     */
    public static function getGestationPeriod($jenisHewan): int
    {
        return match ($jenisHewan) {
            'sapi' => 283,  // ~9 months
            'kambing' => 150, // ~5 months
            'domba' => 147,  // ~5 months
            default => 150,
        };
    }

    /**
     * Get the heat cycle interval (typically 21 days for most livestock)
     */
    public static function getHeatCycleInterval(): int
    {
        return 21; // days
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class HeatDetection extends Model
{
    protected $table = 'heat_detections';

    protected $fillable = [
        'animal_id',
        'tanggal_deteksi',
        'gejala',
        'catatan',
        'status',
        'perkawinan_id',
    ];

    protected $casts = [
        'tanggal_deteksi' => 'date',
        'gejala' => 'array',
    ];

    /**
     * Common heat symptoms (gejala birahi)
     */
    const GEJALA_OPTIONS = [
        'gelisah' => 'Gelisah/Resah',
        'mengembik' => 'Mengembik/Melenguh Sering',
        'nafsu_makan_turun' => 'Nafsu Makan Menurun',
        'ekor_terangkat' => 'Ekor Terangkat',
        'lendir_bening' => 'Lendir Bening dari Vulva',
        'mounting' => 'Menunggang Ternak Lain',
        'vulva_membengkak' => 'Vulva Membengkak & Merah',
    ];

    /**
     * Get animal that is in heat
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Get perkawinan record if this heat led to breeding
     */
    public function perkawinan(): BelongsTo
    {
        return $this->belongsTo(Perkawinan::class);
    }

    /**
     * Scope to get pending heat detections
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get recent heat detections (within 7 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('tanggal_deteksi', '>=', Carbon::now()->subDays(7));
    }

    /**
     * Get formatted gejala as readable text
     */
    public function getFormattedGejalaAttribute(): string
    {
        if (!$this->gejala || empty($this->gejala)) {
            return 'Tidak ada gejala tercatat';
        }

        $symptoms = [];
        foreach ($this->gejala as $key) {
            if (isset(self::GEJALA_OPTIONS[$key])) {
                $symptoms[] = self::GEJALA_OPTIONS[$key];
            }
        }

        return implode(', ', $symptoms);
    }

    /**
     * Check if heat detection is recent (within 3 days)
     */
    public function isRecent(): bool
    {
        return $this->tanggal_deteksi->diffInDays(Carbon::now()) <= 3;
    }
}

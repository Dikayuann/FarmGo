<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'animal_id',
        'jenis_notifikasi',
        'pesan',
        'tanggal_kirim',
        'status',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    /**
     * Get the animal that owns the notification
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }
}

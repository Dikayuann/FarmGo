<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'animal_id',
        'event_type',
        'title',
        'description',
        'event_date',
        'completed',
        'reminder_sent',
    ];

    protected $casts = [
        'event_date' => 'date',
        'completed' => 'boolean',
        'reminder_sent' => 'boolean',
    ];

    // Event types constants
    const TYPE_VACCINATION = 'vaccination';
    const TYPE_BIRTH_ESTIMATE = 'birth_estimate';
    const TYPE_HEALTH_CHECKUP = 'health_checkup';
    const TYPE_HEAT_DETECTION = 'heat_detection';
    const TYPE_CUSTOM = 'custom';

    /**
     * Get the user that owns the event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the animal related to this event
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('event_date', '>=', now())
            ->where('event_date', '<=', now()->addDays($days))
            ->where('completed', false)
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope for events by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for completed events
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Get days until event
     */
    public function getDaysUntilAttribute()
    {
        return now()->diffInDays($this->event_date, false);
    }

    /**
     * Get event type color for UI
     */
    public function getTypeColorAttribute()
    {
        return match ($this->event_type) {
            self::TYPE_VACCINATION => 'red',
            self::TYPE_BIRTH_ESTIMATE => 'green',
            self::TYPE_HEALTH_CHECKUP => 'blue',
            self::TYPE_HEAT_DETECTION => 'pink',
            self::TYPE_CUSTOM => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get event type icon
     */
    public function getTypeIconAttribute()
    {
        return match ($this->event_type) {
            self::TYPE_VACCINATION => '💉',
            self::TYPE_BIRTH_ESTIMATE => '🐄',
            self::TYPE_HEALTH_CHECKUP => '🩺',
            self::TYPE_HEAT_DETECTION => '💕',
            self::TYPE_CUSTOM => '📌',
            default => '📅',
        };
    }

    /**
     * Get human readable countdown
     */
    public function getCountdownTextAttribute()
    {
        $days = $this->days_until;

        if ($days < 0) {
            return abs($days) . ' hari lalu';
        } elseif ($days == 0) {
            return 'Hari ini';
        } elseif ($days == 1) {
            return 'Besok';
        } else {
            return $days . ' hari lagi';
        }
    }
}

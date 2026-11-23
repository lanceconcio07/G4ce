<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultant_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'day_of_week' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    public function getDayNameAttribute(): string
    {
        return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$this->day_of_week] ?? 'Unknown';
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    // ──────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}

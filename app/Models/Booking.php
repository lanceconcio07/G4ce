<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_code',
        'client_name',
        'client_email',
        'client_phone',
        'company_name',
        'operations_country',
        'operational_challenges',
        'service_id',
        'consultant_id',
        'headcount_range',
        'scheduled_at',
        'client_timezone',
        'duration_minutes',
        'meeting_link',
        'nda_requested',
        'status',
        'cancellation_reason',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'scheduled_at'   => 'datetime',
        'confirmed_at'   => 'datetime',
        'cancelled_at'   => 'datetime',
        'nda_requested'  => 'boolean',
    ];

    // ──────────────────────────────────────────
    // Boot — auto-generate reference code
    // ──────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->reference_code)) {
                $booking->reference_code = static::generateReferenceCode();
            }
        });
    }

    public static function generateReferenceCode(): string
    {
        do {
            $code = 'G4CE-BK-' . strtoupper(Str::random(5));
        } while (static::where('reference_code', $code)->exists());

        return $code;
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->where('scheduled_at', '>', now());
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // ──────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}

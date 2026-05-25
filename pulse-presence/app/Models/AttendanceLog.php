<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'shift_id',
        'type',
        'timestamp',
        'latitude',
        'longitude',
        'accuracy',
        'ip_address',
        'device_fingerprint_id',
        'selfie_path',
        'selfie_compressed_path',
        'selfie_watermarked_path',
        'risk_score',
        'risk_level',
        'status',
        'work_mode',
        'is_late',
        'is_early_leave',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'accuracy' => 'float',
        'risk_score' => 'integer',
        'is_late' => 'boolean',
        'is_early_leave' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the attendance log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch for the attendance log.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the shift for the attendance log.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Get the device fingerprint for the attendance log.
     */
    public function deviceFingerprint(): BelongsTo
    {
        return $this->belongsTo(DeviceFingerprint::class);
    }

    /**
     * Get the suspicious events for the attendance log.
     */
    public function suspiciousEvents(): HasMany
    {
        return $this->hasMany(SuspiciousEvent::class);
    }

    /**
     * Scope a query to only include check-ins.
     */
    public function scopeCheckIns($query)
    {
        return $query->where('type', 'checkin');
    }

    /**
     * Scope a query to only include check-outs.
     */
    public function scopeCheckOuts($query)
    {
        return $query->where('type', 'checkout');
    }

    /**
     * Scope a query to only include high risk logs.
     */
    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'high');
    }

    /**
     * Scope a query to only include flagged logs.
     */
    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }
}

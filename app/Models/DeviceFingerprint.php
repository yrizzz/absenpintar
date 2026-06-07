<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceFingerprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_hash',
        'browser',
        'os',
        'platform',
        'timezone',
        'language',
        'screen_resolution',
        'hardware_concurrency',
        'gpu_info',
        'trusted',
        'last_used_at',
    ];

    protected $casts = [
        'trusted' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the user that owns the device fingerprint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance logs for the device fingerprint.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Mark this device as trusted.
     */
    public function markAsTrusted(): void
    {
        $this->update(['trusted' => true]);
    }

    /**
     * Update last used timestamp.
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}

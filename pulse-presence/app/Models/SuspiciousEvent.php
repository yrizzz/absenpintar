<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspiciousEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_log_id',
        'event_type',
        'risk_score',
        'description',
        'metadata',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'metadata' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the suspicious event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance log for the suspicious event.
     */
    public function attendanceLog(): BelongsTo
    {
        return $this->belongsTo(AttendanceLog::class);
    }

    /**
     * Get the user who reviewed the suspicious event.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include pending events.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include high risk events.
     */
    public function scopeHighRisk($query)
    {
        return $query->where('risk_score', '>=', 60);
    }

    /**
     * Mark the event as reviewed.
     */
    public function markAsReviewed(int $reviewerId, string $notes = null): void
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);
    }
}

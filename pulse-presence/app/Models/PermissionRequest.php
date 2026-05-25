<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'date',
        'start_time',
        'end_time',
        'reason',
        'attachment_path',
        'status_dept_head',
        'dept_head_id',
        'dept_head_approved_at',
        'status_hr',
        'hr_id',
        'hr_approved_at',
        'status',
        'approval_notes',
    ];

    protected $casts = [
        'date' => 'date',
        'dept_head_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the permission request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Department Head who approved.
     */
    public function deptHead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dept_head_id');
    }

    /**
     * Get the HR who approved.
     */
    public function hr(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if the permission request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}

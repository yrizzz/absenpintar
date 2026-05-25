<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'start_time',
        'end_time',
        'grace_period',
        'late_threshold',
        'overtime_threshold',
        'working_days',
        'is_active',
    ];

    protected $casts = [
        'working_days' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the shift.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('effective_date', 'end_date')
            ->withTimestamps();
    }

    /**
     * Get the attendance logs for the shift.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Check if the shift is active on a given day.
     */
    public function isActiveOnDay(int $dayOfWeek): bool
    {
        if (empty($this->working_days)) {
            return true;
        }

        return in_array($dayOfWeek, $this->working_days);
    }

    /**
     * Check if a time is late for this shift.
     */
    public function isLate(string $checkInTime): bool
    {
        $shiftStart = strtotime($this->start_time);
        $checkIn = strtotime($checkInTime);
        $gracePeriod = $this->grace_period * 60; // convert to seconds

        return ($checkIn - $shiftStart) > $gracePeriod;
    }
}

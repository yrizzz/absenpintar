<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'employee_id',
    'name',
    'email',
    'password',
    'branch_id',
    'phone',
    'date_of_birth',
    'joined_at',
    'avatar',
    'status',
    'work_mode',
    'two_factor_enabled',
    'two_factor_secret'
])]
#[Hidden(['password', 'remember_token', 'two_factor_secret'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'date_of_birth' => 'date',
            'joined_at' => 'date',
        ];
    }

    /**
     * Get the branch that the user belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the device fingerprints for the user.
     */
    public function deviceFingerprints(): HasMany
    {
        return $this->hasMany(DeviceFingerprint::class);
    }

    /**
     * Get the attendance logs for the user.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Get the leave requests for the user.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the permission requests for the user.
     */
    public function permissionRequests(): HasMany
    {
        return $this->hasMany(PermissionRequest::class);
    }

    /**
     * Get the shifts for the user.
     */
    public function shifts(): BelongsToMany
    {
        return $this->belongsToMany(Shift::class)
            ->withPivot('effective_date', 'end_date')
            ->withTimestamps();
    }

    /**
     * Get the suspicious events for the user.
     */
    public function suspiciousEvents(): HasMany
    {
        return $this->hasMany(SuspiciousEvent::class);
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Determine if the user has registered their baseline face biometric template.
     */
    public function hasRegisteredFace(): bool
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $this->id . '.jpg');
    }

    /**
     * Retrieve base64 formatted master face data.
     */
    public function getMasterFaceUrl(): ?string
    {
        if ($this->hasRegisteredFace()) {
            try {
                return 'data:image/jpeg;base64,' . base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get('master_face/user_' . $this->id . '.jpg'));
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}

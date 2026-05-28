<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Attendance\CheckIn;
use App\Livewire\Attendance\CheckOut;
use App\Livewire\Attendance\History;
use App\Livewire\Attendance\BiometricDemo;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
});

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Attendance routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', History::class)->name('index');
        Route::get('/checkin', CheckIn::class)->name('checkin');
        Route::get('/checkout', CheckOut::class)->name('checkout');
        Route::get('/demo', BiometricDemo::class)->name('demo');
    });
    
    // Leaves routes
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', \App\Livewire\Leaves\LeavesIndex::class)->name('index');
    });
    
    // Permissions routes (manager, hr_admin, super_admin only)
    Route::middleware(['role:super_admin|hr_admin|manager'])->prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', \App\Livewire\Permissions\PermissionsIndex::class)->name('index');
    });
    
    // Reports routes (hr_admin, super_admin only)
    Route::middleware(['role:super_admin|hr_admin'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', \App\Livewire\Reports\ReportsIndex::class)->name('index');
        Route::get('/print', function () {
            $report_type = request('type', 'presence_summary');
            $report_period = request('period', 'monthly');
            $user_id = request('user_id');
            $branch_id = request('branch_id');
            $start_date = request('start_date');
            $end_date = request('end_date');
            $selected_ids = request('selected_ids');

            if ($report_type === 'presence_summary') {
                $query = \App\Models\AttendanceLog::with('user')->orderBy('timestamp', 'desc');
            } elseif ($report_type === 'coordinates_log') {
                $query = \App\Models\AttendanceLog::with(['user', 'branch'])->orderBy('timestamp', 'desc');
            } elseif ($report_type === 'leaves_audit') {
                $query = \App\Models\LeaveRequest::with('user')->orderBy('start_date', 'desc');
            } else {
                $query = \App\Models\DeviceFingerprint::with('user')->orderBy('last_used_at', 'desc');
            }

            // Prioritize checked selected IDs
            if ($selected_ids) {
                $idsArray = array_filter(explode(',', $selected_ids));
                if (!empty($idsArray)) {
                    $query->whereIn('id', $idsArray);
                }
            } else {
                // Apply filters if present
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
                if ($branch_id) {
                    if ($report_type === 'presence_summary' || $report_type === 'coordinates_log') {
                        $query->where('branch_id', $branch_id);
                    }
                }
                if ($start_date) {
                    if ($report_type === 'presence_summary' || $report_type === 'coordinates_log') {
                        $query->whereDate('timestamp', '>=', $start_date);
                    } elseif ($report_type === 'leaves_audit') {
                        $query->whereDate('start_date', '>=', $start_date);
                    }
                }
                if ($end_date) {
                    if ($report_type === 'presence_summary' || $report_type === 'coordinates_log') {
                        $query->whereDate('timestamp', '<=', $end_date);
                    } elseif ($report_type === 'leaves_audit') {
                        $query->whereDate('end_date', '<=', $end_date);
                    }
                }
            }

            $data = $query->get();

            return view('reports.printable', compact('data', 'report_type', 'report_period'));
        })->name('print');
    });
    
    // Settings routes (hr_admin, super_admin only)
    Route::middleware(['role:super_admin|hr_admin'])->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', \App\Livewire\Settings\SettingsIndex::class)->name('index');
    });
    
    // ========================================================
    // Official Letter Print Routes (Surat Resmi PDF)
    // ========================================================
    Route::prefix('letters')->name('letters.')->group(function () {

        // Surat Permohonan Cuti
        Route::get('/leave/{id}', function ($id) {
            $leave = \App\Models\LeaveRequest::with(['user.branch', 'user.roles', 'manager', 'hr'])->findOrFail($id);

            // Authorization: only the owner or admin can print
            $user = auth()->user();
            if ($leave->user_id !== $user->id && !$user->hasAnyRole(['super_admin', 'hr_admin'])) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak surat ini.');
            }

            return view('letters.leave-letter', compact('leave'));
        })->name('leave');

        // Surat Izin Kerja / Dispensasi
        Route::get('/permission/{id}', function ($id) {
            $permission = \App\Models\PermissionRequest::with(['user.branch', 'user.roles', 'deptHead', 'hr'])->findOrFail($id);

            $user = auth()->user();
            if ($permission->user_id !== $user->id && !$user->hasAnyRole(['super_admin', 'hr_admin', 'manager'])) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak surat ini.');
            }

            return view('letters.permission-letter', compact('permission'));
        })->name('permission');

        // Surat Keterangan Kehadiran
        Route::get('/attendance-certificate', function () {
            $targetUserId = request('user_id', auth()->id());
            $startDate = request('start_date', now()->startOfMonth()->toDateString());
            $endDate = request('end_date', now()->toDateString());

            $authUser = auth()->user();
            // Regular employees can only print their own certificate
            if ((int)$targetUserId !== $authUser->id && !$authUser->hasAnyRole(['super_admin', 'hr_admin'])) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak surat keterangan karyawan lain.');
            }

            $user = \App\Models\User::with(['branch', 'roles'])->findOrFail($targetUserId);
            $logs = \App\Models\AttendanceLog::with('branch')
                ->where('user_id', $targetUserId)
                ->whereBetween('timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->orderBy('timestamp', 'asc')
                ->get();

            return view('letters.attendance-certificate', compact('user', 'logs', 'startDate', 'endDate'));
        })->name('attendance-certificate');
    });

    // Profile route
    Route::get('/profile', \App\Livewire\Profile\ProfileIndex::class)->name('profile');
    
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});


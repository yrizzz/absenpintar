<?php

namespace Tests\Feature;

use App\Livewire\Leaves\LeavesIndex;
use App\Models\Branch;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LeaveApprovalTest extends TestCase
{
    use RefreshDatabase;

    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'hr_admin', 'manager', 'employee'] as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        $this->branch = Branch::create([
            'name' => 'Head Office',
            'code' => 'HO',
            'latitude' => -6.2,
            'longitude' => 106.81,
            'radius' => 100,
            'is_active' => true,
        ]);
    }

    private function makeUser(string $role, array $attrs = []): User
    {
        static $seq = 0;
        $seq++;

        $user = User::create(array_merge([
            'employee_id' => 'EMP' . str_pad((string) $seq, 3, '0', STR_PAD_LEFT),
            'name' => ucfirst($role) . ' ' . $seq,
            'email' => $role . $seq . '@example.com',
            'password' => bcrypt('password'),
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'work_mode' => 'wfo',
        ], $attrs));

        $user->assignRole($role);

        return $user;
    }

    public function test_employee_submission_creates_pending_request(): void
    {
        $employee = $this->makeUser('employee');

        Livewire::actingAs($employee)
            ->test(LeavesIndex::class)
            ->set('type', 'annual')
            ->set('start_date', now()->addDays(3)->toDateString())
            ->set('end_date', now()->addDays(5)->toDateString())
            ->set('reason', 'Keperluan keluarga')
            ->call('submitRequest')
            ->assertHasNoErrors();

        $leave = LeaveRequest::first();
        $this->assertNotNull($leave);
        $this->assertSame('pending', $leave->status);
        $this->assertSame(3, $leave->total_days);
    }

    public function test_full_two_stage_approval_flow(): void
    {
        $employee = $this->makeUser('employee');
        $manager = $this->makeUser('manager');
        $hr = $this->makeUser('hr_admin');

        $leave = LeaveRequest::create([
            'user_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'total_days' => 2,
            'reason' => 'x',
            'status' => 'pending',
        ]);

        // Stage 1: manager approves
        Livewire::actingAs($manager)
            ->test(LeavesIndex::class)
            ->call('managerApprove', $leave->id);
        $leave->refresh();
        $this->assertSame('manager_approved', $leave->status);
        $this->assertSame($manager->id, $leave->manager_id);

        // Stage 2: HR finalizes
        Livewire::actingAs($hr)
            ->test(LeavesIndex::class)
            ->call('hrApprove', $leave->id);
        $leave->refresh();
        $this->assertSame('hr_approved', $leave->status);
        $this->assertSame($hr->id, $leave->hr_id);
    }

    public function test_hr_cannot_finalize_before_manager(): void
    {
        $employee = $this->makeUser('employee');
        $hr = $this->makeUser('hr_admin');

        $leave = LeaveRequest::create([
            'user_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'total_days' => 2,
            'reason' => 'x',
            'status' => 'pending',
        ]);

        Livewire::actingAs($hr)
            ->test(LeavesIndex::class)
            ->call('hrApprove', $leave->id);

        $this->assertSame('pending', $leave->refresh()->status);
    }

    public function test_manager_cannot_approve_own_request(): void
    {
        $manager = $this->makeUser('manager');

        $leave = LeaveRequest::create([
            'user_id' => $manager->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'total_days' => 2,
            'reason' => 'x',
            'status' => 'pending',
        ]);

        Livewire::actingAs($manager)
            ->test(LeavesIndex::class)
            ->call('managerApprove', $leave->id);

        $this->assertSame('pending', $leave->refresh()->status);
    }

    public function test_annual_quota_is_enforced(): void
    {
        $employee = $this->makeUser('employee', ['annual_leave_quota' => 5]);

        Livewire::actingAs($employee)
            ->test(LeavesIndex::class)
            ->set('type', 'annual')
            ->set('start_date', now()->addDays(1)->toDateString())
            ->set('end_date', now()->addDays(10)->toDateString()) // 10 days > quota 5
            ->set('reason', 'Liburan panjang')
            ->call('submitRequest')
            ->assertHasErrors(['type']);

        $this->assertSame(0, LeaveRequest::count());
    }

    public function test_overlapping_request_is_rejected(): void
    {
        $employee = $this->makeUser('employee');

        LeaveRequest::create([
            'user_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'total_days' => 4,
            'reason' => 'first',
            'status' => 'pending',
        ]);

        Livewire::actingAs($employee)
            ->test(LeavesIndex::class)
            ->set('type', 'unpaid')
            ->set('start_date', now()->addDays(5)->toDateString()) // overlaps
            ->set('end_date', now()->addDays(7)->toDateString())
            ->set('reason', 'second')
            ->call('submitRequest')
            ->assertHasErrors(['start_date']);

        $this->assertSame(1, LeaveRequest::count());
    }

    public function test_sick_leave_requires_attachment(): void
    {
        Storage::fake('public');
        $employee = $this->makeUser('employee');

        // Without attachment -> error
        Livewire::actingAs($employee)
            ->test(LeavesIndex::class)
            ->set('type', 'sick')
            ->set('start_date', now()->addDays(1)->toDateString())
            ->set('end_date', now()->addDays(1)->toDateString())
            ->set('reason', 'Demam')
            ->call('submitRequest')
            ->assertHasErrors(['attachment']);

        $this->assertSame(0, LeaveRequest::count());

        // With attachment -> success
        Livewire::actingAs($employee)
            ->test(LeavesIndex::class)
            ->set('type', 'sick')
            ->set('start_date', now()->addDays(1)->toDateString())
            ->set('end_date', now()->addDays(1)->toDateString())
            ->set('reason', 'Demam')
            ->set('attachment', UploadedFile::fake()->create('surat-dokter.pdf', 120, 'application/pdf'))
            ->call('submitRequest')
            ->assertHasNoErrors();

        $this->assertSame(1, LeaveRequest::count());
        $this->assertNotNull(LeaveRequest::first()->attachment_path);
    }
}

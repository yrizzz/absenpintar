<?php

namespace Tests\Feature;

use App\Livewire\Permissions\PermissionsIndex;
use App\Models\Branch;
use App\Models\PermissionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionApprovalTest extends TestCase
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

    private function makeUser(string $role): User
    {
        static $seq = 0;
        $seq++;

        $user = User::create([
            'employee_id' => 'EMP' . str_pad((string) $seq, 3, '0', STR_PAD_LEFT),
            'name' => ucfirst($role) . ' ' . $seq,
            'email' => $role . $seq . '@example.com',
            'password' => bcrypt('password'),
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'work_mode' => 'wfo',
        ]);

        $user->assignRole($role);

        return $user;
    }

    private function makeRequest(User $employee): PermissionRequest
    {
        return PermissionRequest::create([
            'user_id' => $employee->id,
            'type' => 'ijin_datang_terlambat',
            'date' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'reason' => 'Macet',
            'status_dept_head' => 'pending',
            'status_hr' => 'pending',
            'status' => 'pending',
        ]);
    }

    public function test_hr_cannot_approve_before_dept_head(): void
    {
        $employee = $this->makeUser('employee');
        $hr = $this->makeUser('hr_admin');
        $req = $this->makeRequest($employee);

        Livewire::actingAs($hr)
            ->test(PermissionsIndex::class)
            ->call('approveHr', $req->id);

        $req->refresh();
        $this->assertSame('pending', $req->status_hr);
        $this->assertSame('pending', $req->status);
    }

    public function test_sequential_approval_completes_request(): void
    {
        $employee = $this->makeUser('employee');
        $manager = $this->makeUser('manager');
        $hr = $this->makeUser('hr_admin');
        $req = $this->makeRequest($employee);

        Livewire::actingAs($manager)
            ->test(PermissionsIndex::class)
            ->call('approveDeptHead', $req->id);
        $req->refresh();
        $this->assertSame('approved', $req->status_dept_head);
        $this->assertSame('pending', $req->status);

        Livewire::actingAs($hr)
            ->test(PermissionsIndex::class)
            ->call('approveHr', $req->id);
        $req->refresh();
        $this->assertSame('approved', $req->status_hr);
        $this->assertSame('approved', $req->status);
    }

    public function test_user_cannot_approve_own_request(): void
    {
        $manager = $this->makeUser('manager');
        $req = $this->makeRequest($manager);

        Livewire::actingAs($manager)
            ->test(PermissionsIndex::class)
            ->call('approveDeptHead', $req->id);

        $this->assertSame('pending', $req->refresh()->status_dept_head);
    }
}

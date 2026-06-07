<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branch = Branch::create([
            'name' => 'Head Office',
            'code' => 'HO',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'is_active' => true,
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeLivewire(Login::class);
    }

    public function test_active_user_can_login_successfully(): void
    {
        $user = User::create([
            'employee_id' => 'EMP001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'branch_id' => $this->branch->id,
            'status' => 'active',
            'work_mode' => 'wfo',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'john@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::create([
            'employee_id' => 'EMP002',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'branch_id' => $this->branch->id,
            'status' => 'inactive',
            'work_mode' => 'wfo',
        ]);

        $this->actingAs($user);

        // Try to access dashboard, EnsureUserIsActive middleware should log user out and redirect
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_shows_error(): void
    {
        Livewire::test(Login::class)
            ->set('email', 'nonexistent@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }
}

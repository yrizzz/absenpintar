<?php

namespace Tests\Feature;

use App\Livewire\Settings\SettingsIndex;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $branch = Branch::create([
            'name' => 'HO', 'code' => 'HO', 'latitude' => -6.2, 'longitude' => 106.8,
            'radius' => 100, 'is_active' => true,
        ]);

        $user = User::create([
            'employee_id' => 'ADM001',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'branch_id' => $branch->id,
            'status' => 'active',
            'work_mode' => 'wfo',
        ]);
        $user->assignRole('super_admin');

        return $user;
    }

    public function test_settings_are_persisted_to_database_and_cache(): void
    {
        Livewire::actingAs($this->admin())
            ->test(SettingsIndex::class)
            ->set('radius', 350)
            ->set('company_name', 'PT Contoh Sejahtera')
            ->set('company_email', 'hr@contoh.test')
            ->call('saveSettings')
            ->assertHasNoErrors();

        // Persisted to DB (survives cache:clear)
        $this->assertDatabaseHas('settings', ['key' => 'radius']);
        $this->assertEquals(350.0, json_decode(Setting::find('radius')->value, true));
        $this->assertSame('PT Contoh Sejahtera', json_decode(Setting::find('company_name')->value, true));

        // Mirrored into cache for existing readers
        $this->assertEquals(350.0, Cache::get('settings.radius'));
        $this->assertSame('PT Contoh Sejahtera', Cache::get('settings.company_name'));
    }

    public function test_invalid_settings_are_rejected(): void
    {
        Livewire::actingAs($this->admin())
            ->test(SettingsIndex::class)
            ->set('company_email', 'not-an-email')
            ->call('saveSettings')
            ->assertHasErrors(['company_email']);

        $this->assertDatabaseMissing('settings', ['key' => 'company_email']);
    }

    public function test_hydrate_cache_restores_values_after_clear(): void
    {
        Setting::put('radius', 999.0);
        Cache::flush(); // simulate cache:clear

        Setting::hydrateCache();

        $this->assertEquals(999.0, Cache::get('settings.radius'));
    }
}

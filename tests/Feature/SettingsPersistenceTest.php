<?php

namespace Tests\Feature;

use App\Livewire\Admin\SettingsComponent;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsPersistenceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_can_save_clinic_information(): void
    {
        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $setting = Setting::getSettings();

        Livewire::actingAs($admin)
            ->test(SettingsComponent::class)
            ->set('state.clinic_name', 'Vision Space Clinic')
            ->set('state.clinic_address', 'Accra Central')
            ->set('state.clinic_contact', '0598882009')
            ->set('state.clinic_email', 'clinic@example.com')
            ->call('updateSettings')
            ->assertHasNoErrors()
            ->assertDispatched('notify');

        $setting->refresh();

        $this->assertSame('Vision Space Clinic', $setting->clinic_name);
        $this->assertSame('Accra Central', $setting->clinic_address);
        $this->assertSame('0598882009', $setting->clinic_contact);
        $this->assertSame('clinic@example.com', $setting->clinic_email);
    }
}

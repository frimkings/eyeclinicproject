<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['Super Admin', 'Doctor', 'Cashier', 'Staff', 'Manager', 'Secretary'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $users = [
            [
                'email' => 'admin@eyeclinic.com',
                'name' => 'System Administrator',
                'password' => 'password',
                'role' => 'Super Admin',
            ],
            [
                'email' => 'frimkings@gmail.com',
                'name' => 'Dr. Kingsford',
                'password' => 'password',
                'role' => 'Doctor',
            ],
            [
                'email' => 'secretary@gmail.com',
                'name' => 'Secretary',
                'password' => 'password',
                'role' => 'Secretary',
            ],
            [
                'email' => 'staff@eyeclinic.com',
                'name' => 'Front Desk Staff',
                'password' => 'staff123',
                'role' => 'Staff',
            ],
        ];

        foreach ($users as $seedUser) {
            $user = User::updateOrCreate(
                ['email' => $seedUser['email']],
                [
                    'name' => $seedUser['name'],
                    'password' => Hash::make($seedUser['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->forceFill(['is_active' => true])->save();

            if (!$user->hasRole($seedUser['role'])) {
                $user->assignRole($seedUser['role']);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Default users seeded with current Spatie roles successfully.');
    }
}

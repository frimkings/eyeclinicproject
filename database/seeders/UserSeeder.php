<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 0: Admin
        User::updateOrCreate(
            ['email' => 'admin@eyeclinic.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                // 'role' => 0,
                'email_verified_at' => now(),
            ]
        );

        
        // 1: Doctor
        User::updateOrCreate(
            ['email' => 'frimkings@gmail.com'],
            [
                'name' => 'Dr. Kingsford',
                'password' => Hash::make('password'),
            
                'email_verified_at' => now(),
            ]
        );

        // 2: Cashier
        User::updateOrCreate(
            ['email' => 'secretary@gmail.com'],
            [
                'name' => 'Secretary',
                'password' => Hash::make('password'),
         
                'email_verified_at' => now(),
            ]
        );

        // 3: Staff / Receptionist
        User::updateOrCreate(
            ['email' => 'staff@eyeclinic.com'],
            [
                'name' => 'Front Desk Staff',
                'password' => Hash::make('staff123'),
              
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('User roles (0-3) seeded successfully!');
    }
}
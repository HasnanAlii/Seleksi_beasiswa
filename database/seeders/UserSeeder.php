<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Create role if not exists, with display name
        $role = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        // Optionally set a display name if your Role model supports it
        if (property_exists($role, 'display_name')) {
            $role->display_name = 'Administrator';
            $role->save();
        }
        if (! $admin->hasRole('admin')) {
            $admin->assignRole($role);
        }
    }
}

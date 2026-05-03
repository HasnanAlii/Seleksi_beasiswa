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
        // Buat daftar role jika belum ada
        $roles = ['admin', 'staf', 'kaprodi', 'wakil dekan 3', 'mahasiswa'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
        }

        // Data Users
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Kany Legiawan',
                'email' => 'kaprodi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'kaprodi',
            ],
            [
                'name' => 'Dodi',
                'email' => 'staf@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staf',
            ],
            [
                'name' => 'Tarmin',
                'email' => 'wd3@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'wakil dekan 3',
            ],
            [
                'name' => 'Hasnan',
                'email' => 'hasnan@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}

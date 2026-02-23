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
     * Creates: 1 admin + 20 dummy users
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@tablelink.com'],
            [
                'name' => 'Admin TableLink',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create 20 Dummy Users
        $users = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com'],
            ['name' => 'Citra Dewi', 'email' => 'citra@example.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@example.com'],
            ['name' => 'Eka Putri', 'email' => 'eka@example.com'],
            ['name' => 'Fajar Rahman', 'email' => 'fajar@example.com'],
            ['name' => 'Gita Lestari', 'email' => 'gita@example.com'],
            ['name' => 'Hadi Wijaya', 'email' => 'hadi@example.com'],
            ['name' => 'Indra Gunawan', 'email' => 'indra@example.com'],
            ['name' => 'Jasmine Ayu', 'email' => 'jasmine@example.com'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@example.com'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman@example.com'],
            ['name' => 'Mira Fatmawati', 'email' => 'mira@example.com'],
            ['name' => 'Nico Pratama', 'email' => 'nico@example.com'],
            ['name' => 'Olivia Natalia', 'email' => 'olivia@example.com'],
            ['name' => 'Putra Mahkota', 'email' => 'putra@example.com'],
            ['name' => 'Qori Amelia', 'email' => 'qori@example.com'],
            ['name' => 'Rina Susilowati', 'email' => 'rina@example.com'],
            ['name' => 'Sandi Pratama', 'email' => 'sandi@example.com'],
            ['name' => 'Tika Hartati', 'email' => 'tika@example.com'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'last_login' => now()->subDays(rand(0, 30)),
                ]
            );
        }

        $this->command->info('Seeded: 1 admin + 20 users');
    }
}

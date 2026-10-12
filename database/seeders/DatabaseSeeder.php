<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'rol' => 'Administrador',
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Test Client',
                'password' => 'password',
                'rol' => 'Cliente',
                'email_verified_at' => now(),
            ],
        );
    }
}

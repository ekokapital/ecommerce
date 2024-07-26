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

        User::factory()->create([
            'name' => 'Admin User',
            'admin' => 'admin@admin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now()
        ]);
        User::factory()->create([
            'name' => 'Welly YC',
            'admin' => 'welly@ekokapital.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now()
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory(32)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'brayan@gmail.com',
            'password' => bcrypt('password') ,
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'viewer',
            'email' => 'viewer@gmail.com',
            'password' => bcrypt('password') ,
        ])->assignRole('viewer');
    }
}

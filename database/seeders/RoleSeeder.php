<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access',
        ]);
        $viewer = Role::create([
            'name' => 'viewer',
            'description' => 'Vistante with read-only access',
        ]);

        Permission::create([
            'name' => 'app.users.index',
            'description' => 'Permission to view user list',
        ])->syncRoles($admin);

        Permission::create([
            'name' => 'profile',
            'description' => 'Permission to view and edit profile',
        ])->syncRoles($admin, $viewer);
    }
}

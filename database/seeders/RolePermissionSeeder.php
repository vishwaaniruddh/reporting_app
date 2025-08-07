<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $admin = Role::create(['name' => 'admin', 'description' => 'Administrator']);
        $manager = Role::create(['name' => 'manager', 'description' => 'Manager']);
        $user = Role::create(['name' => 'user', 'description' => 'Regular User']);

        // Create Permissions
        $permissions = [
            // User permissions
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'group' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'users'],
            
            // Add other permission groups as needed
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to admin
        $admin->permissions()->attach(Permission::all());

        // Assign view and create to manager
        $manager->permissions()->attach(Permission::whereIn('slug', [
            'users.view', 'users.create'
        ])->get());

        // Assign only view to user
        $user->permissions()->attach(Permission::where('slug', 'users.view')->first());
    }
}
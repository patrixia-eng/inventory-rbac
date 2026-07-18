<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        /* 1. Permissions ------------------------------------------------ */
        $permissions = [
            'can_create'       => 'Create inventory entries',
            'can_update'       => 'Update inventory entries',
            'can_view'         => 'View individual item details',
            'can_remove'       => 'Remove inventory entries',
            'can_print'        => 'Generate reports for printing',
            'can_manage_users' => 'Assign, change, or remove roles for users',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        /* 2. Roles and their permissions -------------------------------- */
        $roles = [
            'system-administrator' => [
                'name'        => 'System Administrator',
                'permissions' => ['can_manage_users'],
            ],
            'receiving-clerk' => [
                'name'        => 'Inventory Receiving Clerk',
                'permissions' => ['can_create', 'can_update', 'can_view'],
            ],
            'inventory-manager' => [
                'name'        => 'Inventory Manager',
                'permissions' => ['can_view'],
            ],
            'inventory-supervisor' => [
                'name'        => 'Inventory Supervisor',
                'permissions' => ['can_view', 'can_remove'],
            ],
            'reports-officer' => [
                'name'        => 'Inventory Reports Officer',
                'permissions' => ['can_print'],
            ],
        ];

        foreach ($roles as $slug => $definition) {
            $role = Role::firstOrCreate(['slug' => $slug], ['name' => $definition['name']]);

            $ids = Permission::whereIn('slug', $definition['permissions'])->pluck('id');
            $role->permissions()->sync($ids);
        }

        /* 3. Assign roles to the seeded demo users (multi-role via pivot) */
        $assignments = [
            'admin@inventory.test'      => ['system-administrator'],
            'clerk@inventory.test'      => ['receiving-clerk'],
            'manager@inventory.test'    => ['inventory-manager'],
            'supervisor@inventory.test' => ['inventory-supervisor'],
            'reports@inventory.test'    => ['reports-officer'],
        ];

        foreach ($assignments as $email => $roleSlugs) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $roleIds = Role::whereIn('slug', $roleSlugs)->pluck('id');
                $user->roles()->sync($roleIds);
            }
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Permissions by group
        $permissionsByGroup = [
            'products' => [
                'view_products' => 'View Products',
                'create_products' => 'Create Products',
                'edit_products' => 'Edit Products',
                'delete_products' => 'Delete Products',
            ],
            'categories' => [
                'view_categories' => 'View Categories',
                'create_categories' => 'Create Categories',
                'edit_categories' => 'Edit Categories',
                'delete_categories' => 'Delete Categories',
            ],
            'orders' => [
                'view_orders' => 'View Orders',
                'edit_orders' => 'Edit Orders',
                'delete_orders' => 'Delete Orders',
            ],
            'settings' => [
                'view_settings' => 'View Settings',
                'edit_settings' => 'Edit Settings',
            ],
            'banners' => [
                'view_banners' => 'View Banners',
                'edit_banners' => 'Edit Banners',
            ],
        ];

        $allPermissionIds = [];
        $managerPermissionIds = [];
        $staffPermissionIds = [];

        foreach ($permissionsByGroup as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                $permission = Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'group' => $group,
                        'description' => "Allows user to $name",
                    ]
                );

                $allPermissionIds[] = $permission->id;

                // Manager gets everything except delete operations
                if (!str_starts_with($slug, 'delete_')) {
                    $managerPermissionIds[] = $permission->id;
                }

                // Staff gets only view operations
                if (str_starts_with($slug, 'view_')) {
                    $staffPermissionIds[] = $permission->id;
                }
            }
        }

        // 2. Define Default System Roles (tenant_id = null, is_system = true)
        
        // Owner
        $ownerRole = Role::updateOrCreate(
            ['slug' => 'owner', 'tenant_id' => null],
            [
                'name' => 'Owner',
                'description' => 'Store Owner with full control.',
                'is_system' => true,
            ]
        );
        $ownerRole->permissions()->sync($allPermissionIds);

        // Manager
        $managerRole = Role::updateOrCreate(
            ['slug' => 'manager', 'tenant_id' => null],
            [
                'name' => 'Manager',
                'description' => 'Store Manager who can manage products, categories, orders, and banners.',
                'is_system' => true,
            ]
        );
        $managerRole->permissions()->sync($managerPermissionIds);

        // Staff
        $staffRole = Role::updateOrCreate(
            ['slug' => 'staff', 'tenant_id' => null],
            [
                'name' => 'Staff',
                'description' => 'Store Staff who can view store data and manage orders.',
                'is_system' => true,
            ]
        );
        $staffRole->permissions()->sync($staffPermissionIds);
    }
}

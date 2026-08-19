<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $arrayOfAdminPermissions = [
            'View Users',
            'Create Users',
            'Edit Users',
            'View Roles',
            'Create Roles',
            'Edit Roles',
            'View Countries',
            'Create Countries',
            'Edit Countries',
            'Delete Countries',
            'View Regions',
            'Create Regions',
            'Edit Regions',
            'Delete Regions',
        ];

        foreach ($arrayOfAdminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }

        $arrayOfVendorPermissions = [
            'View Company',
            'Edit Company',
            'View Branches',
            'Create Branches',
            'Edit Branches',
            'Delete Branches',
        ];

        foreach ($arrayOfVendorPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'vendor',
            ]);
        }
    }
}
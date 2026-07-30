<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;   
use Spatie\Permission\Models\Permission;  

class OrganizationRolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $guard = 'web';

        $permissions = [
            'upload photos',
            'view own photos',
            'view organization photos',
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'change employee status',
            'view organization activity',
            'manage organization settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => $guard]
            );
        }

        // create roles
        $owner    = Role::firstOrCreate(['name' => 'owner', 'guard_name' => $guard]);
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => $guard]);

        // owner - apni organization ke andar sab kuch kar sakta hai
        $owner->syncPermissions(
            Permission::where('guard_name', $guard)->get()
        );

        // employee - sirf apna kaam (photo upload + apni activity dekhna)
        $employee->syncPermissions(
            Permission::whereIn('name', [
                'upload photos',
                'view own photos',
            ])->where('guard_name', $guard)->get()
        );
    }
}

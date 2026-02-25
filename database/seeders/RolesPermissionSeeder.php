<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage users','guard_name' => 'admin']);
        Permission::create(['name' => 'manage photos','guard_name' => 'admin']);
        Permission::create(['name' => 'manage setting','guard_name' => 'admin']);
        Permission::create(['name' => 'create users','guard_name' => 'admin']);
        Permission::create(['name' => 'edit users','guard_name' => 'admin']);
        Permission::create(['name' => 'delete users','guard_name' => 'admin']);
        Permission::create(['name' => 'changes users status','guard_name' => 'admin']);
        Permission::create(['name' => 'changes photos status','guard_name' => 'admin']);
        Permission::create(['name' => 'view users','guard_name' => 'admin']);
        Permission::create(['name' => 'view photos','guard_name' => 'admin']);
        Permission::create(['name' => 'view activity','guard_name' => 'admin']);

        // create roles
        $superAdmin = Role::create(['name' => 'super-admin','guard_name' => 'admin']);
        $admin      = Role::create(['name' => 'admin','guard_name' => 'admin']);

        // assign all permissions to super admin
        $superAdmin->givePermissionTo(Permission::all());

        // assign specific permissions to admin
        $admin->givePermissionTo([
            'view users',
            'view photos',
            'view activity',
            'changes photos status'
        ]);

    }
}

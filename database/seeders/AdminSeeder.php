<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::firstOrCreate([
            'email' => 'sandeep@mailinator.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('123456'),
        ]);

        $admin->syncRoles(['super-admin']);
    }
}

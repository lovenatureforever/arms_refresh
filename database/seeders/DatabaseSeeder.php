<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles without permissions
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);

        User::firstOrCreate(
            [
                'email' => 'admin@auditapp.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => 1,
            ]
        )->assignRole('superadmin');
    }
}

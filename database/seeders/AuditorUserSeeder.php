<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuditorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist first
        $this->call(TenantRolePermissionSeeder::class);

        // Create auditor user
        $user = User::firstOrCreate(
            [
                'email' => 'auditor@auditapp.com',
            ],
            [
                'name' => 'Audit Partner',
                'password' => Hash::make('password'),
                'is_active' => 1,
            ]
        );

        // Assign the internal_admin role (or choose another role based on your requirements)
        // You can change this to any role that fits your needs:
        // - internal_admin (full access)
        // - internal_reviewer (review permissions)
        // - internal_executor (execution permissions)
        if (!$user->hasRole('internal_admin')) {
            $user->assignRole('internal_admin');
        }

        $this->command->info('Auditor user created successfully!');
        $this->command->info('Email: auditor@auditapp.com');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('To access the audit firm page:');
        $this->command->info('1. Login with the credentials above');
        $this->command->info('2. Navigate to: /audit-firm');
    }
}

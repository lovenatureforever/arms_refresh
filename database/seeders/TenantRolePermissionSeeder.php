<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenantRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Internal workflow roles
        $internalRoles = [
            'internal_admin',
            'internal_reviewer',
            'internal_executor',
            'internal_2nd_reviewer',
            'outsider_viewer',
            'outsider_client',
            'isqm_approver',
            'isqm_reviewer',
            'isqm_preparer'
        ];

        // COSEC user type roles
        $cosecRoles = [
            'cosec_admin',      // Full access to all COSEC features
            'cosec_subscriber', // Company Secretary - can manage their companies
            'cosec_director',   // Director - limited access to their company
        ];

        $allRoles = array_merge($internalRoles, $cosecRoles);

        foreach ($allRoles as $role) {
            Role::updateOrCreate(
                ['name' => $role]
            );
        }
    }
}

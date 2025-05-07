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
        $roles = [
            'internal_admin',
            'internal_reviewer',
            'internal_executor',
            'internal_2nd_reviewer',
            'outsider_viewer',
            'outsider_client',
            'isqm_approver',
            'isqm_reviewer',
            'isql_preparer'
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role]
            );
        }
    }
}

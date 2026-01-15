<?php

namespace App\Console\Commands;

use App\Models\Central\Tenant;
use App\Models\Tenant\CompanySecretary;
use Illuminate\Console\Command;

class CleanupOrphanSecretaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secretaries:cleanup {tenant? : The tenant ID to run for} {--dry-run : Show what would be deleted without actually deleting} {--force : Delete without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove orphan secretary records that have no corresponding change records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $isDryRun = $this->option('dry-run');

        // If no tenant specified, get all tenants
        if ($tenantId) {
            $tenants = Tenant::where('id', $tenantId)->get();
        } else {
            $tenants = Tenant::all();
        }

        if ($tenants->isEmpty()) {
            $this->error('No tenants found.');
            return 1;
        }

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->id}");

            tenancy()->initialize($tenant);

            // Find orphan secretaries (those without any change records)
            $orphanSecretaries = CompanySecretary::whereDoesntHave('changes')->get();

            if ($orphanSecretaries->isEmpty()) {
                $this->info('  No orphan secretary records found.');
                tenancy()->end();
                continue;
            }

            $this->info("  Found {$orphanSecretaries->count()} orphan secretary record(s):");
            $this->newLine();

            // Display table of orphan records
            $tableData = $orphanSecretaries->map(function ($secretary) {
                return [
                    'ID' => $secretary->id,
                    'Company ID' => $secretary->company_id,
                    'Name' => $secretary->name,
                    'Secretary No' => $secretary->secretary_no,
                    'Is Active' => $secretary->is_active ? 'Yes' : 'No',
                ];
            })->toArray();

            $this->table(['ID', 'Company ID', 'Name', 'Secretary No', 'Is Active'], $tableData);

            if ($isDryRun) {
                $this->newLine();
                $this->warn('  Dry run mode - no records were deleted.');
                tenancy()->end();
                continue;
            }

            // Confirm deletion (skip if --force)
            if (!$this->option('force') && !$this->confirm('  Do you want to delete these orphan records?')) {
                $this->info('  Operation cancelled.');
                tenancy()->end();
                continue;
            }

            // Delete orphan records
            $deletedCount = CompanySecretary::whereDoesntHave('changes')->delete();

            $this->newLine();
            $this->info("  Successfully deleted {$deletedCount} orphan secretary record(s).");

            tenancy()->end();
        }

        $this->newLine();
        $this->info('Done.');

        return 0;
    }
}

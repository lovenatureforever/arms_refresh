<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Stancl\Tenancy\Contracts\Tenant;

class CreateFrameworkDirectoriesForTenant implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->tenant->run(function ($tenant) {
            $storage_path = storage_path();

            // Create framework directories
            if (!is_dir("$storage_path/framework/cache")) {
                mkdir("$storage_path/framework/cache", 0777, true);
            }

            // Create Livewire temporary upload directory
            if (!is_dir("$storage_path/app/livewire-tmp")) {
                mkdir("$storage_path/app/livewire-tmp", 0777, true);
            }

            // Create public storage directories for signatures
            if (!is_dir("$storage_path/app/public/signatures")) {
                mkdir("$storage_path/app/public/signatures", 0777, true);
            }
        });
    }
}

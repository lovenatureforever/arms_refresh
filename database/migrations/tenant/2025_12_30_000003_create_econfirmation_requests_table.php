<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('econfirmation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Year-end snapshot
            $table->date('year_end_date')->comment('Company year-end date at time of request');
            $table->string('year_end_period', 10)->comment('e.g., 2026');

            // Token for director access
            $table->string('token', 64)->unique();
            $table->timestamp('token_expires_at');

            // Status tracking
            $table->enum('status', ['draft', 'pending_signatures', 'completed', 'expired'])->default('draft');

            // Progress counters
            $table->unsignedInteger('total_banks')->default(0);
            $table->unsignedInteger('banks_completed')->default(0);
            $table->unsignedInteger('total_signatures_required')->default(0);
            $table->unsignedInteger('total_signatures_collected')->default(0);

            // Timestamps
            $table->timestamp('sent_at')->nullable()->comment('When emails were sent to directors');
            $table->timestamp('completed_at')->nullable();

            // Settings
            $table->unsignedInteger('validity_days')->default(14);
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('token');
            $table->index('token_expires_at');
            $table->index(['status', 'token_expires_at']);
            $table->index('year_end_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('econfirmation_requests');
    }
};

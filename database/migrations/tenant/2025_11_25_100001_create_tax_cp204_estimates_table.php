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
        Schema::create('tax_cp204_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            // Basis Period Information
            $table->integer('basis_period_year');
            $table->date('basis_period_from');
            $table->date('basis_period_to');

            // Estimate Type
            $table->enum('estimate_type', ['cp204_initial', 'cp204a_revision']);
            $table->tinyInteger('revision_month')->unsigned()->nullable()->comment('6, 9, or 11 for CP204A');

            // Estimate Details
            $table->decimal('estimated_tax_amount', 15, 2);
            $table->decimal('monthly_installment', 15, 2);

            // Submission Tracking
            $table->enum('submission_status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->dateTime('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();

            // 85% Rule Compliance
            $table->foreignId('previous_estimate_id')->nullable()->constrained('tax_cp204_estimates')->nullOnDelete()->comment('Links to prior year estimate');
            $table->boolean('is_85_percent_compliant')->nullable();
            $table->text('compliance_notes')->nullable();

            // Metadata
            $table->text('remarks')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['company_id', 'basis_period_year'], 'idx_company_period');
            $table->index('submission_status', 'idx_submission_status');
            $table->index(['basis_period_from', 'basis_period_to'], 'idx_basis_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_cp204_estimates');
    }
};

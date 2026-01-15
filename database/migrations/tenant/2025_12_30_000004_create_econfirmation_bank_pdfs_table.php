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
        Schema::create('econfirmation_bank_pdfs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('econfirmation_request_id')->constrained('econfirmation_requests')->cascadeOnDelete();
            $table->foreignId('bank_branch_id')->constrained('bank_branches')->cascadeOnDelete();

            // PDF paths
            $table->string('unsigned_pdf_path', 500)->nullable();
            $table->string('signed_pdf_path', 500)->nullable();

            // Status tracking
            $table->enum('status', ['pending', 'partial', 'signed'])->default('pending');

            // Signature counters
            $table->unsignedInteger('signatures_required')->default(0);
            $table->unsignedInteger('signatures_collected')->default(0);

            // Version tracking
            $table->unsignedInteger('version')->default(1);

            $table->timestamps();

            $table->index(['econfirmation_request_id', 'status'], 'econf_bank_pdfs_request_status_idx');
            $table->unique(['econfirmation_request_id', 'bank_branch_id'], 'econf_bank_pdfs_request_branch_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('econfirmation_bank_pdfs');
    }
};

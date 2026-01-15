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
        Schema::create('econfirmation_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('econfirmation_bank_pdf_id')->constrained('econfirmation_bank_pdfs')->cascadeOnDelete();
            $table->foreignId('director_id')->constrained('company_directors')->cascadeOnDelete();

            // Signature tracking
            $table->enum('status', ['pending', 'signed', 'waived'])->default('pending');
            $table->string('signature_path_used', 500)->nullable()->comment('Snapshot of signature used');
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip', 45)->nullable();

            // Director snapshot
            $table->string('director_name')->comment('Director name at time of signing');

            $table->timestamps();

            $table->unique(['econfirmation_bank_pdf_id', 'director_id'], 'econf_sigs_pdf_director_unique');
            $table->index(['director_id', 'status'], 'econf_sigs_director_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('econfirmation_signatures');
    }
};

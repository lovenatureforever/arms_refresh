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
        Schema::create('econfirmation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('econfirmation_request_id')->constrained('econfirmation_requests')->cascadeOnDelete();

            // Log details
            $table->string('log_type', 50)->comment('email_sent, reminder_sent, signature_collected, pdf_generated, etc.');
            $table->foreignId('director_id')->nullable()->constrained('company_directors')->nullOnDelete();
            $table->string('recipient_email')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['econfirmation_request_id', 'log_type'], 'econf_logs_request_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('econfirmation_logs');
    }
};

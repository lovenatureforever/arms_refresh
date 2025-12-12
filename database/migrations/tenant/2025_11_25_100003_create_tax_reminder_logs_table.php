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
        Schema::create('tax_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_reminder_id')->constrained('tax_reminders')->cascadeOnDelete();

            // Log Details
            $table->enum('log_type', ['email_sent', 'email_failed', 'sms_sent', 'acknowledged', 'snoozed', 'dismissed']);
            $table->string('recipient_email')->nullable();
            $table->foreignId('recipient_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Notification Details
            $table->dateTime('sent_at')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();

            // User Actions
            $table->string('user_action', 100)->nullable()->comment('acknowledged, snoozed, dismissed');
            $table->dateTime('action_at')->nullable();
            $table->foreignId('action_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('action_notes')->nullable();

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamp('created_at')->nullable();

            // Indexes
            $table->index('tax_reminder_id', 'idx_reminder');
            $table->index('sent_at', 'idx_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_reminder_logs');
    }
};

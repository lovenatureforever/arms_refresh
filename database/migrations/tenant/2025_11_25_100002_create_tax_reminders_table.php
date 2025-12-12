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
        Schema::create('tax_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            // Reminder Identification
            $table->enum('reminder_category', ['cp204', 'cp204a', 'monthly_installment', 'form_c', 'other']);
            $table->string('reminder_type', 100)->comment('cp204_submission, cp204a_6th_month, monthly_payment_jan, etc.');

            // Associated Estimate
            $table->foreignId('tax_estimate_id')->nullable()->constrained('tax_cp204_estimates')->nullOnDelete();

            // Basis Period Reference
            $table->integer('basis_period_year');
            $table->date('basis_period_from');
            $table->date('basis_period_to');

            // Deadline Information
            $table->date('action_due_date')->comment('When the action should be completed');
            $table->date('reminder_trigger_date')->comment('When to start sending reminders');
            $table->date('final_reminder_date')->nullable()->comment('Last reminder before deadline');

            // Status Tracking
            $table->enum('status', ['pending', 'scheduled', 'sent', 'acknowledged', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete();

            // Notification Tracking
            $table->integer('notification_count')->unsigned()->default(0);
            $table->dateTime('last_notified_at')->nullable();
            $table->dateTime('next_notification_at')->nullable();

            // Recipients
            $table->foreignId('primary_recipient_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('primary_recipient_email')->nullable();
            $table->json('cc_recipients')->nullable()->comment('Array of additional recipients');

            // Message Content
            $table->string('reminder_title');
            $table->text('reminder_message')->nullable();
            $table->enum('reminder_priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            // Additional Data
            $table->json('metadata')->nullable()->comment('Store revision month, installment details, etc.');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['company_id', 'basis_period_year'], 'idx_company_year');
            $table->index('status', 'idx_status');
            $table->index('action_due_date', 'idx_due_date');
            $table->index('reminder_trigger_date', 'idx_reminder_trigger');
            $table->index('next_notification_at', 'idx_next_notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_reminders');
    }
};

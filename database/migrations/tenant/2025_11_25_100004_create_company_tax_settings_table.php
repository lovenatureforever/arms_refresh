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
        Schema::create('company_tax_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained('companies')->cascadeOnDelete();

            // CP204 Settings
            $table->boolean('enable_cp204_reminders')->default(true);
            $table->integer('cp204_submission_days_before')->default(30)->comment('Remind N days before deadline');
            $table->boolean('enable_cp204a_reminders')->default(true);

            // Installment Settings
            $table->boolean('enable_monthly_installment_reminders')->default(true);
            $table->integer('installment_reminder_days_before')->default(7);

            // Notification Preferences
            $table->foreignId('primary_tax_contact_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('primary_tax_contact_email')->nullable();
            $table->boolean('cc_company_directors')->default(true);
            $table->boolean('cc_auditors')->default(true);
            $table->json('additional_recipients')->nullable();

            // Reminder Frequency
            $table->integer('first_reminder_days_before')->default(30);
            $table->integer('second_reminder_days_before')->default(14);
            $table->integer('final_reminder_days_before')->default(3);

            // Custom Settings
            $table->json('custom_reminder_schedule')->nullable();
            $table->json('notification_channels')->nullable()->comment('email, sms, in-app');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_tax_settings');
    }
};

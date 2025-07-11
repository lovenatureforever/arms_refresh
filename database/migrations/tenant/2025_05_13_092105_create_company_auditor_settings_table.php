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
        Schema::create('company_auditor_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('auditor_id')->nullable()->constrained('auditors')->cascadeOnDelete();
            $table->foreignId('selected_firm_address_id')->nullable()->constrained('audit_firm_addresses')->nullOnDelete();
            $table->unsignedBigInteger('selected_auditor_license')->nullable();
            $table->boolean('audit_firm_changed')->default(false);
            $table->string('prior_audit_firm')->nullable();
            $table->date('prior_report_date')->nullable();
            $table->string('prior_report_opinion')->nullable();
            $table->boolean('with_breakline')->default(false);
            $table->string('audit_firm_description')->nullable();
            $table->boolean('is_default_letterhead')->default(true);
            $table->boolean('is_letterhead_repeat')->default(false);
            $table->float('blank_header_spacing')->nullable();
            $table->boolean('is_show_firm_name')->default(true);
            $table->boolean('is_show_firm_title')->default(true);
            $table->boolean('is_show_firm_license')->default(true);
            $table->boolean('is_show_firm_address')->default(true);
            $table->boolean('is_show_firm_contact')->default(true);
            $table->boolean('is_show_firm_email')->default(true);
            $table->boolean('is_show_firm_fax')->default(true);
            $table->boolean('is_firm_address_uppercase')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_auditor_settings');
    }
};

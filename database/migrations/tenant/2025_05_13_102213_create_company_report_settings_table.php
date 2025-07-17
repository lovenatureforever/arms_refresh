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
        Schema::create('company_report_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('officer_name')->nullable();
            $table->string('officer_id_type')->nullable();
            $table->string('officer_id')->nullable();
            $table->string('officer_mia_no')->nullable();
            $table->string('cover_sign_position')->nullable();
            $table->string('cover_sign_name')->nullable();
            $table->string('cover_signature_position')->nullable();
            $table->string('cover_sign_secretary_no')->nullable();
            $table->date('report_date')->nullable();
            $table->string('director_report_location')->nullable();
            $table->date('statement_date')->nullable();
            $table->string('statement_location')->nullable();
            $table->boolean('statement_as_report_date')->default(false);
            $table->date('statutory_date')->nullable();
            $table->string('statutory_location')->nullable();
            $table->boolean('statutory_as_report_date')->default(false);
            $table->date('auditor_report_date')->nullable();
            $table->string('auditor_report_location')->nullable();
            $table->boolean('auditor_report_as_report_date')->default(false);
            $table->date('circulation_date')->nullable();
            $table->string('declaration_country')->nullable();
            $table->string('foreign_act')->nullable();
            $table->string('declaration_commissioner')->nullable();
            $table->decimal('auditor_remuneration')->nullable();
            $table->boolean('is_declaration_officer')->default(false);
            $table->boolean('is_declaration_mia')->default(false);
            $table->foreignId('selected_director_id')->nullable()->constrained('company_directors')->nullOnDelete();
            $table->foreignId('selected_secretary_id')->nullable()->constrained('company_secretaries')->nullOnDelete();
            $table->boolean('is_approved_application')->default(false);
            $table->boolean('is_exempt')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_settings');
    }
};

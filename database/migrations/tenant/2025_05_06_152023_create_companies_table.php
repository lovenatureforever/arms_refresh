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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->nullable();
            $table->string('registration_no_old')->nullable();
            $table->boolean('current_is_first_year')->nullable();
            $table->string('current_year_type')->nullable();
            $table->string('current_report_header_format')->nullable();
            $table->year('current_year')->nullable();
            $table->date('current_year_from')->nullable();
            $table->date('current_year_to')->nullable();
            $table->boolean('last_is_first_year')->nullable();
            $table->string('last_year_type')->nullable();
            $table->string('last_report_header_format')->nullable();
            $table->year('last_year')->nullable();
            $table->date('last_year_from')->nullable();
            $table->date('last_year_to')->nullable();
            $table->decimal('audit_fee', 10, 2)->default(0);
            $table->boolean('no_business_address')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

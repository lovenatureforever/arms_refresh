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
        // Schema::create('company_report_soce_cols', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->nullable();
        //     $table->foreignId('company_report_id')->constrained()->cascadeOnDelete();
        //     $table->string('data_type')->default('text');
        //     $table->string('sort')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_soce_cols');
    }
};

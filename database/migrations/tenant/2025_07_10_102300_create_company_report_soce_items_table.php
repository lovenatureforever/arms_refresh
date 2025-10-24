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
        // Schema::create('company_report_soce_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('row_id')->constrained('company_report_soce_rows')->cascadeOnDelete();
        //     $table->foreignId('col_id')->constrained('company_report_soce_cols')->cascadeOnDelete();
        //     $table->foreignId('company_report_id')->constrained()->cascadeOnDelete();
        //     $table->string('value')->nullable(); // Store the value as a string for flexibility
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_soce_items');
    }
};

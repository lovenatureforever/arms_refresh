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
        Schema::create('company_detail_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            // $table->boolean('is_year_start')->default(false);
            $table->string('change_nature')->nullable();
            $table->string('name')->nullable();
            $table->string('company_type')->nullable();
            $table->string('presentation_currency')->nullable();
            $table->string('presentation_currency_code')->nullable();
            $table->string('functional_currency')->nullable();
            $table->string('functional_currency_code')->nullable();
            $table->string('domicile')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('remarks')->nullable();
            // $table->string('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_detail_changes');
    }
};

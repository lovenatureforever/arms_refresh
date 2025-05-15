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
        Schema::create('company_shareholder_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_shareholder_id')->constrained('company_shareholders')->cascadeOnDelete();
            // $table->boolean('is_year_start')->default(false);
            $table->string('change_nature')->nullable();
            $table->string('share_type')->nullable();
            $table->integer('shares')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_shareholder_changes');
    }
};

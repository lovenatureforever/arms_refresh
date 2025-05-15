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
        Schema::create('company_dividend_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->boolean('is_declared')->default(false);
            $table->date('declared_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('year_end')->nullable();
            $table->string('share_type')->nullable();
            $table->string('dividend_type')->nullable();
            $table->boolean('is_free_text')->default(false);
            $table->string('rate_unit')->nullable();
            $table->decimal('rate', 8, 2)->nullable();
            $table->decimal('amount', 8, 2)->nullable();
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
        Schema::dropIfExists('company_dividend_changes');
    }
};

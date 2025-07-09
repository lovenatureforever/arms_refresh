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
        Schema::create('company_charge_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            // $table->boolean('is_year_start')->default(false);
            $table->string('change_nature')->nullable();
            $table->foreignId('creating_charge_id')
                  ->nullable()
                  ->constrained('company_charge_changes')
                  ->nullOnDelete();
            $table->string('registered_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('charge_nature')->nullable();
            $table->string('chargee_name')->nullable();
            $table->decimal('indebtedness_amount', 8, 2)->nullable();
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
        Schema::dropIfExists('company_charge_changes');
    }
};

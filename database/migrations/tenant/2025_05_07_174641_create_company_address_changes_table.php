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
        Schema::create('company_address_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            // $table->boolean('is_year_start')->default(false);
            $table->string('change_nature')->nullable();
            $table->string('country')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('postcode')->nullable();
            $table->string('town')->nullable();
            $table->string('state')->nullable();
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
        Schema::dropIfExists('company_address_changes');
    }
};

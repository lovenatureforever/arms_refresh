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
        Schema::create('company_shareholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('company_director_id')
                  ->nullable()
                  ->constrained('company_directors')
                  ->cascadeOnDelete();
            // $table->boolean('is_year_start')->default(false);
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_no')->nullable();
            $table->string('company_domicile')->nullable();
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
        Schema::dropIfExists('company_shareholders');
    }
};

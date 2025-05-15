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
        Schema::create('company_directors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('alternate_id')
                  ->nullable()
                  ->constrained('company_directors')
                  ->nullOnDelete();
            // $table->boolean('is_year_start')->default(false);
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_no')->nullable();
            $table->boolean('gender')->nullable();
            // $table->boolean('set_to_report')->default(false);
            // $table->boolean('is_rep_statutory')->default(false);
            // $table->boolean('is_rep_statement')->default(false);
            // $table->boolean('is_cover_page')->default(false);
            // $table->string('cover_page_title')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
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
        Schema::dropIfExists('company_directors');
    }
};

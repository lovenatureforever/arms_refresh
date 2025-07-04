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
        Schema::create('company_director_company_shareholder', function (Blueprint $table) {
            $table->unsignedBigInteger('company_director_id');
            $table->unsignedBigInteger('company_shareholder_id');
            $table->primary(['company_director_id', 'company_shareholder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('director_shareholder');
    }
};

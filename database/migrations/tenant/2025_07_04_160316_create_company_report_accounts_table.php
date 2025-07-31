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
        Schema::create('company_report_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_report_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('display')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_accounts');
    }
};

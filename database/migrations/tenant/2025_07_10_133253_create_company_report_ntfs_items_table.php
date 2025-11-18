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
        Schema::create('company_report_ntfs_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('col_1')->nullable();
            $table->decimal('col_2')->nullable();
            $table->decimal('col_3')->nullable();
            $table->decimal('col_4')->nullable();
            $table->foreignId('company_report_ntfs_section_id')->constrained();
            // $table->foreignId('company_report_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_ntfs_items');
    }
};

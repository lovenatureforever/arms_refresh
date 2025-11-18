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
        Schema::create('director_report_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->text('report_content')->nullable();
            $table->integer('position')->nullable();
            $table->string('template_type')->nullable();
            $table->boolean('display')->default(true);
            $table->boolean('page_break')->default(false);
            $table->boolean('is_deleteable')->default(false);
            $table->integer('order_no')->default(0);
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
        Schema::dropIfExists('director_report_configs');
    }
};

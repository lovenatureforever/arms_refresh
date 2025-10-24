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
        // Schema::create('report_configs', function (Blueprint $table) {
        //     $table->id();
        //     $table->text('report_content')->nullable();
        //     $table->integer('position')->nullable();
        //     $table->string('template_type')->nullable();
        //     $table->boolean('display')->default(true);
        //     $table->boolean('page_break')->default(false);
        //     $table->string('remarks')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_configs');
    }
};

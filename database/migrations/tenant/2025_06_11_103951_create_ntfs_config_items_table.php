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
        Schema::create('ntfs_config_items', function (Blueprint $table) {
            $table->id();
            $table->string('default_title')->nullable();
            $table->text('default_content')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('type')->nullable();
            $table->string('section')->nullable();
            $table->string('position')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default_title')->default(true);
            $table->boolean('is_default_content')->default(true);
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ntfs_config_items');
    }
};

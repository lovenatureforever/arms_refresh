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
        Schema::create('company_mbrs_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('dividend_disclosure_type')->nullable();
            $table->string('biz_status')->nullable();
            $table->string('biz_1')->nullable();
            $table->string('biz_1_code')->nullable();
            $table->string('biz_2')->nullable();
            $table->string('biz_2_code')->nullable();
            $table->string('biz_3')->nullable();
            $table->string('biz_3_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_mbrs_settings');
    }
};

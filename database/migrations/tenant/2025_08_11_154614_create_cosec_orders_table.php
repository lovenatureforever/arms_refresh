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
        Schema::create('cosec_orders', function (Blueprint $table) {
            $table->id();
            // $table->string('tenant_id');
            $table->integer('tenant_company_id')->nullable();
            $table->integer('tenant_user_id')->nullable();
            $table->string('firm')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_no')->nullable();
            $table->string('company_old_no')->nullable();
            $table->string('user')->nullable();
            $table->string('uuid')->nullable();
            $table->integer('form_type')->nullable();
            $table->string('form_name')->nullable();
            $table->datetime('requested_at')->nullable();
            $table->json('data')->nullable();
            $table->integer('cost')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosec_orders');
    }
};

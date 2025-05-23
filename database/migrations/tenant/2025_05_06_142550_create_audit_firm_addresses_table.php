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
        Schema::create('audit_firm_addresses', function (Blueprint $table) {
            $table->id();
            // $table->uuid('tenant_id');
            $table->string('branch')->nullable();
            $table->string('country')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('postcode')->nullable();
            $table->string('town')->nullable();
            $table->string('state')->nullable();
            $table->string('mbrs_state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_firm_addresses');
    }
};

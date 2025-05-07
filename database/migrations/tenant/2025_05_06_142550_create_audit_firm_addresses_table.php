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
            $table->string('firm_branch');
            $table->text('firm_address1');
            $table->text('firm_address2')->nullable();
            $table->string('firm_postcode');
            $table->string('firm_city');
            $table->string('firm_state');
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

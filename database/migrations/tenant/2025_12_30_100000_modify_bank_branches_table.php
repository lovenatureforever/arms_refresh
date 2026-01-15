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
        Schema::table('bank_branches', function (Blueprint $table) {
            // Add street_2 and street_3 after street
            $table->string('street_2')->nullable()->after('street');
            $table->string('street_3')->nullable()->after('street_2');

            // Remove contact fields
            $table->dropColumn(['contact_person', 'contact_phone', 'contact_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_branches', function (Blueprint $table) {
            // Re-add contact fields
            $table->string('contact_person')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_email')->nullable();

            // Remove street_2 and street_3
            $table->dropColumn(['street_2', 'street_3']);
        });
    }
};

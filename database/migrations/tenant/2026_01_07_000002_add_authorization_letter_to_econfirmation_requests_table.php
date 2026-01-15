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
        Schema::table('econfirmation_requests', function (Blueprint $table) {
            $table->string('authorization_letter_path')->nullable()->after('approved_by');
            $table->boolean('data_consent')->default(false)->after('authorization_letter_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('econfirmation_requests', function (Blueprint $table) {
            $table->dropColumn(['authorization_letter_path', 'data_consent']);
        });
    }
};

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
            $table->string('account_no', 100)->nullable()->after('validity_days');
            $table->string('charge_code', 100)->nullable()->after('account_no');

            $table->index('account_no', 'econf_requests_account_no_idx');
            $table->index('charge_code', 'econf_requests_charge_code_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('econfirmation_requests', function (Blueprint $table) {
            $table->dropIndex('econf_requests_account_no_idx');
            $table->dropIndex('econf_requests_charge_code_idx');
            $table->dropColumn(['account_no', 'charge_code']);
        });
    }
};

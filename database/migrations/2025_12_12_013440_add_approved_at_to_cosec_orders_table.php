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
        if (!Schema::hasColumn('cosec_orders', 'approved_at')) {
            Schema::table('cosec_orders', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cosec_orders', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};

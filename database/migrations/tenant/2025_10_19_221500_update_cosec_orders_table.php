<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cosec_orders', function (Blueprint $table) {
            $table->longText('document_content')->nullable()->after('data');
            $table->integer('custom_credit_cost')->nullable()->after('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cosec_orders', function (Blueprint $table) {
            $table->dropColumn(['document_content', 'custom_credit_cost']);
        });
    }
};

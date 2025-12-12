<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cosec_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('cosec_orders', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable()->after('form_name');
                $table->foreign('template_id')->references('id')->on('cosec_templates');
            }
            if (!Schema::hasColumn('cosec_orders', 'signature_status')) {
                $table->enum('signature_status', ['not_required', 'pending', 'partial', 'complete'])->default('not_required')->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('cosec_orders', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['template_id', 'signature_status']);
        });
    }
};
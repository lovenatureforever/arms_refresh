<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cosec_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('cosec_templates', 'template_file')) {
                $table->string('template_file')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cosec_templates', function (Blueprint $table) {
            $table->dropColumn('template_file');
        });
    }
};
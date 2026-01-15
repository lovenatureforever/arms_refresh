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
        Schema::table('users', function (Blueprint $table) {
            $table->string('signature_path')->nullable()->after('user_type');
            $table->string('license_no')->nullable()->after('signature_path')->comment('For secretary users');
            $table->string('ssm_no')->nullable()->after('license_no')->comment('For secretary users');
            $table->string('secretary_no')->nullable()->after('ssm_no')->comment('For secretary users');
            $table->string('secretary_company_name')->nullable()->after('secretary_no')->comment('For secretary users');
            $table->text('secretary_address')->nullable()->after('secretary_company_name')->comment('For secretary users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'signature_path',
                'license_no',
                'ssm_no',
                'secretary_no',
                'secretary_company_name',
                'secretary_address',
            ]);
        });
    }
};

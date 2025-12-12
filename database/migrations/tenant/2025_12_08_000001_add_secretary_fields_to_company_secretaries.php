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
        Schema::table('company_secretaries', function (Blueprint $table) {
            if (!Schema::hasColumn('company_secretaries', 'license_no')) {
                $table->string('license_no')->nullable()->after('secretary_no');
            }
            if (!Schema::hasColumn('company_secretaries', 'ssm_no')) {
                $table->string('ssm_no')->nullable()->after('license_no');
            }
            if (!Schema::hasColumn('company_secretaries', 'email')) {
                $table->string('email')->nullable()->after('ssm_no');
            }
            if (!Schema::hasColumn('company_secretaries', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('company_secretaries', 'company_name')) {
                $table->string('company_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('company_secretaries', 'address')) {
                $table->text('address')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('company_secretaries', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_secretaries', function (Blueprint $table) {
            $table->dropColumn(['license_no', 'ssm_no', 'email', 'phone', 'company_name', 'address', 'signature_path']);
        });
    }
};

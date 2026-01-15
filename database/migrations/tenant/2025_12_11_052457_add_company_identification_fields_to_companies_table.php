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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_group')->nullable()->after('registration_no_old');
            $table->string('tax_file_no')->nullable()->after('company_group');
            $table->string('employer_file_no')->nullable()->after('tax_file_no');
            $table->string('sst_registration_no')->nullable()->after('employer_file_no');
            $table->date('year_end')->nullable()->after('sst_registration_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['company_group', 'tax_file_no', 'employer_file_no', 'sst_registration_no', 'year_end']);
        });
    }
};

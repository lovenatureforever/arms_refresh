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
        Schema::create('audit_firm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('firm_description')->nullable();
            $table->unsignedBigInteger('selected_firm_address')->nullable();
            $table->boolean('with_breakline')->default(false);
            $table->string('audit_firm_description')->nullable();
            $table->boolean('is_default_letterhead')->default(false);
            $table->boolean('is_custom_letterhead')->default(false);
            $table->boolean('is_letterhead_repeat')->default(false);
            $table->string('blank_header_spacing')->nullable();
            $table->boolean('is_show_firm_name')->default(false);
            $table->boolean('is_show_firm_title')->default(false);
            $table->boolean('is_show_firm_license')->default(false);
            $table->boolean('is_show_firm_address')->default(false);
            $table->boolean('is_show_firm_contact')->default(false);
            $table->boolean('is_show_firm_fax')->default(false);
            $table->boolean('is_show_firm_email')->default(false);
            $table->boolean('is_firm_address_uppercase')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_firm_settings');
    }
};

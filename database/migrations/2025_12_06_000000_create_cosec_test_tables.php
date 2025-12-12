<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for COSEC test tables
 * This migration creates the necessary tables for testing the COSEC module
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Companies table
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('registration_no')->nullable();
                $table->string('registration_no_old')->nullable();
                $table->text('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->date('incorporation_date')->nullable();
                $table->boolean('is_active')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // Company Secretaries table
        if (!Schema::hasTable('company_secretaries')) {
            Schema::create('company_secretaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('license_no')->nullable();
                $table->string('ssm_no')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // Company Directors table
        if (!Schema::hasTable('company_directors')) {
            Schema::create('company_directors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('name');
                $table->string('ic_no')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('designation')->nullable();
                $table->date('appointment_date')->nullable();
                $table->date('resignation_date')->nullable();
                $table->boolean('is_default_signatory')->default(0);
                $table->boolean('is_active')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // Director Signatures table
        if (!Schema::hasTable('director_signatures')) {
            Schema::create('director_signatures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('director_id')->constrained('company_directors')->onDelete('cascade');
                $table->string('signature_path');
                $table->boolean('is_default')->default(0);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // COSEC Templates table
        if (!Schema::hasTable('cosec_templates')) {
            Schema::create('cosec_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('form_type')->nullable();
                $table->string('template_path')->nullable();
                $table->longText('content')->nullable();
                $table->string('template_file')->nullable();
                $table->string('signature_type')->default('default');
                $table->foreignId('default_signatory_id')->nullable();
                $table->integer('credit_cost')->default(0);
                $table->boolean('is_active')->default(1);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // COSEC Orders table
        if (!Schema::hasTable('cosec_orders')) {
            Schema::create('cosec_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_company_id')->nullable();
                $table->string('company_name')->nullable();
                $table->string('company_no')->nullable();
                $table->string('company_old_no')->nullable();
                $table->foreignId('template_id')->nullable();
                $table->foreignId('tenant_user_id')->nullable();
                $table->string('user')->nullable();
                $table->uuid('uuid')->nullable();
                $table->string('form_type')->nullable();
                $table->string('form_name')->nullable();
                $table->json('data')->nullable();
                $table->longText('document_content')->nullable();
                $table->integer('cost')->default(0);
                $table->integer('custom_credit_cost')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->string('signature_status')->default('not_required');
                $table->timestamp('requested_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // COSEC Order Signatures table
        if (!Schema::hasTable('cosec_order_signatures')) {
            Schema::create('cosec_order_signatures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cosec_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('director_id')->nullable();
                $table->string('signature_status')->default('pending');
                $table->timestamp('signed_at')->nullable();
                $table->timestamps();
            });
        }

        // Credit Transactions table
        if (!Schema::hasTable('credit_transactions')) {
            Schema::create('credit_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type'); // credit or debit
                $table->integer('amount');
                $table->integer('balance_before')->nullable();
                $table->integer('balance_after')->nullable();
                $table->text('description')->nullable();
                $table->string('reference_type')->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->foreignId('performed_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
        Schema::dropIfExists('cosec_order_signatures');
        Schema::dropIfExists('cosec_orders');
        Schema::dropIfExists('cosec_templates');
        Schema::dropIfExists('director_signatures');
        Schema::dropIfExists('company_directors');
        Schema::dropIfExists('company_secretaries');
        Schema::dropIfExists('companies');
    }
};

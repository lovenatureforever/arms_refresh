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
        Schema::create('company_share_capital_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            // $table->boolean('is_year_start')->default(false);
            $table->string('share_type')->nullable();
            $table->string('allotment_type')->nullable();
            $table->string('issuance_term')->nullable();
            $table->string('issuance_term_freetext')->nullable();
            $table->string('issuance_purpose')->nullable();
            $table->decimal('fully_paid_shares')->default(0);
            $table->decimal('fully_paid_amount', 8, 2);
            $table->decimal('partially_paid_shares')->default(0);
            $table->decimal('partially_paid_amount', 8, 2)->default(0);

            $table->date('effective_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_share_capital_changes');
    }
};

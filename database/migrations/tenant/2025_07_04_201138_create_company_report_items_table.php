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
        // Schema::create('company_report_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('company_report_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('company_report_type_id')->nullable()->constrained()->cascadeOnDelete();
        //     $table->foreignId('company_report_account_id')->nullable()->constrained()->onDelete('cascade');
        //     $table->string('item')->nullable();
        //     $table->string('display')->nullable();
        //     $table->decimal('this_year_amount', 8, 2)->nullable();
        //     $table->decimal('last_year_amount', 8, 2)->nullable();
        //     $table->enum('type', ['value', 'total', 'label', 'group', 'grandtotal'])->default('value');
        //     $table->string('sort')->nullable();
        //     $table->boolean('is_report')->default(false);
        //     $table->boolean('show_display')->default(false);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_report_items');
    }
};

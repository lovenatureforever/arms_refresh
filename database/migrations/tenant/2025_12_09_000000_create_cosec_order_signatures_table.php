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
        Schema::create('cosec_order_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cosec_order_id')->constrained('cosec_orders')->onDelete('cascade');
            $table->foreignId('director_id')->constrained('company_directors')->onDelete('cascade');
            $table->string('signature_status')->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosec_order_signatures');
    }
};

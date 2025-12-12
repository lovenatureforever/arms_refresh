<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['credit', 'debit']); // credit = add, debit = subtract
            $table->integer('amount');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('description');
            $table->string('reference_type')->nullable(); // e.g., 'cosec_order', 'admin_adjustment', 'top_up'
            $table->unsignedBigInteger('reference_id')->nullable(); // e.g., order_id
            $table->unsignedBigInteger('performed_by')->nullable(); // admin who made the change
            $table->timestamps();

            $table->index('user_id');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_transactions');
    }
};

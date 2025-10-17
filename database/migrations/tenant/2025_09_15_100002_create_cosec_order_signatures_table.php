<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cosec_order_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cosec_order_id');
            $table->unsignedBigInteger('director_id');
            $table->enum('signature_status', ['pending', 'signed', 'rejected'])->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            $table->foreign('cosec_order_id')->references('id')->on('cosec_orders');
            $table->foreign('director_id')->references('id')->on('company_directors');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cosec_order_signatures');
    }
};
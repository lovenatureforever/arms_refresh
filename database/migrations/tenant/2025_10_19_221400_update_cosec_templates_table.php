<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cosec_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('form_type')->nullable();
            $table->string('signature_type')->nullable();
            $table->longText('content')->nullable();
            $table->integer('credit_cost')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cosec_templates');
    }
};

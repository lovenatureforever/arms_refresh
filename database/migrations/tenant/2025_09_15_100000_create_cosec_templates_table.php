<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cosec_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('form_type')->nullable();
            $table->string('template_path')->nullable();
            $table->enum('signature_type', ['default', 'all_directors'])->default('default');
            $table->unsignedBigInteger('default_signatory_id')->nullable();
            $table->integer('credit_cost')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('default_signatory_id')->references('id')->on('company_directors');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cosec_templates');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('director_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('director_id');
            $table->string('signature_path')->nullable();
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('director_id')->references('id')->on('company_directors');
            $table->foreign('uploaded_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('director_signatures');
    }
};
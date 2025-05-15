<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nivel_js', function (Blueprint $table) {
            $table->id();
            $table->text('pregunta');
            $table->text('resp_correcta');
            $table->text('resp_usuario')->nullable();
            $table->string('language')->default('js');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nivel_js');
    }
};
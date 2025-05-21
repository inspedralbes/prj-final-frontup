<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nivells_usuaris', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('initial_html');
            $table->text('initial_css');
            $table->text('initial_js');
            $table->text('expected_html')->nullable();
            $table->text('expected_css')->nullable();
            $table->text('expected_js')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nivells_usuaris');
    }
};
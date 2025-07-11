<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContattiAccessiTable extends Migration
{
    public function up()
    {
        Schema::create('contattiAccessi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idContatto');
            $table->tinyInteger('autenticato')->default(0);
            $table->string('ip', 15)->nullable();
            $table->timestamps();

            $table->foreign('idContatto')->references('idContatto')->on('contatti')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contattiAccessi');
    }
}

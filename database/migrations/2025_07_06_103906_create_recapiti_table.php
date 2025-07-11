<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecapitiTable extends Migration
{
    public function up()
    {
        Schema::create('recapiti', function (Blueprint $table) {
            $table->id('idRecapito');
            $table->unsignedBigInteger('idContatto');
            // aggiungi altri campi necessari qui
            $table->timestamps();

            $table->foreign('idContatto')->references('idContatto')->on('contatti')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recapiti');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContattiPasswordTable extends Migration
{
    public function up()
    {
        Schema::create('contattiPassword', function (Blueprint $table) {
            $table->bigIncrements('idContattoPassword');
            $table->unsignedBigInteger('idContatto');
            $table->string('psw', 255);
            $table->string('sale', 255);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('idContatto')->references('idContatto')->on('contatti')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contattiPassword');
    }
}

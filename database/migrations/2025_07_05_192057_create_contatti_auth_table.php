<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContattiAuthTable extends Migration
{
    public function up()
    {
        Schema::create('contattiAuth', function (Blueprint $table) {
            $table->bigIncrements('idContattoAuth');
            $table->unsignedBigInteger('idContatto');
            $table->string('user', 255);
            $table->string('sfida', 255)->nullable();
            $table->string('secretJWT', 255)->nullable();
            $table->integer('inizioSfida')->nullable();
            $table->tinyInteger('obbligoCambio')->default(0);
            $table->timestamps();

            $table->foreign('idContatto')->references('idContatto')->on('contatti')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contattiAuth');
    }
}

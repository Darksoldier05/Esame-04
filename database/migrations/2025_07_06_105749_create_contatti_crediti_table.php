<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContattiCreditiTable extends Migration
{
    public function up()
    {
        Schema::create('contattiCrediti', function (Blueprint $table) {
            $table->id('idCredito');
            $table->unsignedBigInteger('idContatto');
            $table->decimal('credito', 10, 2)->default(0.00); // esempio campo credito
            $table->timestamps();

            $table->foreign('idContatto')
                  ->references('idContatto')
                  ->on('contatti')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contattiCrediti');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndirizziTable extends Migration
{
    public function up()
    {
        Schema::create('indirizzi', function (Blueprint $table) {
            $table->id(); // chiave primaria autoincrementale
            $table->unsignedBigInteger('idContatto'); // chiave esterna verso contatti

            $table->string('via', 128);
            $table->string('citta', 64);
            $table->string('cap', 10);
            $table->string('provincia', 32);
            $table->string('nazione', 64)->default('Italia')->nullable();
            $table->string('note', 255)->nullable();

            $table->timestamps();

            $table->foreign('idContatto')
                ->references('idContatto')
                ->on('contatti')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('indirizzi');
    }
}

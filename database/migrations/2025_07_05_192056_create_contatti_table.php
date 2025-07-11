<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContattiTable extends Migration
{
    public function up()
    {
        Schema::create('contatti', function (Blueprint $table) {
            $table->bigIncrements('idContatto');
            $table->unsignedBigInteger('idContattoStato')->default(1);
            $table->string('nome', 45);
            $table->string('cognome', 45);
            $table->tinyInteger('sesso')->nullable();
            $table->string('codiceFiscale', 20)->nullable();
            $table->string('partitaIva', 20)->nullable();
            $table->string('cittadinanza', 45)->nullable();
            $table->unsignedBigInteger('idNazioneNascita')->nullable();
            $table->string('cittaNascita', 45)->nullable();
            $table->string('provinciaNascita', 45)->nullable();
            $table->date('dataNascita')->nullable();
            $table->boolean('archiviato')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contatti');
    }
}

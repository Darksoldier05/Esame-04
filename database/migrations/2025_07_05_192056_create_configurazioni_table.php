<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurazioniTable extends Migration
{
    public function up()
    {
        Schema::create('configurazioni', function (Blueprint $table) {
            $table->bigIncrements('idConfigurazione');
            $table->string('chiave', 64)->unique();
            $table->string('valore', 255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('configurazioni');
    }
}

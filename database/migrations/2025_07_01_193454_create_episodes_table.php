<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('episode_number');
            $table->integer('season_number')->nullable();
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}

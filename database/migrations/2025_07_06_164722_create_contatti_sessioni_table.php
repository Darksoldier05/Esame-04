<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contattiSessioni', function (Blueprint $table) {
            $table->id('idContattoSessione');
            $table->unsignedBigInteger('idContatto');
            $table->string('token', 512)->nullable();
            $table->unsignedBigInteger('inizioSessione')->nullable(); // timestamp UNIX
            $table->timestamps();

            $table->foreign('idContatto')->references('idContatto')->on('contatti')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contattiSessioni');
    }
};

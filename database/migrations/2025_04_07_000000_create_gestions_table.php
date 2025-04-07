<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gestions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->unsignedBigInteger('tour_id')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->string('servicio_t')->nullable();
            $table->unsignedBigInteger('guia_id')->nullable();
            $table->string('guia_t')->nullable();
            $table->unsignedBigInteger('traductor_id')->nullable();
            $table->string('traductor_t')->nullable();
            $table->unsignedBigInteger('cocinero_id')->nullable();
            $table->string('cocinero_t')->nullable();
            $table->unsignedBigInteger('chofer_id')->nullable();
            $table->string('chofer_t')->nullable();
            $table->unsignedBigInteger('vagoneta_id')->nullable();
            $table->unsignedBigInteger('provag_id')->nullable();
            $table->string('vagoneta_t')->nullable();
            $table->unsignedBigInteger('caballo_id')->nullable();
            $table->unsignedBigInteger('procab_id')->nullable();
            $table->string('caballo_t')->nullable();
            $table->unsignedBigInteger('bicicleta_id')->nullable();
            $table->unsignedBigInteger('probic_id')->nullable();
            $table->string('bicicleta_t')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->tinyInteger('estatus')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestions');
    }
};

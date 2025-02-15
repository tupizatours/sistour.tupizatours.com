<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gestions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();

            $table->unsignedBigInteger('reserva_id')->unsigned()->index();
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');

            $table->unsignedBigInteger('tour_id')->unsigned()->index();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

            $table->string('servicio_id')->nullable();
            $table->decimal('servicio_t', 8, 2)->nullable();
            $table->string('guia_id')->nullable();
            $table->decimal('guia_t', 8, 2)->nullable();
            $table->string('traductor_id')->nullable();
            $table->decimal('traductor_t', 8, 2)->nullable();
            $table->string('cocinero_id')->nullable();
            $table->decimal('cocinero_t', 8, 2)->nullable();
            $table->string('chofer_id')->nullable();
            $table->decimal('chofer_t', 8, 2)->nullable();
            $table->string('vagoneta_id')->nullable();
            $table->string('provag_id')->nullable();
            $table->decimal('vagoneta_t', 8, 2)->nullable();
            $table->string('caballo_id')->nullable();
            $table->string('procab_id')->nullable();
            $table->decimal('caballo_t', 8, 2)->nullable();
            $table->string('bicicleta_id')->nullable();
            $table->string('probic_id')->nullable();
            $table->decimal('bicicleta_t', 8, 2)->nullable();
            $table->string('estado')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestions');
    }
};

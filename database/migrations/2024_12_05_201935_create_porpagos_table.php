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
        Schema::create('porpagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id')->nullable(); // Relacionar con otra tabla si aplica
            $table->unsignedBigInteger('tour_id')->nullable();
            $table->json('vagonetas')->nullable(); // Guardar como JSON
            $table->json('caballos')->nullable();
            $table->json('bicicletas')->nullable();
            $table->json('tickets')->nullable();
            $table->json('anticipoActual')->nullable();
            $table->json('subtotal')->nullable();
            $table->json('prestatario')->nullable();
            $table->json('anticipoAnterior')->nullable();
            $table->json('saldo')->nullable();
            $table->json('dserv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porpagos');
    }
};

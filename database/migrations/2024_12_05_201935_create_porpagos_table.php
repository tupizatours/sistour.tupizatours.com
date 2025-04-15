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
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('tour_id');
            $table->string('tipo_servicio'); // Ej: 'guia', 'caballo'
            $table->unsignedBigInteger('servicio_id')->nullable(); // ID del guía, traductor, prestatario...
            $table->unsignedBigInteger('pres_serv_id')->nullable(); // ID del elemento (caballo, vagoneta...)
            $table->unsignedBigInteger('anticipo_id')->nullable(); // FK a anticipos
            $table->decimal('costo', 10, 2)->default(0);
            $table->boolean('es_prestatario')->default(false); // <--- Campo agregado
            $table->string('estado')->default('pendiente');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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

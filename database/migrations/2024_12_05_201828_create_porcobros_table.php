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
        Schema::create('porcobros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id')->nullable(); // Relacionar con otra tabla si aplica
            $table->unsignedBigInteger('tour_id')->nullable();
            $table->json('servicios')->nullable(); // Guardar como JSON
            $table->json('guias')->nullable();
            $table->json('traductors')->nullable();
            $table->json('cocineros')->nullable();
            $table->json('chofers')->nullable();
            $table->json('tickets')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porcobros');
    }
};

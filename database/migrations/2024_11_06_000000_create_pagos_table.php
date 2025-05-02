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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();

            $table->unsignedBigInteger('reserva_id')->unsigned()->index();
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');

            $table->unsignedBigInteger('rescli_id')->unsigned()->index();
            $table->foreign('rescli_id')->references('id')->on('reserclientes')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->decimal('monto', 8, 2)->nullable();
            $table->decimal('diferencia', 8, 2)->nullable();
            $table->string('metodo')->nullable();
            $table->decimal('conversion', 8, 2)->nullable();
            $table->decimal('comision', 8, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

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
        Schema::create('reserclientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->decimal('pre_per', 8, 2)->nullable();
            $table->decimal('subtotal', 8, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();
            $table->decimal('pagado', 8, 2)->nullable();

            $table->unsignedBigInteger('reserva_id')->unsigned()->index();
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');

            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('edad')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('documento')->nullable();
            $table->string('celular')->nullable();
            $table->string('sexo')->nullable();
            $table->string('correo')->nullable();
            $table->text('alergias')->nullable();
            $table->text('alimentacion')->nullable();
            $table->string('nota')->nullable();
            $table->text('file')->nullable();
            
            $table->json('tickets')->nullable();
            $table->json('habitaciones')->nullable();
            $table->json('accesorios')->nullable();
            $table->json('servicios')->nullable();
            $table->string('estado')->nullable();
            $table->string('estatus')->nullable();
            $table->string('esPrincipal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserclientes');
    }
};

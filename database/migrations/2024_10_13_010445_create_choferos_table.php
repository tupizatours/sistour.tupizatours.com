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
        Schema::create('choferes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('cedula')->nullable();
            $table->string('licencia')->nullable();
            $table->string('numero')->nullable();
            $table->string('correo')->nullable();
            $table->string('celular')->nullable();
            $table->string('cuenta')->nullable();

            $table->unsignedBigInteger('bancos_id')->unsigned()->index();
            $table->foreign('bancos_id')->references('id')->on('bancos')->onDelete('cascade');

            $table->string('referencia')->nullable();
            $table->string('celref')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('file')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choferes');
    }
};

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
        Schema::create('caballos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('propietario_id')->unsigned()->index();
            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('cascade');

            $table->string('costo')->nullable();
            $table->string('venta')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caballos');
    }
};

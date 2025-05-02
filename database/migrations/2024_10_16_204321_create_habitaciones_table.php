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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('hotel_id')->unsigned()->index();
            $table->foreign('hotel_id')->references('id')->on('hoteles')->onDelete('cascade');

            $table->string('titulo')->nullable();
            $table->string('costo')->nullable();
            $table->string('cos_ext')->nullable();
            $table->string('nacionales')->nullable();
            $table->string('extranjeros')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};

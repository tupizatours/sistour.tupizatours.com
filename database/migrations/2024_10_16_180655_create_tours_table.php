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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();

            $table->unsignedBigInteger('categoria_id')->unsigned()->index();
            $table->foreign('categoria_id')->references('id')->on('tour_categorias')->onDelete('cascade');

            $table->string('duracion')->nullable();
            $table->string('noches')->nullable();
            $table->string('min_per')->nullable();
            $table->string('max_per')->nullable();
            $table->string('hor_lim')->nullable();
            $table->string('tipo')->nullable();
            $table->string('serv_tour')->nullable();
            $table->string('serv_cli')->nullable();
            $table->string('pre_uni')->nullable();
            $table->string('pre_tot')->nullable();
            $table->string('tickets')->nullable();
            $table->string('hoteles')->nullable();
            $table->string('accesorios')->nullable();
            $table->string('turistas')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};

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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->decimal('subtotal', 8, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();

            $table->unsignedBigInteger('tour_id')->unsigned()->index();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

            $table->string('tprivado')->nullable();
            $table->string('pre_per')->nullable();
            $table->string('can_per')->nullable();
            $table->string('pre_pri')->nullable();
            $table->string('can_pri')->nullable();
            $table->string('fecha')->nullable();
            $table->text('pago')->nullable();
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
        Schema::dropIfExists('reservas');
    }
};

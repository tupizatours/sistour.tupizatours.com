<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {

        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('apertura');
            $table->dateTime('cierre')->nullable();
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->decimal('monto_final', 10, 2)->nullable();
            $table->boolean('cerrada')->default(false);
            $table->unsignedBigInteger('user_id')->nullable(); // responsable
            $table->timestamps();
        });
        
        
    }

    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};

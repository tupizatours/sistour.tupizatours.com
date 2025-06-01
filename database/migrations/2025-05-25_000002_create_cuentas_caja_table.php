<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {

        Schema::create('cuentas_caja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->boolean('sistema')->default(false); // <- corregido aquí
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_caja');
    }
};

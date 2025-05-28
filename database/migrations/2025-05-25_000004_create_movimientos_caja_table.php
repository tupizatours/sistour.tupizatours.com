<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->onDelete('cascade');
            $table->foreignId('cuenta_caja_id')->constrained('cuentas_caja');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->decimal('monto', 10, 2);
            $table->string('origen')->nullable(); // ejemplo: reserva, gasto, etc.
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};

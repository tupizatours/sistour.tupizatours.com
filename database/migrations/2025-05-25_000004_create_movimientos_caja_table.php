<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained()->onDelete('cascade');
            $table->foreignId('cuenta_caja_id')->constrained('cuentas_caja')->onDelete('restrict');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->unsignedBigInteger('origen_id')->nullable(); // ID externo relacionado
            $table->decimal('monto', 10, 2);
            $table->text('descripcion')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};

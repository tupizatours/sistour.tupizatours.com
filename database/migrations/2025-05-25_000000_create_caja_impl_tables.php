<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('cajas')) {
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

        if (!Schema::hasTable('cuentas_caja')) {
            Schema::create('cuentas_caja', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->enum('tipo', ['ingreso', 'egreso']);
                $table->boolean('sistema')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('movimientos_caja')) {
            Schema::create('movimientos_caja', function (Blueprint $table) {
                $table->id();
                $table->foreignId('caja_id')->constrained()->onDelete('cascade');
                $table->foreignId('cuenta_caja_id')->constrained('cuentas_caja')->onDelete('restrict');
                $table->enum('tipo', ['ingreso', 'egreso']);
                $table->unsignedBigInteger('origen_id')->nullable();
                $table->decimal('monto', 10, 2);
                $table->text('descripcion')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('cuentas_caja');
        Schema::dropIfExists('cajas');
    }
};

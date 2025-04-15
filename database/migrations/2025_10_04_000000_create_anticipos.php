<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anticipos', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('prestatario_id'); // el dueño del recurso (caballo, vagoneta, etc.)
            $table->unsignedBigInteger('elemento_id');    // el recurso específico (caballo_id, vagoneta_id, etc.)
        
            $table->decimal('monto', 10, 2)->default(0);  // monto del anticipo
            $table->string('tipo_servicio');             // 'vagoneta', 'caballo', 'bicicleta', etc.
        
            $table->timestamps();
        
            // Relaciones sugeridas
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('prestatario_id')->references('id')->on('propietarios')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });       
    }

    public function down(): void
    {
        Schema::dropIfExists('gestions');
    }
};

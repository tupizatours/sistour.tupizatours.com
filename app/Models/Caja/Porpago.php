<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porpago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'tour_id',
        'tipo_servicio',     // Ej: 'guia', 'caballo', 'bicicleta'
        'servicio_id',       // ID del guía/traductor o prestatario
        'pres_serv_id',      // ID del elemento físico (caballo, vagoneta, etc.)
        'anticipo_id',       // FK a anticipos si se ha entregado anticipo
        'costo',             // Monto total asignado
        'es_prestatario',    // Boolean para distinguir si es un servicio de prestatario
        'estado',            // Estado del pago (ej: pendiente, pagado)
        'user_id',           // ← Nuevo: quien registra el movimiento
    ];

    protected $casts = [
        'es_prestatario' => 'boolean',
        'costo' => 'decimal:2',
    ];

    protected $table = 'porpagos';

    // Relaciones
    public function reserva()
    {
        return $this->belongsTo(\App\Models\Reserva::class, 'reserva_id');
    }

    public function tour()
    {
        return $this->belongsTo(\App\Models\Tour::class, 'tour_id');
    }

    public function anticipo()
    {
        return $this->belongsTo(\App\Models\Caja\Anticipo::class, 'anticipo_id');
    }

    public function prestatario()
    {
        return $this->belongsTo(\App\Models\Propietario::class, 'servicio_id');
    }

    public function elemento()
    {
        // Este método se puede personalizar con morphTo o lógica condicional si deseas acceder dinámicamente al "pres_serv_id"
        return null;
    }
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}

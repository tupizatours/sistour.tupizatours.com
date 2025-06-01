<?php

namespace App\Models\Caja;

use App\Models\Propietario;
use App\Models\Propietario\Chofer;
use App\Models\Propietario\Cocinero;
use App\Models\Propietario\Guia;
use App\Models\Propietario\Traductor;
use App\Models\Servicio\Bicicleta;
use App\Models\Servicio\Caballo;
use App\Models\Servicio\Vagoneta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porpago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'tour_id',
        'tipo_servicio',
        'servicio_id',
        'pres_serv_id',
        'anticipo_id',
        'costo',
        'es_prestatario',
        'estado',
        'user_id',
        'con_bienes',
    ];
    

    protected $casts = [
        'es_prestatario' => 'boolean',
        'con_bienes' => 'boolean', 
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

    public function servicio()
    {
        return match ($this->tipo_servicio) {
            'guia' => $this->belongsTo(Guia::class, 'servicio_id'),
            'chofer' => $this->belongsTo(Chofer::class, 'servicio_id'),
            'traductor' => $this->belongsTo(Traductor::class, 'servicio_id'),
            'cocinero' => $this->belongsTo(Cocinero::class, 'servicio_id'),
            'vagoneta' => $this->belongsTo(Vagoneta::class, 'servicio_id'),
            'bicicleta' => $this->belongsTo(Bicicleta::class, 'servicio_id'),
            'caballo' => $this->belongsTo(Caballo::class, 'servicio_id'),
            default => null,
        };
    }

    public function getNombrePrestatarioAttribute()
    {
        $servicio = $this->servicio;

        // Si es un servicio con bienes (vagoneta, caballo, bicicleta)
        if (in_array($this->tipo_servicio, ['vagoneta', 'bicicleta', 'caballo'])) {
            $prop = $servicio?->propietario;
            return $prop ? trim("{$prop->nombre} {$prop->apellido}") : 'Sin nombre';
        }

        // Servicios normales: guia, chofer, etc.
        return trim("{$servicio?->nombre} {$servicio?->apellido}");
    }


}


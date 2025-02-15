<?php

namespace App\Models\Reserva;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resercliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'pre_per',
        'subtotal',
        'total',
        'pagado',
        'reserva_id',
        'nombres',
        'apellidos',
        'edad',
        'nacionalidad',
        'documento',
        'celular',
        'sexo',
        'correo',
        'alergias',
        'alimentacion',
        'nota',
        'file',
        'tickets',
        'habitaciones',
        'accesorios',
        'servicios',
        'estado',
        'estatus',
        'esPrincipal'
    ];

    // Especificar los campos de tipo JSON
    protected $casts = [
        'tickets' => 'array',
        'habitaciones' => 'array',
        'accesorios' => 'array',
        'servicios' => 'array',
    ];

    public function reserva() {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }

    protected $table = 'reserclientes';
}

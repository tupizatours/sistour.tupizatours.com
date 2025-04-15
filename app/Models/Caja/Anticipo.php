<?php


namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;

class Anticipo extends Model
{
    protected $fillable = [
        'reserva_id',
        'prestatario_id',
        'elemento_id',
        'tipo_servicio',
        'monto',
        'user_id', // ← Nuevo: para saber quién entregó el anticipo

    ];

    public function reserva()
    {
        return $this->belongsTo(\App\Models\Reserva::class);
    }

    public function prestatario()
    {
        return $this->belongsTo(\App\Models\Propietario::class, 'prestatario_id');
    }
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
}

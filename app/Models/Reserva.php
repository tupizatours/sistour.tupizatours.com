<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'subtotal',
        'total',
        'pagado',
        'tour_id',
        'tprivado',
        'pre_per',
        'can_per',
        'pre_pri',
        'can_pri',
        'fecha',
        'pago',
        'estado',
        'estatus'
    ];

    protected $table = 'reservas';

    public function tour() {
        return $this->belongsTo('App\Models\Tour', 'tour_id', 'id');
    }

    public function turistas() {
        return $this->hasMany('App\Models\Reserva\Resercliente', 'reserva_id', 'id');
    }

    public function resclientes()
    {
        return $this->hasMany('App\Models\Reserva\Resercliente', 'reserva_id', 'id');
    }
}

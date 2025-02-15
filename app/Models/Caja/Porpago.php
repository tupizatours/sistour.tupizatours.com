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
        'vagonetas',
        'caballos',
        'bicicletas',
        'tickets',
        'anticipoActual',
        'subtotal',
        'prestatario',
        'anticipoAnterior',
        'saldo',
        'dserv'
    ];

    protected $casts = [
        'vagonetas' => 'array',
        'caballos' => 'array',
        'bicicletas' => 'array',
        'tickets' => 'array',
    ];

    protected $table = 'porpagos';

    public function banco() {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }

    public function tour() {
        return $this->belongsTo('App\Models\Tour', 'tour_id', 'id');
    }
}

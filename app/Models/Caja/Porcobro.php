<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porcobro extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'tour_id',
        'servicios',
        'guias',
        'traductors',
        'cocineros',
        'chofers',
        'tickets'
    ];

    protected $casts = [
        'servicios' => 'array',
        'guias' => 'array',
        'traductors' => 'array',
        'cocineros' => 'array',
        'chofers' => 'array',
        'tickets' => 'array',
    ];

    protected $table = 'porcobros';

    public function banco() {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }

    public function tour() {
        return $this->belongsTo('App\Models\Tour', 'tour_id', 'id');
    }
}

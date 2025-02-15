<?php

namespace App\Models\Venta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'reserva_id',
        'rescli_id',
        'user_id',
        'monto',
        'diferencia',
        'metodo',
        'conversion',
        'comision',
        'total',
        'estatus'
    ];

    protected $table = 'pagos';

    public function reserva() {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }

    public function rescli() {
        return $this->belongsTo('App\Models\Reserva\Resercliente', 'rescli_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}

<?php


namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\CuentaCaja;
use App\Models\Caja\Caja;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'cuenta_id',
        'tipo',       // ingreso | egreso
        'monto',
        'detalle',
        'origen_id',  // puede ser reserva_id, gestion_id, etc.
        'origen_tipo' // clase relacionada
    ];

    // 🔁 Relación con la caja
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    // 🔁 Relación con la cuenta
    public function cuenta()
    {
        return $this->belongsTo(CuentaCaja::class, 'cuenta_id');
    }

    // 🔁 Polimorfismo para vincular a reservas u otras entidades
    public function origen()
    {
        return $this->morphTo('origen');
    }
}

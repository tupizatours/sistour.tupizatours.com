<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\MovimientoCaja;
use App\Models\User;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'apertura',
        'cierre',
        'monto_inicial',
        'monto_final',
        'cerrada',
        'user_id',
    ];

    protected $casts = [
        'apertura' => 'datetime',
        'cierre' => 'datetime',
        'cerrada' => 'boolean',
    ];

    // 🔁 Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔁 Relación con movimientos
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    // 📊 Total ingresos
    public function totalIngresos()
    {
        return $this->movimientos()->where('tipo', 'ingreso')->sum('monto');
    }

    // 📉 Total egresos
    public function totalEgresos()
    {
        return $this->movimientos()->where('tipo', 'egreso')->sum('monto');
    }

    // 🧮 Saldo actual
    public function saldoActual()
    {
        return $this->monto_inicial + $this->totalIngresos() - $this->totalEgresos();
    }
}

<?php


namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\MovimientoCaja;

class CuentaCaja extends Model
{
    protected $table = 'cuentas_caja';

    protected $fillable = [
        'nombre',
        'tipo',            // ingreso | egreso
        'es_automatica',   // true si es protegida
    ];

    protected $casts = [
        'es_automatica' => 'boolean',  // ✅ nombre corregido para que coincida con fillable y migración
    ];

    // 🔁 Una cuenta puede tener muchos movimientos
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class, 'cuenta_caja_id');
    }
}


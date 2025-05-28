<?php


namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\MovimientoCaja;

class CuentaCaja extends Model
{
    protected $table = 'cuentas_caja';

    protected $fillable = [
        'nombre',
        'tipo',     // ingreso | egreso
        'sistema',  // true si es protegida
    ];

    protected $casts = [
        'sistema' => 'boolean',
    ];

    // 🔁 Una cuenta puede tener muchos movimientos
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class, 'cuenta_id');
    }
}

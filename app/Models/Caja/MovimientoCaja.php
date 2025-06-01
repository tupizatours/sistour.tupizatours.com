<?php


namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\CuentaCaja;
use App\Models\Caja\Caja;

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Caja\CuentaCaja;
use App\Models\Caja\Caja;
use App\Models\User;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'cuenta_caja_id',
        'tipo',         // ingreso | egreso
        'subtipo',      // caja | billetera | porpago | anticipo | trabajador | otro
        'origen_id',    // ID externo relacionado (opcional)
        'monto',
        'descripcion',
        'user_id',
    ];

    /**
     * Relación con la caja.
     */
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    /**
     * Relación con la cuenta contable.
     */
    public function cuenta()
    {
        return $this->belongsTo(CuentaCaja::class, 'cuenta_caja_id');
    }

    /**
     * Relación con el usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Determinar si es ingreso.
     */
    public function esIngreso()
    {
        return $this->tipo === 'ingreso';
    }

    /**
     * Determinar si es egreso.
     */
    public function esEgreso()
    {
        return $this->tipo === 'egreso';
    }
}

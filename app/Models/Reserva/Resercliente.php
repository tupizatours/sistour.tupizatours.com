<?php

namespace App\Models\Reserva;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resercliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'pre_per',
        'subtotal',
        'total',
        'pagado',
        'reserva_id',
        'nombres',
        'apellidos',
        'edad',
        'nacionalidad',
        'documento',
        'celular',
        'sexo',
        'correo',
        'alergias',
        'alimentacion',
        'nota',
        'file',
        'tickets',
        'habitaciones',
        'accesorios',
        'servicios',
        'estado',
        'estatus',
        'esPrincipal'
    ];

    // Especificar los campos de tipo JSON
    protected $casts = [
        'tickets' => 'array',
        'habitaciones' => 'array',
        'accesorios' => 'array',
        'servicios' => 'array',
    ];

    public function reserva()
    {
        return $this->belongsTo('App\Models\Reserva', 'reserva_id', 'id');
    }
    public function getTotalAttribute()
    {
        // Obtiene el precio del tour para este turista
        $pre_per = $this->pre_per ?? 0;

        // Laravel ya maneja estos atributos como arrays, solo los sumamos
        $serviciosAdicionales = collect($this->servicios ?: [])->sum('price');
        $habitacionesAdicionales = collect($this->habitaciones ?: [])->sum('price');

        $ticketsAdicionales = collect($this->tickets ?: [])->sum('price');
        $accesoriosAdicionales = collect($this->accesorios ?: [])->sum('price');

        return $pre_per + $serviciosAdicionales + $ticketsAdicionales + $accesoriosAdicionales + $habitacionesAdicionales;
    }
    
    public function getTotalPendienteAttribute()
    {
        // Obtiene los pagos realizados por el turista, considerando la conversión si aplica
        $pagosTurista = $this->pagos()->where('estatus', '1')->get()->sum(function ($pago) {
            return $pago->metodo === 'Bolivianos' ? $pago->monto : $pago->conversion;
        });
    
        // Obtiene los pagos generales de la reserva (sin duplicar pagos ya asociados a un turista)
        $pagosReserva = \App\Models\Venta\Pago::where('reserva_id', $this->reserva_id)
            ->where('estatus', '1')
            ->whereNull('rescli_id') // Solo contar pagos generales, no los de turistas específicos
            ->get()->sum(function ($pago) {
                return $pago->metodo === 'Bolivianos' ? $pago->monto : $pago->conversion;
            });
    
        // ✅ Sumar pagos realizados con conversión adecuada
        $totalPagado = $pagosTurista + $pagosReserva;
    
        // ✅ Obtener el total del tour (ya incluye servicios adicionales)
        $totalTour = $this->total ?? 0;
    
        // ✅ Calcular total pendiente asegurando que nunca sea negativo
        $totalPendiente = max($totalTour - $totalPagado, 0);
    
        return $totalPendiente;
    }
    

    public function pagos()
    {
        return $this->hasMany('App\Models\Venta\Pago', 'rescli_id', 'id');
    }

    protected $table = 'reserclientes';
}

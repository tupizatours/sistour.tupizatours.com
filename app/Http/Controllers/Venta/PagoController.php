<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Venta\Pago;
use App\Models\Reserva\Resercliente;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $in = [
            'codigo'       => str_random(10),
            'reserva_id'   => $request->reserva_id,
            'rescli_id'    => $request->rescli_id,
            'user_id'      => $request->user_id,
            'monto'        => $request->monto,
            'diferencia'   => $request->diferencia,
            'metodo'       => $request->metodo,
            'conversion'   => $request->forma == "directo" ? $request->monto : $request->conversion,
            'comision'     => $request->forma == "directo" ? 0 : $request->comision,
            'total'        => $request->forma == "directo" ? $request->monto : $request->total,
            'estatus'      => 1,
        ];

        $store = Pago::create($in);

        $res = Reserva::find($request->reserva_id);
        $res->estado = 2;
        $res->save();

        $rescli = Resercliente::find($request->rescli_id);

        // Si se seleccionó una nueva reserva en el select
        if ($request->has('tour_id') && !empty($request->tour_id)) {
            // Cambiar la reserva_id y marcar como no principal
            $rescli->reserva_id = $request->tour_id;
            $rescli->esPrincipal = 0;
        }

        $rescli->pagado = $request->monto;
        $rescli->save();
        
        if ($request->has('tour_id') && !empty($request->tour_id)) {
            // Redirigir a la reserva del nuevo tour seleccionado
            return redirect('ventas/reservas/' . $request->tour_id)
                ->with('success', 'Nueva pago agregado.');
        } else {
            // Redirigir a la reserva original
            return redirect('ventas/reservas/' . $request->reserva_id)
                ->with('success', 'Nueva pago agregado.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

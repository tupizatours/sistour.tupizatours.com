<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja\Porpago;
use App\Models\Caja\Anticipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CajaController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'reserva_id' => 'required|exists:reservas,id',
            'tour_id'    => 'required|exists:tours,id',
            'dserv'      => 'required|string',
            'dserid'     => 'required|integer',
            'subtotal'   => 'required|numeric|min:0.01',
            'total'      => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $anticipo = null;
        $anticipoMonto = floatval($request->anticipoActual ?? 0);
        $userId = Auth::id();

        // Si hay anticipo, crear Anticipo y asociar
        if ($anticipoMonto > 0) {
            if (!$request->prestatario) {
                return back()->with('error', 'Debes seleccionar un prestatario para el anticipo.')->withInput();
            }

            $anticipo = Anticipo::create([
                'reserva_id'     => $request->reserva_id,
                'prestatario_id' => $request->prestatario,
                'elemento_id'    => $request->dserid,
                'tipo_servicio'  => $request->dserv,
                'monto'          => $anticipoMonto,
                'user_id'        => $userId,
            ]);
        }

        // Verificar saldo disponible de anticipos
        $anticiposTotal = Anticipo::where('reserva_id', $request->reserva_id)
            ->where('prestatario_id', $request->prestatario)
            ->sum('monto');

        $porpagosTotal = Porpago::where('reserva_id', $request->reserva_id)
            ->where('servicio_id', $request->prestatario)
            ->sum('costo');

        $saldoDisponible = $anticiposTotal - $porpagosTotal;

        if ($request->subtotal > $saldoDisponible) {
            return back()->with('error', 'Saldo insuficiente para este prestatario. Disponible: Bs. ' . number_format($saldoDisponible, 2))->withInput();
        }

        // Registrar Porpago
        Porpago::create([
            'reserva_id'    => $request->reserva_id,
            'tour_id'       => $request->tour_id,
            'tipo_servicio' => $request->dserv,
            'servicio_id'   => $request->prestatario,
            'pres_serv_id'  => $request->dserid,
            'anticipo_id'   => $anticipo?->id,
            'costo'         => $request->total,
            'es_prestatario' => true,
            'estado'        => 'pendiente',
            'user_id'       => $userId,
        ]);

        return back()->with('success', 'Operación registrada correctamente.');
    }


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

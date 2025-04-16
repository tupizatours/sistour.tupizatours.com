<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja\Porpago;
use App\Models\Caja\Anticipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CajaController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();
    
        $validator = Validator::make($data, [
            'reserva_id' => 'required|exists:reservas,id',
            'tour_id'    => 'required|exists:tours,id',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $userId = Auth::id();
        $anticipo = null;
        $anticipoMonto = floatval($request->monto_anticipo ?? 0);
        $prestatarioId = $request->prestatario;
        $elementoId = $request->dserid;
        $tipoServicioAnticipo = $request->dserv;
    
        // 1. Registrar anticipo si existe
        if ($anticipoMonto > 0) {
            if (!$prestatarioId) {
                return back()->with('error', 'Debes seleccionar un prestatario para el anticipo.')->withInput();
            }
    
            $anticipo = Anticipo::create([
                'reserva_id'     => $request->reserva_id,
                'prestatario_id' => $prestatarioId,
                'elemento_id'    => $elementoId,
                'tipo_servicio'  => $tipoServicioAnticipo,
                'monto'          => $anticipoMonto,
                'user_id'        => $userId,
            ]);
        }
    
        // 2. Recorrer totalidades seleccionadas (checkboxes[])
        if (isset($request->checkboxes) && is_array($request->checkboxes)) {
            foreach ($request->checkboxes as $clave => $info) {
                if (!isset($info['selected'])) continue;
    
                $tipo = strtolower($clave); // hoteles, tickets, etc.
                $monto = floatval($info['monto']);
    
                // Opción: si quieres que todos se registren como NO prestatarios
                $esPrestatario = true;
    
                // Validar contra saldo si hay prestatario
                if ($prestatarioId && $anticipoMonto > 0) {
                    $anticiposTotal = Anticipo::where('reserva_id', $request->reserva_id)
                        ->where('prestatario_id', $prestatarioId)
                        ->sum('monto');
    
                    $porpagosTotal = Porpago::where('reserva_id', $request->reserva_id)
                        ->where('servicio_id', $prestatarioId)
                        ->sum('costo');
    
                    $saldoDisponible = $anticiposTotal - $porpagosTotal;
    
                    if ($monto > $saldoDisponible) {
                        return back()->with('error', "Saldo insuficiente para el item '$tipo'. Disponible: Bs. " . number_format($saldoDisponible, 2))->withInput();
                    }
                }
    
                // Crear o actualizar por tipo de totalidad
                Porpago::updateOrCreate(
                    [
                        'reserva_id'    => $request->reserva_id,
                        'tour_id'       => $request->tour_id,
                        'tipo_servicio' => $tipo,
                    ],
                    [
                        'servicio_id'    => $prestatarioId,
                        'pres_serv_id'   => null,
                        'anticipo_id'    => $anticipo?->id,
                        'costo'          => $monto,
                        'es_prestatario' => $esPrestatario,
                        'estado'         => 'pendiente',
                        'user_id'        => $userId,
                    ]
                );
            }
        }
    
        return back()->with('success', 'Totalidades registradas correctamente.');
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

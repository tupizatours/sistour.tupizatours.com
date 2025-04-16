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
    
        // 2. Recorrer totalidades seleccionadas (sin validación de saldo)
        if ($request->filled('totalidades')) {
            foreach ($request->totalidades as $totalidad) {
                $tipo = strtolower($totalidad['nombre']);
                $monto = floatval($totalidad['monto']);
        
                Porpago::updateOrCreate(
                    [
                        'reserva_id'    => $request->reserva_id,
                        'tour_id'       => $request->tour_id,
                        'tipo_servicio' => $tipo,
                    ],
                    [
                        'servicio_id'    => null, // o null si no aplica
                        'pres_serv_id'   => $request->prestatario,
                        'anticipo_id'    => null,
                        'costo'          => $monto,
                        'es_prestatario' => false,
                        'estado'         => 'pagado',
                        'user_id'        => $userId,
                    ]
                );
            }
        }        
    
        // 3. Registrar pago por anticipo (si existe dserv)
        if ($tipoServicioAnticipo && $prestatarioId) {
            Porpago::updateOrCreate(
                [
                    'reserva_id'    => $request->reserva_id,
                    'tour_id'       => $request->tour_id,
                    'tipo_servicio' => $tipoServicioAnticipo,
                ],
                [
                    'servicio_id'    => $prestatarioId,
                    'pres_serv_id'   => $elementoId,
                    'anticipo_id'    => $anticipo?->id,
                    'es_prestatario' => true,
                    'estado'         => 'pendiente',
                    'user_id'        => $userId,
                ]
            );
        }
    
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

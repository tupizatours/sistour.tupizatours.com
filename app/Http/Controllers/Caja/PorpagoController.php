<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja\Porpago;
use App\Models\Caja\Anticipo;

class PorpagoController extends Controller
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

    /**
     * Validar monto servicio para pago de totalidad.
     */
    public function validarMontoServicio(Request $request)
    {
        $reservaId = $request->input('reserva_id');
        $tipo = $request->input('tipo_servicio'); // 'vagoneta', 'caballo', etc.
        $servicioId = $request->input('servicio_id'); // id del prestatario

        if (!$reservaId || !$tipo || !$servicioId) {
            return response()->json(['error' => 'Faltan datos para validar.'], 422);
        }

        $porpago = Porpago::where('reserva_id', $reservaId)
            ->where('tipo_servicio', $tipo)
            ->where('servicio_id', $servicioId)
            ->first();

        if (!$porpago) {
            return response()->json(['saldo_disponible' => 0, 'error' => 'Servicio no encontrado.'], 404);
        }

        // Resta anticipo ya usado, si lo hay
        $anticipoUsado = $porpago->anticipo_id ? $porpago->anticipo->monto ?? 0 : 0;
        $saldoDisponible = $porpago->costo - $anticipoUsado;

        return response()->json([
            'saldo_disponible' => max(0, $saldoDisponible),
        ]);
    }

    /**
     * Validar saldo de anticipo.
     */
    public function saldoAnticipo(Request $request)
    {
        $reservaId = $request->input('reserva_id');
        $prestatarioId = $request->input('prestatario_id');

        if (!$reservaId || !$prestatarioId) {
            return response()->json(['error' => 'Datos incompletos.'], 422);
        }

        // Anticipos disponibles para esa reserva y prestatario
        $anticipos = Anticipo::where('reserva_id', $reservaId)
            ->where('prestatario_id', $prestatarioId)
            ->get();

        // Si no hay, saldo 0
        if ($anticipos->isEmpty()) {
            // Buscar servicios asignados en porpagos con es_prestatario = true
            $porpagos = Porpago::where('reserva_id', $reservaId)
                ->where('servicio_id', $prestatarioId)
                ->where('es_prestatario', true)
                ->get();
        
            $cupoMaximo = $porpagos->sum('costo');
        
            return response()->json([
                'saldo_disponible' => $cupoMaximo,
                'es_primera_vez' => true
            ]);
        }
        
        $saldo = $anticipos->sum('monto'); // Puedes restar pagos si están relacionados

        return response()->json([
            'saldo_disponible' => $saldo,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Mail\ReservaConfirmada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Venta\Pago;
use App\Models\Reserva\Resercliente;
use Barryvdh\DomPDF\Facade\Pdf;


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
        // Validar entrada
        $request->validate([
            'metodo' => 'required|string',
            'monto' => 'required|numeric|min:1',
        ]);
    
        // Obtener la reserva y el cliente asociado
        $rescli = Resercliente::find($request->rescli_id);
    
        if (!$rescli) {
            return back()->with('error', 'Reserva no encontrada.');
        }
    
        // Obtener la reserva asociada
        $reserva = Reserva::find($rescli->reserva_id);
    
        if (!$reserva) {
            return back()->with('error', 'No se encontró la reserva asociada.');
        }
    
        // Obtener el total pendiente actualizado
        $totalPendiente = $reserva->total - $rescli->pagado;
    
        // Verificar si aún hay saldo pendiente
        if ($totalPendiente <= 0) {
            return back()->with('error', 'No hay saldo pendiente para esta reserva.');
        }
    
        // Verificar que el monto no exceda el saldo pendiente
        if ($request->monto > $totalPendiente) {
            return back()->with('error', 'El monto ingresado excede el saldo pendiente.');
        }
    
        // Obtener la tasa de conversión desde la base de datos
        $tasaConversion = $this->obtenerTasaConversion($request->metodo) ?? 1; // Asegurar que sea 1 si no hay tasa
    
        // Calcular conversión y comisiones
        $conversion = $request->monto * $tasaConversion;
        $comision = $this->calcularComision($request->metodo) ?? 0;
        $totalPago = $conversion + $comision;
    
        // Crear el pago
        $pago = Pago::create([
            'codigo' => uniqid(),
            'reserva_id' => $reserva->id,
            'rescli_id' => $rescli->id,
            'user_id' => auth()->id(),
            'monto' => $request->monto,
            'conversion' => $conversion,
            'comision' => $comision,
            'total' => $totalPago,
            'metodo' => $request->metodo,
            'estatus' => '1',
        ]);
    
        // Actualizar monto pagado del cliente
        $rescli->pagado += $request->monto;
        $rescli->save();
    
        // Recalcular el total pendiente después del pago
        $nuevoSaldoPendiente = $reserva->total - $rescli->pagado;
    
        // Verificar si el pago cubre el total pendiente y actualizar el estado
        if ($nuevoSaldoPendiente >= 0) {
            $reserva->estado = '2'; // Si estado es VARCHAR
            $reserva->save();
        }
    
        // Obtener la lista de turistas adicionales (excluyendo al principal)
        $touristasAdicionales = Resercliente::where('reserva_id', $reserva->id)
            ->where('esPrincipal', false) // Solo los adicionales
            ->get();

        $linksTuristas = [];

        foreach ($touristasAdicionales as $turista) {
            $linksTuristas[] = [
                'nombre' => $turista->nombres,
                'apellido' => $turista->apellidos,
                'link' => url('/ventas/resclis/user/' . $turista->id)
            ];
        }

        // PDF
        $pdfPath = $this->generarResumenReservaPDF($reserva, $rescli);

        // 🔹 Enviar correo de confirmación de pago
        if ($request->origen !== 'resclis') {

            $data = [
                'nombre' => $rescli->nombre,
                'apellidos' => $rescli->apellido,
                'email' => $rescli->correo,
                'codigo_reserva' => $reserva->codigo,
                'monto_pagado' => number_format($request->monto, 2, '.', ''),
                'total' => $rescli->total,
                'fecha_reserva' => $reserva->fecha_reserva,
                'cantidad_personas' => $reserva->can_per,
                'estado' => 'Confirmada',
                'tour_id' => $reserva->id,
                'fecha_reserva' => $reserva->fecha,
                'turistas_adicionales' => $linksTuristas,
            ];

            // Enviar el correo
            Mail::to($rescli->correo)->send(new ReservaConfirmada($data, $pdfPath));
        }
        return redirect('ventas/reservas/' . $request->reserva_id)
            ->with('success', 'Pago registrado exitosamente y correo enviado.');
    }

    /**
     * Obtener la tasa de conversión de la divisa seleccionada desde la base de datos.
     */
    private function obtenerTasaConversion($metodoPago)
    {
        $cobro = \App\Models\Configuracion\Cobro::where('titulo', $metodoPago)->first();

        return $cobro ? $cobro->tipo : 1; // Si no encuentra la divisa, usa 1 como valor por defecto
    }

    /**
     * Calcular la comisión del pago según el método seleccionado.
     */
    private function calcularComision($metodoPago)
    {
        $cobro = \App\Models\Configuracion\Cobro::where('titulo', $metodoPago)->first();

        if (!$cobro) {
            return 0; // Si no se encuentra el método, no se cobra comisión
        }
    
        // Si la comisión es mayor o igual a 1, asumimos que es un monto fijo
        return $cobro->comision;
    }

    private function generarResumenReservaPDF($reserva, $cliente)
    {
        // Decodificar si es string, sino usar tal cual
        $habitacionesRaw = is_string($cliente->habitaciones) ? json_decode($cliente->habitaciones, true) : $cliente->habitaciones;
        $ticketsRaw = is_string($cliente->tickets) ? json_decode($cliente->tickets, true) : $cliente->tickets;
        $accesoriosRaw = is_string($cliente->accesorios) ? json_decode($cliente->accesorios, true) : $cliente->accesorios;
        $serviciosRaw = is_string($cliente->servicios) ? json_decode($cliente->servicios, true) : $cliente->servicios;
    
        $habitaciones = collect($habitacionesRaw ?? [])->map(function ($item) {
            $habit = \App\Models\Servicio\Habitacion::with('hotel')->find($item['id']);
            return [
                'hotel' => $habit?->hotel->titulo ?? 'Hotel',
                'name' => $item['name'],
                'price' => $item['price'],
            ];
        });
    
        $tickets = collect($ticketsRaw ?? []);
        $accesorios = collect($accesoriosRaw ?? []);
        $servicios = collect($serviciosRaw ?? []);
    
        $alergias = \App\Models\Configuracion\Alergia::whereIn('id', is_string($cliente->alergias) ? json_decode($cliente->alergias) : ($cliente->alergias ?? []))->get();
        $alimentos = \App\Models\Configuracion\Alimentacion::whereIn('id', is_string($cliente->alimentacion) ? json_decode($cliente->alimentacion) : ($cliente->alimentacion ?? []))->get();
    
        $pdf = Pdf::loadView('pdf.reserva', compact(
            'reserva',
            'cliente',
            'habitaciones',
            'tickets',
            'accesorios',
            'servicios',
            'alergias',
            'alimentos'
        ));
    
        $pdfPath = storage_path('app/public/reservas/resumen_' . $reserva->codigo . '.pdf');
        $pdf->save($pdfPath);
    
        return $pdfPath;
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

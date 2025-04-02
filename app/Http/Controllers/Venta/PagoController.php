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
     * - the form for save a resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'metodo' => 'required|string',
            'monto' => 'required|numeric|min:1',
        ]);

        $rescli = Resercliente::find($request->rescli_id);
        $reserva = Reserva::find($rescli->reserva_id);

        if (!$rescli || !$reserva) {
            return back()->with('error', 'Reserva no encontrada.');
        }

        $totalPendiente = round($reserva->total - $rescli->pagado, 2);
        if ($totalPendiente <= 0) {
            return back()->with('error', 'No hay saldo pendiente para esta reserva.');
        }

        $montoIngresado = $request->monto;
        $metodo = $request->metodo;
        $tasa = $this->obtenerTasaConversion($metodo) ?? 1;
        $comision = $this->calcularComision($metodo) ?? 0;

        // Moneda nacional
        if ($metodo === 'Bolivianos' || $metodo === 'Tarjeta de crédito' || $metodo === 'Transferencia bancaria' ||  $tasa == 1  ) {
            $montoAplicado = min($montoIngresado, $totalPendiente);
            $vuelto = $montoIngresado - $montoAplicado;

            // Registrar
            Pago::create([
                'codigo' => uniqid(),
                'reserva_id' => $reserva->id,
                'rescli_id' => $rescli->id,
                'user_id' => auth()->id(),
                'monto' => $montoAplicado,
                'conversion' => 0,
                'comision' => 0,
                'total' => $montoAplicado,
                'metodo' => $metodo,
                'estatus' => '1',
            ]);

            $rescli->pagado += $montoAplicado;
            $rescli->save();

            if (($reserva->total - $rescli->pagado) <= 0) {
                $reserva->estado = '2';
                $reserva->save();
            }

            if ($vuelto > 0) {
                return view('ventas.resclis.vuelto', [
                    'rescli_id' => $rescli->id,
                    'codigo_reserva' => $reserva->codigo,
                    'monto_ingresado' => $montoIngresado,
                    'monto_pagado' => $montoAplicado,
                    'vuelto_bs' => $vuelto,
                    'vuelto_moneda' => null,
                    'metodo' => $metodo,
                ]);
            }
        }

        // Moneda extranjera
        else {
            $montoBs = round($montoIngresado * $tasa, 2);
            $conversionAplicada = min($montoBs, $totalPendiente);
            $montoAplicado = round($conversionAplicada / $tasa, 2); // monto real aplicado en moneda extranjera
            $vueltoMoneda = $montoIngresado - $montoAplicado;
            $vueltoBs = round($vueltoMoneda * $tasa, 2);

            Pago::create([
                'codigo' => uniqid(),
                'reserva_id' => $reserva->id,
                'rescli_id' => $rescli->id,
                'user_id' => auth()->id(),
                'monto' => $montoAplicado,
                'conversion' => $conversionAplicada,
                'comision' => $comision,
                'total' => $conversionAplicada + $comision,
                'metodo' => $metodo,
                'estatus' => '1',
            ]);

            $rescli->pagado += $conversionAplicada;
            $rescli->save();

            if (($reserva->total - $rescli->pagado) <= 0) {
                $reserva->estado = '2';
                $reserva->save();
            }

            if ($vueltoBs > 0) {
                return view('ventas.resclis.vuelto', [
                    'rescli_id' => $rescli->id,
                    'codigo_reserva' => $reserva->codigo,
                    'monto_ingresado' => $montoIngresado,
                    'monto_pagado' => $montoAplicado,
                    'vuelto_bs' => $vueltoBs,
                    'vuelto_moneda' => $vueltoMoneda,
                    'metodo' => $metodo,
                ]);
            }
        }

        // PDF y correo
        $pdfPath = $this->generarResumenReservaPDF($reserva, $rescli);
        $data = [ /* datos para el correo */];

        try {
            Mail::to($rescli->correo)->send(new ReservaConfirmada($data, $pdfPath));
        } catch (\Exception $e) {
            \Log::error('Error al enviar correo: ' . $e->getMessage());
        }

        return redirect('ventas/reservas/' . $reserva->id)
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
        // Decodificar o usar directamente si ya es array
        $habitacionesRaw = is_string($cliente->habitaciones) ? json_decode($cliente->habitaciones, true) : ($cliente->habitaciones ?? []);
        $ticketsRaw = is_string($cliente->tickets) ? json_decode($cliente->tickets, true) : ($cliente->tickets ?? []);
        $accesoriosRaw = is_string($cliente->accesorios) ? json_decode($cliente->accesorios, true) : ($cliente->accesorios ?? []);
        $serviciosRaw = is_string($cliente->servicios) ? json_decode($cliente->servicios, true) : ($cliente->servicios ?? []);

        // Habitaciones con hotel
        $habitaciones = collect($habitacionesRaw)->map(function ($item) {
            $habit = \App\Models\Servicio\Habitacion::with('hotel')->find($item['id'] ?? 0);
            return [
                'hotel' => $habit?->hotel?->titulo ?? 'Hotel no especificado',
                'name'  => $item['name'] ?? 'Habitación',
                'price' => $item['price'] ?? 0,
            ];
        });

        // Resto como colecciones limpias
        $tickets    = collect($ticketsRaw ?? []);
        $accesorios = collect($accesoriosRaw ?? []);
        $servicios  = collect($serviciosRaw ?? []);

        // Alergias y alimentación
        $alergias = collect();
        $alimentos = collect();

        $alergiaIds = is_string($cliente->alergias) ? json_decode($cliente->alergias, true) : ($cliente->alergias ?? []);
        $alimentacionIds = is_string($cliente->alimentacion) ? json_decode($cliente->alimentacion, true) : ($cliente->alimentacion ?? []);

        if (is_array($alergiaIds) && !empty($alergiaIds)) {
            $alergias = \App\Models\Configuracion\Alergia::whereIn('id', $alergiaIds)->get();
        }

        if (is_array($alimentacionIds) && !empty($alimentacionIds)) {
            $alimentos = \App\Models\Configuracion\Alimentacion::whereIn('id', $alimentacionIds)->get();
        }

        // Generar PDF
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

        // Guardar en ruta definida
        $folderPath = public_path('reservas');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true); // Crea la carpeta si no existe
        }

        $pdfPath = $folderPath . '/resumen_' . $reserva->codigo . '.pdf';
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

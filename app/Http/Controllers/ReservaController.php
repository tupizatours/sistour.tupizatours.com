<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Country;
use App\Models\Reserva\Resercliente;
use App\Models\Tour;
use App\Mail\ReservaTour;
use DB;
use Image;
use Illuminate\Support\Str;
use App\Services\NotificacionReservaService;

use Illuminate\Support\Facades\Mail;

use App\Models\Configuracion\Link;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function gracias()
    {
        return view('reservas.gracias');
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
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        // Datos adicionales
        $alergias = json_encode($request->alergias ?? []);
        $alimentacion = json_encode($request->alimentacion ?? []);

        $tickets     = json_decode($request->input('tickets_seleccionados'), true) ?? [];
        $rooms       = json_decode($request->input('habitaciones_seleccionadas'), true) ?? [];
        $accessories = json_decode($request->input('accesorios_seleccionados'), true) ?? [];
        $services    = json_decode($request->input('servicios_seleccionados'), true) ?? [];

        // Manejo del archivo
        if ($imagen = $request->file('file')) {
            $rutaGuardarmg = 'files_documentos';
            $nombreOriginal = time() . '_' . $imagen->getClientOriginalName();
            $extension = $imagen->getClientOriginalExtension();

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                Image::make($imagen)->fit(300, 300)->save(public_path("$rutaGuardarmg/$nombreOriginal"));
            } elseif ($extension === 'pdf') {
                $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
            }

            $fotoQr = $nombreOriginal;
        }

        // 🧠 Cálculo lógico
        $precioUnidad = floatval($request->pre_uni);
        $cantidad = intval($request->cantper);
        $esPrivado = $request->tprivado ? true : false;
        $adicionales = collect(array_merge($tickets, $rooms, $accessories, $services))
            ->pluck('price')
            ->sum();

        $pre_pri = $esPrivado
            ? floatval($request->pre_tot)
            : $precioUnidad * $cantidad;

        $esExterno = true;
        $totalReserva = 0;

        // Crear reserva
        $reserva = Reserva::create([
            'codigo'    => Str::random(10),
            'subtotal'  => 0,
            'total'     => 0,
            'tour_id'   => $request->tour_id,
            'tprivado'  => $esPrivado,
            'pre_per'   => $precioUnidad,
            'can_per'   => $cantidad,
            'pre_pri'   => $pre_pri,
            'can_pri'   => $request->max_per,
            'fecha'     => $request->fecha_limite,
            'estado'    => 1,
            'estatus'   => $request->estatus,
        ]);

        // Crear clientes
        for ($i = 0; $i < $cantidad; $i++) {
            $esPrincipal = $i === 0;

            $sub = $precioUnidad;
            $res_total = $esPrincipal ? $precioUnidad + $adicionales : $precioUnidad;
            $totalReserva += $res_total;

            $rescli = [
                'codigo'      => Str::random(10),
                'pre_per'     => $precioUnidad,
                'subtotal'    => $sub,
                'total'       => $res_total,
                'reserva_id'  => $reserva->id,
                'estado'      => 1,
                'estatus'     => $request->estatus,
                'esPrincipal' => $esPrincipal,
            ];

            if ($esPrincipal) {
                $rescli = array_merge($rescli, [
                    'nombres'       => $request->nombres,
                    'apellidos'     => $request->apellidos,
                    'edad'          => $request->edad,
                    'nacionalidad'  => $request->nacionalidad,
                    'documento'     => $request->documento,
                    'celular'       => $request->celular,
                    'sexo'          => $request->sexo,
                    'correo'        => $request->email,
                    'alergias'      => $alergias,
                    'alimentacion'  => $alimentacion,
                    'nota'          => $request->nota,
                    'file'          => $fotoQr,
                    'tickets'       => $tickets,
                    'habitaciones'  => $rooms,
                    'accesorios'    => $accessories,
                    'servicios'     => $services,
                ]);
            }

            Resercliente::create($rescli);
        }

        // Actualiza totales
        $reserva->update([
            'subtotal' => $precioUnidad * $cantidad,
            'total'    => $totalReserva,
        ]);

        // Envío de notificación con nuevo servicio
        $cliente = Resercliente::where('reserva_id', $reserva->id)
        ->where('esPrincipal', true)
        ->first();

        // Detecta página origen
        $pagina = $request->input('pagina') ?? 'user_external';

        // Envío de notificación
        try {
            Mail::to($cliente->correo)->send(new ReservaTour($reserva, $cliente, $pagina));
        } catch (\Exception $e) {
            \Log::error('No se pudo enviar el correo de reserva: ' . $e->getMessage(), [
                'correo' => $cliente->correo ?? 'sin correo',
                'reserva_id' => $reserva->id,
            ]);
        }

        return view('reservas.gracias');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::findOrFail($id);
        $links = \App\Models\Configuracion\Link::where('estatus', 1)->get();
        $onlines = \App\Models\Configuracion\Online::where('estatus', 1)->get();
        $qrs = \App\Models\Configuracion\Qr::where('estatus', 1)->get();
    
        return view('reservas.edit', compact('reserva', 'links', 'onlines', 'qrs'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reserva = Reserva::find($id);
        $resclis = Resercliente::where('reserva_id', $id)->get(); // Filtrar Resercliente por reserva_id
        $tours = Tour::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        
        return view('reservas.edit', compact('reserva', 'resclis', 'tours', 'links', 'onlines', 'qrs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        if ($request->pagina == "file_email") {
            $this->procesarComprobantePago($request, $id);
            return view('reservas.pago');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

       /**
     * External Rute
     */
    public function externalStore(Request $request)
    {
        if ($request->pagina !== 'user_external') {
            abort(403, 'No autorizado');
        }

        return $this->store($request); // o copia manual la lógica
    }

    public function externalUpdate(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        if ($request->input('pagina') !== 'file_email') {
            abort(403, 'No autorizado');
        }

        $this->procesarComprobantePago($request, $id);

        return view('reservas.pago');
    }
    

    private function procesarComprobantePago(Request $request, $reservaId)
    {
        if ($imagen = $request->file('file')) {
            $rutaGuardarmg = 'files_pagos';
            $nombreOriginal = $imagen->getClientOriginalName();
            $extension = $imagen->getClientOriginalExtension();
            $fotoPago = $nombreOriginal;

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                Image::make($imagen)->fit(300, 300)->save(public_path("$rutaGuardarmg/$nombreOriginal"));
            } elseif ($extension === 'pdf') {
                $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
            }

            // Actualizar reserva
            $reserva = Reserva::findOrFail($reservaId);
            $reserva->update(['pago' => $fotoPago]);

            return true;
        }

        return false;
    }


}

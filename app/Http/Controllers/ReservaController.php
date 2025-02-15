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
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048', // Máximo 2MB
        ]);

        $alergias = json_encode($request->alergias);
        $alimentacion = json_encode($request->alimentacion);
        
        $tickets = json_decode($request->input('tickets_seleccionados'), true);
        $rooms = json_decode($request->input('habitaciones_seleccionadas'), true);
        $accessories = json_decode($request->input('accesorios_seleccionados'), true);
        $services = json_decode($request->input('servicios_seleccionados'), true);

        if($imagen = $request->File('file')) {
            $rutaGuardarmg = 'files_documentos';
            $nombreOriginal = $imagen->getClientOriginalName();
            $extension = $imagen->getClientOriginalExtension();

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Procesar imagen
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
            } elseif ($extension === 'pdf') {
                // Guardar directamente el PDF
                $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
            }

            $fotoQr = "$nombreOriginal";
        }

        // Crea la reserva y guarda los datos
        $in = [
            'codigo'        => str_random(10),
            'subtotal'      => $request->pre_tot,
            'total'         => $request->tour_total,
            'tour_id'       => $request->tour_id,
            'tprivado'      => $request->tprivado,
            'pre_per'       => $request->pre_uni,
            'can_per'       => $request->cantper,
            'pre_pri'       => $request->pre_tot,
            'can_pri'       => $request->max_per,
            'fecha'         => $request->fecha_limite,
            'estado'        => 1,
            'estatus'       => $request->estatus,
        ];

        $store = Reserva::create($in);

        $country = Country::where('iso',$request->nacionalidad)->first();

        // Crear registros en Resercliente
        for ($i = 0; $i < $request->cantper; $i++) {
            if ($i === 0) {
                // Primer registro, guarda todos los datos
                $rs = [
                    'codigo'            => str_random(10),
                    'pre_per'           => $request->pre_uni,
                    //'subtotal'          => $request->pre_tot,
                    'total'             => $request->tour_total,
                    'reserva_id'        => $store->id,
                    'nombres'           => $request->nombres,
                    'apellidos'         => $request->apellidos,
                    'edad'              => $request->edad,
                    'nacionalidad'      => $request->nacionalidad,
                    'documento'         => $request->documento,
                    'celular'           => $request->celular,
                    'sexo'              => $request->sexo,
                    'correo'            => $request->email,
                    'alergias'          => $alergias,
                    'alimentacion'      => $alimentacion,
                    'nota'              => $request->nota,
                    'file'              => $fotoQr,
                    'tickets'           => $tickets,
                    'habitaciones'      => $rooms,
                    'accesorios'        => $accessories,
                    'servicios'         => $services,
                    'estado'            => 1,
                    'estatus'           => $request->estatus,
                    'esPrincipal'       => true, // Primer registro como principal
                ];
            } else {
                // Registros subsiguientes, guarda solo los campos especificados
                $rs = [
                    'codigo'            => str_random(10),
                    'pre_per'           => $request->pre_uni,
                    'reserva_id'        => $store->id,
                    'estado'            => 1,
                    'estatus'           => $request->estatus,
                    'esPrincipal'       => false, // Registros adicionales no son principales
                ];
            }
            
            Resercliente::create($rs);
        }

        $data = $request->all();
        $tour_id = $request->tour_id;

        $response = \Mail::to($request->email)->send(new ReservaTour($data, $store->id));
        //$response = \Mail::to('danielmayurilevano@gmail.com')->send(new ReservaTour($data, $tour_id));

        return view ('reservas.gracias');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
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
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048', // Máximo 2MB
        ]);        
        if($request->pagina == "file_email"){
            if($imagen = $request->File('file')) {
                $rutaGuardarmg = 'files_pagos';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoPago = "$nombreOriginal";
            }

            $in['pago'] = $fotoPago;
            
            $res = Reserva::find($id);
            $res->update($in);

            return view ('reservas.pago');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

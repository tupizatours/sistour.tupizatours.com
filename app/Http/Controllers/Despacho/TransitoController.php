<?php

namespace App\Http\Controllers\Despacho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva\Resercliente;
use App\Models\Tour;
use App\Models\Tour\HotelTour;
use App\Models\Servicio;
use App\Models\Tour\Categoria;
use App\Models\Servicio\Vagoneta;
use App\Models\Servicio\Caballo;
use App\Models\Servicio\Bicicleta;
use App\Models\Propietario;
use App\Models\Propietario\Chofer;
use App\Models\Propietario\Cocinero;
use App\Models\Propietario\Guia;
use App\Models\Propietario\Traductor;
use App\Models\Despacho\Gestion;
use Barryvdh\DomPDF\Facade\Pdf;


use DB;

class TransitoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $tours = Tour::all();
        $servicios = Servicio::all();
        $vagonetas = Vagoneta::all();
        $caballors = Caballo::all();
        $bicicletas = Bicicleta::all();
        $propietarios = Propietario::all();
        $chofers = Chofer::all();
        $cocineros = Cocinero::all();
        $guias = Guia::all();
        $traductors = Traductor::all();
        
        return view('despachos.transitos.index', compact('reservas', 'tours', 'servicios', 'vagonetas', 'caballors', 'bicicletas', 'propietarios', 'chofers', 'cocineros', 'guias', 'traductors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->pagina === "transitos") {
            // lógica futura aquí
        } else {
            $reserva = Reserva::findOrFail($request->reserva_id);
            $reserva->estado = 4;
            $reserva->save();

            $gestion = Gestion::where('reserva_id', $reserva->id)->first();

            $resclis = Resercliente::where('reserva_id', $reserva->id)->get();

            // Mapeamos todos los turistas con sus campos
            $resclis_mapeados = $resclis->map(function ($t) {
                return [
                    'nombres'      => $t->nombres,
                    'apellidos'    => $t->apellidos,
                    'documento'    => $t->documento,
                    'nacionalidad' => $t->nacionalidad,
                    'edad'         => $t->edad,
                    'celular'      => $t->celular,
                    'correo'       => $t->correo,
                    'nota'         => $t->nota,
                    'nacionalidad' => $t->nacionalidad,


                    'tickets'      => $this->decodeField($t->tickets),
                    'habitaciones' => $this->decodeField($t->habitaciones),
                    'accesorios'   => $this->decodeField($t->accesorios),
                    'servicios'    => $this->decodeField($t->servicios),

                    'alergias'     => $this->decodeField($t->alergias),
                    'alimentacion' => $this->decodeField($t->alimentacion),
                ];
            });

            // Generar PDF
            $pdf = PDF::loadView('pdf.resumen_transito', [
                'reserva' => $reserva,
                'resclis' => $resclis_mapeados,
                'gestion' => $gestion,
            ]);

            $pdf->save(public_path("despachos/transito_{$reserva->codigo}.pdf"));

            return redirect('despachos/transitos/' . $reserva->id);
        }
    }


    private function decodeField($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::findOrFail($id);
        $gestion = Gestion::where('reserva_id', $id)->first();

        $resclis = Resercliente::where('reserva_id', $id)->get();

        // ✅ Mapeamos cada turista con sus valores decodificados
        $resclis = $resclis->map(function ($t) {
            $decode = fn($f) => is_string($f) ? json_decode($f, true) ?? [] : ($f ?? []);

            return (object) [
                'nombres'      => $t->nombres,
                'apellidos'    => $t->apellidos,
                'documento'    => $t->documento,
                'nacionalidad' => $t->nacionalidad,
                'edad'         => $t->edad,
                'celular'      => $t->celular,
                'correo'       => $t->correo,
                'nota'         => $t->nota,
                'esPrincipal'  => $t->esPrincipal,

                'tickets'      => $decode($t->tickets),
                'habitaciones' => $decode($t->habitaciones),
                'accesorios'   => $decode($t->accesorios),
                'servicios'    => $decode($t->servicios),
                'alergias'     => $decode($t->alergias),
                'alimentacion' => $decode($t->alimentacion),
            ];
        });

        return view('despachos.transitos.show', compact('reserva', 'resclis', 'gestion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

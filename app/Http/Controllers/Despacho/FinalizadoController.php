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
use App\Models\Servicio\Ticket;
use App\Models\Servicio\Turista;
use App\Models\Servicio\Accesorio;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;
use App\Models\Configuracion\Alergia;
use App\Models\Configuracion\Alimentacion;
use App\Models\Configuracion\Link;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;
use DB;
use App\Models\Despacho\Gestion;

class FinalizadoController extends Controller
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
        
        return view('despachos.finalizados.index', compact('reservas', 'tours', 'servicios', 'vagonetas', 'caballors', 'bicicletas', 'propietarios', 'chofers', 'cocineros', 'guias', 'traductors'));
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
        if($request->pagina == "finalizados"){
            
        }else{
            $res = Reserva::find($request->reserva_id);
            $res->estado = 5;
            $res->save();
    
            return redirect('despachos/finalizados/'.$request->reserva_id);
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::with('tour')->findOrFail($id);
        $gestion = Gestion::with([
            'guia', 'traductor', 'chofer', 'cocinero',
            'vagoneta', 'caballo', 'bicicleta',
            'provag', 'procab', 'probic', 'servicio'
        ])->where('reserva_id', $id)->first();

        $resclis = Resercliente::where('reserva_id', $id)->get();

        // Decodificación limpia
        $resclis = $resclis->map(function ($t) {
            $decode = fn($value) => is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []);

            return (object)[
                'id'           => $t->id,
                'nombres'      => $t->nombres,
                'apellidos'    => $t->apellidos,
                'documento'    => $t->documento,
                'nacionalidad' => $t->nacionalidad,
                'edad'         => $t->edad,
                'sexo'         => $t->sexo,
                'celular'      => $t->celular,
                'correo'       => $t->correo,
                'esPrincipal'  => $t->esPrincipal,
                'pre_per'      => $t->pre_per,
                'total'        => $t->total,
                'nota'         => $t->nota,
                'estado'       => $t->estado,
                'estatus'      => $t->estatus,

                'tickets'      => $decode($t->tickets),
                'habitaciones' => $decode($t->habitaciones),
                'accesorios'   => $decode($t->accesorios),
                'servicios'    => $decode($t->servicios),
                'alergias'     => $decode($t->alergias),
                'alimentacion' => $decode($t->alimentacion),
            ];
        });

        return view('despachos.finalizados.show', compact('reserva', 'gestion', 'resclis'));
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

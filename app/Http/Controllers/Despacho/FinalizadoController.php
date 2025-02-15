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
        $reserva = Reserva::find($id);
        $resclis = Resercliente::where('reserva_id', $id)->get(); // Filtrar Resercliente por reserva_id
        $gestion = Gestion::where('reserva_id', $id)->first();
        $tours = Tour::all();
        $hottus = HotelTour::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        $guias = Guia::all();
        $traductors = Traductor::all();
        $chofers = Chofer::all();
        $cocineros = Cocinero::all();
        $propietarios = Propietario::all();
        $vagonetas = Vagoneta::all();
        $bicicletas = Bicicleta::all();
        $caballos = Caballo::all();
        
        return view('despachos.finalizados.show', compact('gestion', 'caballos', 'bicicletas', 'vagonetas', 'propietarios', 'cocineros', 'chofers', 'traductors', 'guias', 'resclis', 'reserva', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'hottus', 'categorias', 'servicios'));
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

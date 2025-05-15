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
            // ... posible lógica futura
        } else {
            $reserva = Reserva::find($request->reserva_id);
            $reserva->estado = 4;
            $reserva->save();

            $cliente = Resercliente::where('reserva_id', $reserva->id)
                ->where('esPrincipal', true)
                ->first();

            $resclis = Resercliente::where('reserva_id', $reserva->id)->get();
            $gestion = Gestion::where('reserva_id', $reserva->id)->first();

            // Decodificación segura
            $habitaciones = is_string($cliente->habitaciones) ? json_decode($cliente->habitaciones, true) : ($cliente->habitaciones ?? []);
            $tickets = is_string($cliente->tickets) ? json_decode($cliente->tickets, true) : ($cliente->tickets ?? []);
            $accesorios = is_string($cliente->accesorios) ? json_decode($cliente->accesorios, true) : ($cliente->accesorios ?? []);
            $servicios = is_string($cliente->servicios) ? json_decode($cliente->servicios, true) : ($cliente->servicios ?? []);

            $alergiasIds = is_string($cliente->alergias) ? json_decode($cliente->alergias, true) : ($cliente->alergias ?? []);
            $alimentosIds = is_string($cliente->alimentacion) ? json_decode($cliente->alimentacion, true) : ($cliente->alimentacion ?? []);

            $alergiasIds = $cliente->alergias;

            if (is_string($alergiasIds)) {
                $alergiasIds = json_decode($alergiasIds, true);
            }

            $alergias = Alergia::whereIn('id', is_array($alergiasIds) ? $alergiasIds : [])->pluck('titulo');

            $alimentosIds = $cliente->alimentacion;

            if (is_string($alimentosIds)) {
                $alimentosIds = json_decode($alimentosIds, true);
            }

            $alimentos = Alimentacion::whereIn('id', is_array($alimentosIds) ? $alimentosIds : [])->pluck('titulo');

            // Generar PDF
            $pdf = PDF::loadView('pdf.resumen_transito', compact(
                'reserva',
                'cliente',
                'resclis',
                'gestion',
                'habitaciones',
                'tickets',
                'accesorios',
                'servicios',
                'alergias',
                'alimentos'
            ));

            $pdf->save(public_path("despachos/transito_{$reserva->codigo}.pdf"));

            return redirect('despachos/transitos/' . $reserva->id);
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
        
        return view('despachos.transitos.show', compact('gestion', 'caballos', 'bicicletas', 'vagonetas', 'propietarios', 'cocineros', 'chofers', 'traductors', 'guias', 'resclis', 'reserva', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'hottus', 'categorias', 'servicios'));
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

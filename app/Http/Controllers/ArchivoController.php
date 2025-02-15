<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour\HotelTour;
use App\Models\Tour\Categoria;
use App\Models\Servicio;
use App\Models\Servicio\Ticket;
use App\Models\Servicio\Turista;
use App\Models\Servicio\Accesorio;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;
use App\Models\Configuracion\Alergia;
use App\Models\Configuracion\Alimentacion;
use App\Models\Configuracion\Link;
use DB;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;

class ArchivoController extends Controller
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tour = Tour::find($id);
        
        // Obtener los IDs directamente desde JSON y asegurarse de que son arreglos
        $ticket_ids = json_decode($tour->tickets, true) ?? [];
        $accesorio_ids = json_decode($tour->accesorios, true) ?? [];
        $turista_ids = json_decode($tour->turistas, true) ?? [];
        $hotel_ids = array_merge(...json_decode($tour->hoteles, true) ?? []);

        // Filtrar solo los elementos necesarios
        $tickets = Ticket::whereIn('id', $ticket_ids)->get();
        $accesorios = Accesorio::whereIn('id', $accesorio_ids)->get();
        $turistas = Turista::whereIn('id', $turista_ids)->get();
        $hoteles = Hotel::whereIn('id', $hotel_ids)->with('habitaciones')->get();

        // Obtener datos adicionales
        $servicios = Servicio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        
        return view('archivos.edit', compact('links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tour', 'hoteles', 'servicios', 'tickets', 'turistas', 'accesorios'));
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
    public function destroy(string $id)
    {
        //
    }
}

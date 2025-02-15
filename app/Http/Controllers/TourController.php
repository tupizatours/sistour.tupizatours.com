<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Tour\HotelTour;
use App\Models\Tour\Categoria;
use App\Models\Servicio;
use App\Models\Servicio\Ticket;
use App\Models\Servicio\Turista;
use App\Models\Servicio\Accesorio;
use App\Models\Servicio\Hotel;
use DB;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tour::all();
        $hottus = HotelTour::all();
        $hoteles = Hotel::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $tickets = Ticket::all();
        $turistas = Turista::all();
        $accesorios = Accesorio::all();
        
        return view('tours.index', compact('tours', 'hottus', 'hoteles', 'categorias', 'servicios', 'tickets', 'turistas', 'accesorios'));
    }

    public function filtrarPorTipo(Request $request)
    {
        $tipo = $request->input('tipo');
        $hoteles = DB::table('hoteles')
                    ->where('tipo', $tipo)
                    ->where('estatus', 1) // Filtra por estatus 1
                    ->get();

        return response()->json($hoteles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $tours = Tour::all();
        $servicios = Servicio::all();
        $tickets = Ticket::all();
        $turistas = Turista::all();
        $accesorios = Accesorio::all();
        
        return view('tours.create', compact('tours', 'categorias', 'servicios', 'tickets', 'turistas', 'accesorios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $serv_tour = json_encode($request->serv_tour);
        //$serv_cli = json_encode($request->serv_cli);
        $tickets = json_encode($request->tickets);
        $hoteles = json_encode($request->hoteles);
        $accesorios = json_encode($request->accesorios);
        $turistas = json_encode($request->turistas);
        
        $in = [];
        $in['codigo'] = $request->string('codigo');
        $in['titulo'] = $request->string('titulo');
        $in['descripcion'] = $request->string('descripcion');
        $in['categoria_id'] = $request->string('categoria_id');
        $in['duracion'] = $request->string('duracion');
        $in['noches'] = $request->string('noches');
        $in['min_per'] = $request->string('min_per');
        $in['max_per'] = $request->string('max_per');
        $in['hor_lim'] = $request->string('hor_lim');
        $in['tipo'] = $request->string('tipo');
        $in['serv_tour'] = $serv_tour;
        $in['serv_cli'] = $request->string('serv_cli');
        $in['pre_uni'] = $request->string('pre_uni');
        $in['pre_tot'] = $request->string('pre_tot');
        $in['tickets'] = $tickets;
        $in['hoteles'] = $hoteles;
        $in['accesorios'] = $accesorios;
        $in['turistas'] = $turistas;
        $in['estatus'] = $request->string('estatus');
        $store = Tour::create($in);

        $validated = $request->validate([
            'hoteles' => 'required|array',
            'hoteles.*' => 'array', // Cada noche tiene un array de hoteles seleccionados
            'dias' => 'required|array',
        ]);

        foreach ($request->dias as $index => $dia) {
            if (isset($request->hoteles[$index + 1])) { // +1 porque los índices de los arrays comienzan en 0, pero los días en 1
                foreach ($request->hoteles[$index + 1] as $hotelId) {
                    DB::table('hotel_tour')->insert([
                        'tour_id' => $store->id,
                        'hotel_id' => $hotelId,
                        'dia' => $dia,
                    ]);
                }
            }
        }

        return back()->with('success','Tour agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tour = Tour::find($id);
        
        return view('tours.show', compact('tour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tour = Tour::find($id);
        
        return view('tours.edit', compact('tour'));
    }

    public function eliminados(Request $request)
    {
        $categorias = Categoria::all();
        $tours = Tour::all();
        $servicios = Servicio::all();
        $tickets = Ticket::all();
        $turistas = Turista::all();
        $accesorios = Accesorio::all();
        
        return view('tours.eliminados', compact('tours', 'categorias', 'servicios', 'tickets', 'turistas', 'accesorios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $serv_tour = json_encode($request->serv_tour);
        //$serv_cli = json_encode($request->serv_cli);
        $tickets = json_encode($request->tickets);
        $hoteles = json_encode($request->hoteles);
        $accesorios = json_encode($request->accesorios);
        $turistas = json_encode($request->turistas);
        
        $in = [];
        $in['codigo'] = $request->string('codigo');
        $in['titulo'] = $request->string('titulo');
        $in['descripcion'] = $request->string('descripcion');
        $in['categoria_id'] = $request->string('categoria_id');
        $in['duracion'] = $request->string('duracion');
        $in['noches'] = $request->string('noches');
        $in['min_per'] = $request->string('min_per');
        $in['max_per'] = $request->string('max_per');
        $in['hor_lim'] = $request->string('hor_lim');
        $in['tipo'] = $request->string('tipo');
        $in['serv_tour'] = $serv_tour;
        $in['serv_cli'] = $request->string('serv_cli');
        $in['pre_uni'] = $request->string('pre_uni');
        $in['pre_tot'] = $request->string('pre_tot');
        $in['tickets'] = $tickets;
        $in['hoteles'] = $hoteles;
        $in['accesorios'] = $accesorios;
        $in['turistas'] = $turistas;
        $in['estatus'] = $request->string('estatus');

        $tour = Tour::find($id);
        $tour->update($in);

        return back()->with('success','Tour actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Tour::destroy($id);

        return redirect()->route('tours.index')->with('success','Tour eliminado');
    }
}

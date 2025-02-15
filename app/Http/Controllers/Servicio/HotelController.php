<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hoteles = Hotel::withCount('habitaciones')->get();
        $habitaciones = Habitacion::all();
        
        return view('servicios.hoteles.index', compact('hoteles', 'habitaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();
        
        return view('servicios.hoteles.create', compact('hoteles', 'habitaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hoteles = $request->all();
        Hotel::create($hoteles);

        return back()->with('success','Hotel agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hotel = Hotel::find($id);
        $habitaciones = Habitacion::all();
        
        return view('servicios.hoteles.show', compact('hotel', 'habitaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hotel = Hotel::find($id);
        $habitaciones = Habitacion::all();
        
        return view('servicios.hoteles.edit', compact('hotel', 'habitaciones'));
    }

    public function eliminados(Request $request)
    {
        $hoteles = Hotel::withCount('habitaciones')->get();
        $habitaciones = Habitacion::all();
        
        return view('servicios.hoteles.eliminados', compact('hoteles', 'habitaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $hot = Hotel::find($id);

        $hot->titulo               = $request->titulo;
        $hot->tipo                 = $request->tipo;
        $hot->estado               = $request->estado;
        $hot->estatus              = $request->estatus;
        $hot->save();

        return back()->with('success','Hotel actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Hotel::destroy($id);

        return redirect()->route('servhoteles.index')->with('success','Hotel eliminado');
    }
}

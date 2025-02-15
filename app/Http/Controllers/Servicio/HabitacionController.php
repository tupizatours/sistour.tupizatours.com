<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();
        
        return view('servicios.habitaciones.index', compact('habitaciones', 'hoteles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();
        
        return view('servicios.habitaciones.create', compact('habitaciones', 'hoteles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $habitaciones = $request->all();
        Habitacion::create($habitaciones);

        return back()->with('success','Habitación agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hoteles = Hotel::all();
        $habitacion = Habitacion::find($id);
        
        return view('servicios.habitaciones.show', compact('habitacion', 'hoteles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hoteles = Hotel::all();
        $habitacion = Habitacion::find($id);
        
        return view('servicios.habitaciones.edit', compact('habitacion', 'hoteles'));
    }

    public function eliminados(Request $request)
    {
        $habitaciones = Habitacion::all();
        $hoteles = Hotel::all();
        
        return view('servicios.habitaciones.eliminados', compact('habitaciones', 'hoteles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tick = Habitacion::find($id);

        $tick->hotel_id             = $request->hotel_id;
        $tick->titulo               = $request->titulo;
        $tick->costo                = $request->costo;
        $tick->cos_ext              = $request->cos_ext;
        $tick->nacionales           = $request->nacionales;
        $tick->extranjeros          = $request->extranjeros;
        $tick->estatus              = $request->estatus;
        $tick->save();

        return back()->with('success','Habitación actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Habitacion::destroy($id);

        return redirect()->route('servhabitaciones.index')->with('success','Habitación eliminada');
    }
}

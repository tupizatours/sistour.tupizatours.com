<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Turista;

class TuristaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turistas = Turista::all();
        
        return view('servicios.turistas.index', compact('turistas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turistas = Turista::all();
        
        return view('servicios.turistas.create', compact('turistas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $turistas = $request->all();
        Turista::create($turistas);

        return back()->with('success','Turista agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $turista = Turista::find($id);
        
        return view('servicios.turistas.show', compact('turista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $turista = Turista::find($id);
        
        return view('servicios.turistas.edit', compact('turista'));
    }

    public function eliminados(Request $request)
    {
        $turistas = Turista::all();
        
        return view('servicios.turistas.eliminados', compact('turistas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tur = Turista::find($id);

        $tur->titulo               = $request->titulo;
        $tur->costo                = $request->costo;
        $tur->venta                = $request->venta;
        $tur->estatus              = $request->estatus;
        $tur->save();

        return back()->with('success','Turista actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Turista::destroy($id);

        return redirect()->route('servturistas.index')->with('success','Turista eliminado');
    }
}

<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Caballo;
use App\Models\Propietario;

class CaballoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propietarios = Propietario::all();
        $caballos = Caballo::all();
        
        return view('servicios.caballos.index', compact('caballos', 'propietarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propietarios = Propietario::all();
        $caballos = Caballo::all();
        
        return view('servicios.caballos.create', compact('caballos', 'propietarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $caballos = $request->all();
        Caballo::create($caballos);

        return back()->with('success','Caballo agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $propietarios = Propietario::all();
        $caballo = Caballo::find($id);
        
        return view('servicios.caballos.show', compact('caballo', 'propietarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $propietarios = Propietario::all();
        $caballo = Caballo::find($id);
        
        return view('servicios.caballos.edit', compact('caballo', 'propietarios'));
    }

    public function eliminados(Request $request)
    {
        $caballos = Caballo::all();
        $propietarios = Propietario::all();
        
        return view('servicios.caballos.eliminados', compact('caballos', 'propietarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cab = Caballo::find($id);

        $cab->propietario_id       = $request->propietario_id;
        $cab->nombre               = $request->nombre;
        $cab->costo                = $request->costo;
        $cab->venta                = $request->venta;
        $cab->estatus              = $request->estatus;
        $cab->save();

        return back()->with('success','Caballo actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Caballo::destroy($id);

        return redirect()->route('servcaballos.index')->with('success','Caballo eliminado');
    }
}

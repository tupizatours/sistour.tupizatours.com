<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Vagoneta;
use App\Models\Propietario;

class VagonetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propietarios = Propietario::all();
        $vagonetas = Vagoneta::all();
        
        return view('servicios.vagonetas.index', compact('vagonetas', 'propietarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propietarios = Propietario::all();
        $vagonetas = Vagoneta::all();
        
        return view('servicios.vagonetas.create', compact('vagonetas', 'propietarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vagonetas = $request->all();
        Vagoneta::create($vagonetas);

        return back()->with('success','Vagoneta agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $propietarios = Propietario::all();
        $vagoneta = Vagoneta::find($id);
        
        return view('servicios.vagonetas.show', compact('vagoneta', 'propietarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $propietarios = Propietario::all();
        $vagoneta = Vagoneta::find($id);
        
        return view('servicios.vagonetas.edit', compact('vagoneta', 'propietarios'));
    }

    public function eliminados(Request $request)
    {
        $vagonetas = Vagoneta::all();
        $propietarios = Propietario::all();
        
        return view('servicios.vagonetas.eliminados', compact('vagonetas', 'propietarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vag = Vagoneta::find($id);

        $vag->propietario_id       = $request->propietario_id;
        $vag->marca                = $request->marca;
        $vag->placa                = $request->placa;
        $vag->color                = $request->color;
        $vag->modelo               = $request->modelo;
        $vag->costo                = $request->costo;
        $vag->venta                = $request->venta;
        $vag->estatus              = $request->estatus;
        $vag->save();

        return back()->with('success','Vagoneta actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Vagoneta::destroy($id);

        return redirect()->route('servvagonetas.index')->with('success','Vagoneta eliminada');
    }
}

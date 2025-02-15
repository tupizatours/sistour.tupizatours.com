<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Moneda;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monedas = Moneda::all();
        
        return view('configuracion.monedas.index', compact('monedas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $monedas = Moneda::all();
        
        return view('configuracion.monedas.create', compact('monedas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $monedas = $request->all();
        Moneda::create($monedas);

        return back()->with('success','Moneda agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $moneda = Moneda::find($id);
        
        return view('configuracion.monedas.show', compact('moneda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $moneda = Moneda::find($id);
        
        return view('configuracion.monedas.edit', compact('moneda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $moneda = Moneda::find($id);

        $moneda->nombre         = $request->nombre;
        $moneda->codigo         = $request->codigo;
        $moneda->simbolo        = $request->simbolo;
        $moneda->estatus        = $request->estatus;
        $moneda->save();

        return redirect()->route('confmonedas.index')->with('success','Moneda actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Moneda::destroy($id);

        return redirect()->route('confmonedas.index')->with('success','Moneda eliminada');
    }
}

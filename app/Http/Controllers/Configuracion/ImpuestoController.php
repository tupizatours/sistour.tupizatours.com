<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Impuesto;

class ImpuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $impuestos = Impuesto::all();
        
        return view('configuracion.impuestos.index', compact('impuestos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $impuestos = Impuesto::all();
        
        return view('configuracion.impuestos.create', compact('impuestos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $impuestos = $request->all();
        Impuesto::create($impuestos);

        return back()->with('success','Impuesto agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $impuesto = Impuesto::find($id);
        
        return view('configuracion.impuestos.show', compact('impuesto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $impuesto = Impuesto::find($id);
        
        return view('configuracion.impuestos.edit', compact('impuesto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $impuesto = Impuesto::find($id);

        $impuesto->nombre          = $request->nombre;
        $impuesto->porcentaje      = $request->porcentaje;
        $impuesto->estatus         = $request->estatus;
        $impuesto->save();

        return redirect()->route('confimpuestos.index')->with('success','Impuesto actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Impuesto::destroy($id);

        return redirect()->route('confimpuestos.index')->with('success','Impuesto eliminado');
    }
}

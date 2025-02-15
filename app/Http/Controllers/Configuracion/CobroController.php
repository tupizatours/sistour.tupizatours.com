<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Cobro;

class CobroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cobros = Cobro::all();
        
        return view('configuracion.cobros.index', compact('cobros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cobros = Cobro::all();
        
        return view('configuracion.cobros.create', compact('cobros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cobros = $request->all();
        Cobro::create($cobros);

        return back()->with('success','Cobro agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cobro = Cobro::find($id);
        
        return view('configuracion.cobros.show', compact('cobro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cobro = Cobro::find($id);
        
        return view('configuracion.cobros.edit', compact('cobro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cob = Cobro::find($id);

        $alergia->titulo        = $request->titulo;
        $alergia->tipo          = $request->tipo;
        $alergia->comision      = $request->comision;
        $alergia->deposito      = $request->deposito;
        $alergia->estatus       = $request->estatus;
        $alergia->save();

        return redirect()->route('confcobros.index')->with('success','Cobro actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cobro::destroy($id);

        return redirect()->route('confcobros.index')->with('success','Cobro eliminado');
    }
}

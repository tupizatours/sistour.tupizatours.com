<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Alimentacion;

class AlimentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alimentos = Alimentacion::all();
        
        return view('configuracion.alimentacion.index', compact('alimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alimentos = Alimentacion::all();
        
        return view('configuracion.alimentacion.create', compact('alimentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alimentos = $request->all();
        Alimentacion::create($alimentos);

        return back()->with('success','Tipo de alimentación agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $alimento = Alimentacion::find($id);
        
        return view('configuracion.alimentacion.show', compact('alimento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $alimento = Alimentacion::find($id);
        
        return view('configuracion.alimentacion.edit', compact('alimento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alimento = Alimentacion::find($id);

        $alimento->titulo        = $request->titulo;
        $alimento->estatus       = $request->estatus;
        $alimento->save();

        return redirect()->route('confalimentacion.index')->with('success','Tipo de alimentación actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Alimentacion::destroy($id);

        return redirect()->route('confalimentacion.index')->with('success','Tipo de alimentación eliminada');
    }
}

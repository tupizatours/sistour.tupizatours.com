<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Idioma;

class IdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $idiomas = Idioma::all();
        
        return view('configuracion.idiomas.index', compact('idiomas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $idiomas = Idioma::all();
        
        return view('configuracion.idiomas.create', compact('idiomas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idiomas = $request->all();
        Idioma::create($idiomas);

        return back()->with('success','Idioma agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $idioma = Idioma::find($id);
        
        return view('configuracion.idiomas.show', compact('idioma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $idioma = Idioma::find($id);
        
        return view('configuracion.idiomas.edit', compact('idioma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $idioma = Idioma::find($id);

        $idioma->codigo        = $request->codigo;
        $idioma->titulo        = $request->titulo;
        $idioma->estatus       = $request->estatus;
        $idioma->save();

        return redirect()->route('confidiomas.index')->with('success','Idioma actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Idioma::destroy($id);

        return redirect()->route('confidiomas.index')->with('success','Idioma eliminado');
    }
}

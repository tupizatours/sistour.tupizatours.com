<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Alergia;

class AlergiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alergias = Alergia::all();
        
        return view('configuracion.alergias.index', compact('alergias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alergias = Alergia::all();
        
        return view('configuracion.alergias.create', compact('alergias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alergias = $request->all();
        Alergia::create($alergias);

        return back()->with('success','Alergia agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $alergia = Alergia::find($id);
        
        return view('configuracion.alergias.show', compact('alergia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $alergia = Alergia::find($id);
        
        return view('configuracion.alergias.edit', compact('alergia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alergia = Alergia::find($id);

        $alergia->titulo        = $request->titulo;
        $alergia->estatus       = $request->estatus;
        $alergia->save();

        return redirect()->route('confalergias.index')->with('success','Alergia actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Alergia::destroy($id);

        return redirect()->route('confalergias.index')->with('success','Alergia eliminada');
    }
}

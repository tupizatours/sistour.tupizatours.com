<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        
        return view('configuracion.bancos.index', compact('bancos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        
        return view('configuracion.bancos.create', compact('bancos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bancos = $request->all();
        Banco::create($bancos);

        return back()->with('success','Banco agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banco = Banco::find($id);
        
        return view('configuracion.bancos.show', compact('banco'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banco = Banco::find($id);
        
        return view('configuracion.bancos.edit', compact('banco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $banco = Banco::find($id);

        $banco->titulo          = $request->titulo;
        $banco->descripcion     = $request->descripcion;
        $banco->estatus         = $request->estatus;
        $banco->save();

        return redirect()->route('confbancos.index')->with('success','Banco actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Banco::destroy($id);

        return redirect()->route('confbancos.index')->with('success','Banco eliminado');
    }
}

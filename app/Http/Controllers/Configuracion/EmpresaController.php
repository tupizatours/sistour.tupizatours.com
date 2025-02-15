<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Empresa;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::all();
        
        return view('configuracion.empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::all();
        
        return view('configuracion.empresas.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $empresas = $request->all();
        Empresa::create($empresas);

        return back()->with('success','Empresa agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);
        
        return view('configuracion.empresas.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $empresa = Empresa::find($id);
        
        return view('configuracion.empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);

        $empresa->nombre            = $request->nombre;
        $empresa->direccion         = $request->direccion;
        $empresa->telefono          = $request->telefono;
        $empresa->correo            = $request->correo;
        $empresa->web               = $request->web;
        $empresa->nit               = $request->nit;
        $empresa->estatus           = $request->estatus;
        $empresa->save();

        return redirect()->route('confempresas.index')->with('success','Empresa actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Empresa::destroy($id);

        return redirect()->route('confempresas.index')->with('success','Empresa eliminada');
    }
}

<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Online;

class OnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $onlines = Online::all();
        
        return view('configuracion.onlines.index', compact('onlines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $onlines = Online::all();
        
        return view('configuracion.onlines.create', compact('onlines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $onlines = $request->all();
        Online::create($onlines);

        return back()->with('success','Pago online agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $online = Online::find($id);
        
        return view('configuracion.onlines.show', compact('online'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $online = Online::find($id);
        
        return view('configuracion.onlines.edit', compact('online'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $onl = Online::find($id);

        $onl->nombre          = $request->nombre;
        $onl->descripcion     = $request->descripcion;
        $onl->estatus         = $request->estatus;
        $onl->save();

        return redirect()->route('confonlines.index')->with('success','Pago online actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Online::destroy($id);

        return redirect()->route('confonlines.index')->with('success','Pago online eliminado');
    }
}

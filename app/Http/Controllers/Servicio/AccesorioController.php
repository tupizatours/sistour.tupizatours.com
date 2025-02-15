<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Accesorio;

class AccesorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accesorios = Accesorio::all();
        
        return view('servicios.accesorios.index', compact('accesorios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accesorios = Accesorio::all();
        
        return view('servicios.accesorios.create', compact('accesorios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $accesorios = $request->all();
        Accesorio::create($accesorios);

        return back()->with('success','Accesorio agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $accesorio = Accesorio::find($id);
        
        return view('servicios.accesorios.show', compact('accesorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $accesorio = Accesorio::find($id);
        
        return view('servicios.accesorios.edit', compact('accesorio'));
    }

    public function eliminados(Request $request)
    {
        $accesorios = Accesorio::all();
        
        return view('servicios.accesorios.eliminados', compact('accesorios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $acce = Accesorio::find($id);

        $acce->titulo               = $request->titulo;
        $acce->costo                = $request->costo;
        $acce->venta                = $request->venta;
        $acce->estatus              = $request->estatus;
        $acce->save();

        return back()->with('success','Accesorio actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Accesorio::destroy($id);

        return redirect()->route('servtickets.index')->with('success','Accesorio eliminado');
    }
}

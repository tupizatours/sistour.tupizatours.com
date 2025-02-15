<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::all();
        
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turistas = Servicio::all();
        
        return view('servicios.create', compact('servicios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Servicio::create([
            'titulo'                => $request->titulo,
            'costo'                 => $request->costo,
            'venta'                 => $request->venta,
            'estatus'               => $request->estatus,
        ]);

        return back()->with('success','Servicio agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $servicio = Servicio::find($id);
        
        return view('servicios.show', compact('servicio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $servicio = Servicio::find($id);
        
        return view('servicios.edit', compact('servicio'));
    }

    public function eliminados(Request $request)
    {
        $servicios = Servicio::all();
        
        return view('servicios.eliminados', compact('servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ser = Servicio::find($id);

        $ser->titulo                = $request->titulo;
        $ser->costo                 = $request->costo;
        $ser->venta                 = $request->venta;
        $ser->estatus               = $request->estatus;
        $ser->save();

        return back()->with('success','Servicio actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Servicio::destroy($id);

        return redirect()->route('servicios.index')->with('success','Servicio eliminado');
    }
}

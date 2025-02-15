<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Bicicleta;
use App\Models\Propietario;

class BicicletaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propietarios = Propietario::all();
        $bicicletas = Bicicleta::all();
        
        return view('servicios.bicicletas.index', compact('bicicletas', 'propietarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propietarios = Propietario::all();
        $bicicletas = Bicicleta::all();
        
        return view('servicios.bicicletas.create', compact('bicicletas', 'propietarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bicicletas = $request->all();
        Bicicleta::create($bicicletas);

        return back()->with('success','Bicicleta agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $propietarios = Propietario::all();
        $bicicleta = Bicicleta::find($id);
        
        return view('servicios.bicicletas.show', compact('bicicleta', 'propietarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $propietarios = Propietario::all();
        $bicicleta = Bicicleta::find($id);
        
        return view('servicios.bicicletas.edit', compact('bicicleta', 'propietarios'));
    }

    public function eliminados(Request $request)
    {
        $bicicletas = Bicicleta::all();
        $propietarios = Propietario::all();
        
        return view('servicios.bicicletas.eliminados', compact('bicicletas', 'propietarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bic = Bicicleta::find($id);

        $bic->propietario_id       = $request->propietario_id;
        $bic->nombre               = $request->nombre;
        $bic->costo                = $request->costo;
        $bic->venta                = $request->venta;
        $bic->estatus              = $request->estatus;
        $bic->save();

        return back()->with('success','Bicicleta actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Bicicleta::destroy($id);

        return redirect()->route('servbicicletas.index')->with('success','Bicicleta eliminada');
    }
}

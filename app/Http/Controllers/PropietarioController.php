<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Propietario;
use Image;

class PropietarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $propietarios = Propietario::all();
        
        return view('propietarios.index', compact('bancos', 'idiomas', 'propietarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $propietarios = Propietario::all();
        
        return view('propietarios.create', compact('bancos', 'idiomas', 'propietarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $in = [];
        $in['nombre']               = $request->nombre;
        $in['apellido']             = $request->apellido;
        $in['cedula']               = $request->cedula;
        $in['licencia']             = $request->licencia;
        $in['numero']               = $request->numero;
        $in['correo']               = $request->correo;
        $in['celular']              = $request->celular;
        $in['cuenta']               = $request->cuenta;
        $in['bancos_id']            = $request->bancos_id;
        $in['referencia']           = $request->referencia;
        $in['celref']               = $request->celref;
        $in['observaciones']        = $request->observaciones;
        $in['estatus']              = $request->estatus;

        $store = Propietario::create($in);

        return back()->with('success','Propietario agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $propietario = Propietario::find($id);
        
        return view('propietarios.show', compact('propietario', 'bancos', 'idiomas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $propietario = Propietario::find($id);
        
        return view('propietarios.edit', compact('propietario'));
    }

    public function eliminados(Request $request)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $propietarios = Propietario::all();
        
        return view('propietarios.eliminados', compact('bancos', 'idiomas', 'propietarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $prop = Propietario::find($id);

        if($request->foto == 1){
            $prop->nombre               = $request->nombre;
            $prop->apellido             = $request->apellido;
            $prop->cedula               = $request->cedula;
            $prop->licencia             = $request->licencia;
            $prop->numero               = $request->numero;
            $prop->correo               = $request->correo;
            $prop->celular              = $request->celular;
            $prop->cuenta               = $request->cuenta;
            $prop->bancos_id            = $request->bancos_id;
            $prop->referencia           = $request->referencia;
            $prop->celref               = $request->celref;
            $prop->observaciones        = $request->observaciones;
            $prop->estatus              = $request->estatus;
            $prop->save();
        }elseif($request->foto == 2){
            $request->validate([
                'file'  => 'required|image|mimes:jpeg,png,jpg'
            ]);

            if($imagen = $request->File('file')) {
                $rutaGuardarmg = 'panelpropietarios';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoProp = "$nombreOriginal";
            }

            $prop->file = $fotoProp;
            $prop->save();
        }

        return back()->with('success','Propietario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Propietario::destroy($id);

        return redirect()->route('propietarios.index')->with('success','Propietario eliminado');
    }
}

<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Propietario\Cocinero;
use Image;

class CocineroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $cocineros = Cocinero::all();
        
        return view('propietarios.cocineros.index', compact('bancos', 'idiomas', 'cocineros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $cocineros = Cocinero::all();
        
        return view('propietarios.cocineros.create', compact('bancos', 'idiomas', 'cocineros'));
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
        $in['correo']               = $request->correo;
        $in['celular']              = $request->celular;
        $in['tarifa']               = $request->tarifa;
        $in['cuenta']               = $request->cuenta;
        $in['bancos_id']            = $request->bancos_id;
        $in['referencia']           = $request->referencia;
        $in['celref']               = $request->celref;
        $in['observaciones']        = $request->observaciones;
        $in['estatus']              = $request->estatus;

        $store = Cocinero::create($in);

        return back()->with('success','Cocinero agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $cocinero = Cocinero::find($id);
        
        return view('propietarios.cocineros.show', compact('cocinero', 'bancos', 'idiomas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cocinero = Cocinero::find($id);
        
        return view('propietarios.cocineros.edit', compact('cocinero'));
    }

    public function eliminados(Request $request)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $cocineros = Cocinero::all();
        
        return view('propietarios.cocineros.eliminados', compact('bancos', 'idiomas', 'cocineros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coci = Cocinero::find($id);

        if($request->foto == 1){
            $coci->nombre               = $request->nombre;
            $coci->apellido             = $request->apellido;
            $coci->tipo                 = $request->tipo;
            $coci->cedula               = $request->cedula;
            $coci->acreditacion         = $request->acreditacion;
            $coci->correo               = $request->correo;
            $coci->celular              = $request->celular;
            $coci->idiomas_id           = $idiomas_id;
            $coci->tarifa               = $request->tarifa;
            $coci->cuenta               = $request->cuenta;
            $coci->bancos_id            = $request->bancos_id;
            $coci->referencia           = $request->referencia;
            $coci->celref               = $request->celref;
            $coci->observaciones        = $request->observaciones;
            $coci->estatus              = $request->estatus;
            $coci->save();
        }elseif($request->foto == 2){
            $request->validate([
                'file'  => 'required|image|mimes:jpeg,png,jpg'
            ]);

            if($imagen = $request->File('file')) {
                $rutaGuardarmg = 'panelcocineros';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoCoci = "$nombreOriginal";
            }

            $coci->file = $fotoCoci;
            $coci->save();
        }

        return back()->with('success','Cocinero actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cocinero::destroy($id);

        return redirect()->route('prococineros.index')->with('success','Cocinero eliminado');
    }
}

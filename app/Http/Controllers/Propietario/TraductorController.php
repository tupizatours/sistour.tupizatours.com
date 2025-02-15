<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Propietario\Traductor;
use Image;

class TraductorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $traductores = Traductor::all();
        
        return view('propietarios.traductores.index', compact('bancos', 'idiomas', 'traductores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $traductores = Traductor::all();
        
        return view('propietarios.traductores.create', compact('bancos', 'idiomas', 'traductores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idiomas_id = json_encode($request->idiomas_id);

        $in = [];
        $in['nombre']               = $request->nombre;
        $in['apellido']             = $request->apellido;
        $in['cedula']               = $request->cedula;
        $in['acreditacion']         = $request->acreditacion;
        $in['correo']               = $request->correo;
        $in['celular']              = $request->celular;
        $in['idiomas_id']           = $idiomas_id;
        $in['tarifa']               = $request->tarifa;
        $in['cuenta']               = $request->cuenta;
        $in['bancos_id']            = $request->bancos_id;
        $in['referencia']           = $request->referencia;
        $in['celref']               = $request->celref;
        $in['observaciones']        = $request->observaciones;
        $in['estatus']              = $request->estatus;

        $store = Traductor::create($in);

        return back()->with('success','Traductor agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $traductor = Traductor::find($id);
        
        return view('propietarios.traductores.show', compact('traductor', 'bancos', 'idiomas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $traductor = Traductor::find($id);
        
        return view('propietarios.traductores.edit', compact('traductor'));
    }

    public function eliminados(Request $request)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $traductores = Traductor::all();
        
        return view('propietarios.traductores.eliminados', compact('bancos', 'idiomas', 'traductores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guia = Traductor::find($id);

        if($request->foto == 1){
            $idiomas_id = json_encode($request->idiomas_id);

            $guia->nombre               = $request->nombre;
            $guia->apellido             = $request->apellido;
            $guia->cedula               = $request->cedula;
            $guia->acreditacion         = $request->acreditacion;
            $guia->correo               = $request->correo;
            $guia->celular              = $request->celular;
            $guia->idiomas_id           = $idiomas_id;
            $guia->tarifa               = $request->tarifa;
            $guia->cuenta               = $request->cuenta;
            $guia->bancos_id            = $request->bancos_id;
            $guia->referencia           = $request->referencia;
            $guia->celref               = $request->celref;
            $guia->observaciones        = $request->observaciones;
            $guia->estatus              = $request->estatus;
            $guia->save();
        }elseif($request->foto == 2){
            $request->validate([
                'file'  => 'required|image|mimes:jpeg,png,jpg'
            ]);

            if($imagen = $request->File('file')) {
                $rutaGuardarmg = 'paneltraductores';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoGuia = "$nombreOriginal";
            }

            $guia->file = $fotoGuia;
            $guia->save();
        }

        return back()->with('success','Traductor actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Traductor::destroy($id);

        return redirect()->route('proguias.index')->with('success','Traductor eliminado');
    }
}

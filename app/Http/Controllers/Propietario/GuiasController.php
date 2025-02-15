<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Propietario\Guia;
use Image;

class GuiasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $guias = Guia::all();
        
        return view('propietarios.guias.index', compact('bancos', 'idiomas', 'guias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $guias = Guia::all();
        
        return view('propietarios.guias.create', compact('bancos', 'idiomas', 'guias'));
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
        $in['tipo']                 = $request->tipo;
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

        $store = Guia::create($in);

        return back()->with('success','Guia agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $guia = Guia::find($id);
        
        return view('propietarios.guias.show', compact('guia', 'bancos', 'idiomas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $guia = Guia::find($id);
        
        return view('propietarios.guias.edit', compact('guia'));
    }

    public function eliminados(Request $request)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $guias = Guia::all();
        
        return view('propietarios.guias.eliminados', compact('bancos', 'idiomas', 'guias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guia = Guia::find($id);

        if($request->foto == 1){
            $idiomas_id = json_encode($request->idiomas_id);

            $guia->nombre               = $request->nombre;
            $guia->apellido             = $request->apellido;
            $guia->tipo                 = $request->tipo;
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
                $rutaGuardarmg = 'panelguias';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoGuia = "$nombreOriginal";
            }

            $guia->file = $fotoGuia;
            $guia->save();
        }

        return back()->with('success','Guia actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Guia::destroy($id);

        return redirect()->route('proguias.index')->with('success','Guia eliminada');
    }
}

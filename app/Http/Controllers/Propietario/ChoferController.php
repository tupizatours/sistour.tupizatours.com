<?php

namespace App\Http\Controllers\Propietario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Banco;
use App\Models\Configuracion\Idioma;
use App\Models\Propietario\Chofer;
use Image;

class ChoferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $choferes = Chofer::all();
        
        return view('propietarios.choferes.index', compact('bancos', 'idiomas', 'choferes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $choferes = Chofer::all();
        
        return view('propietarios.choferes.create', compact('bancos', 'idiomas', 'choferes'));
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
        $in['correo']               = $request->correo;
        $in['celular']              = $request->celular;
        $in['cuenta']               = $request->cuenta;
        $in['bancos_id']            = $request->bancos_id;
        $in['referencia']           = $request->referencia;
        $in['celref']               = $request->celref;
        $in['observaciones']        = $request->observaciones;
        $in['estatus']              = $request->estatus;

        $store = Chofer::create($in);

        return back()->with('success','Chofer agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $chofer = Chofer::find($id);
        
        return view('propietarios.choferes.show', compact('chofer', 'bancos', 'idiomas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $chofer = Chofer::find($id);
        
        return view('propietarios.choferes.edit', compact('chofer'));
    }

    public function eliminados(Request $request)
    {
        $bancos = Banco::all();
        $idiomas = Idioma::all();
        $choferes = Chofer::all();
        
        return view('propietarios.choferes.eliminados', compact('bancos', 'idiomas', 'choferes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $chof = Chofer::find($id);

        if($request->foto == 1){
            $chof->nombre               = $request->nombre;
            $chof->apellido             = $request->apellido;
            $chof->cedula               = $request->cedula;
            $chof->licencia             = $request->licencia;
            $chof->correo               = $request->correo;
            $chof->celular              = $request->celular;
            $chof->cuenta               = $request->cuenta;
            $chof->bancos_id            = $request->bancos_id;
            $chof->referencia           = $request->referencia;
            $chof->celref               = $request->celref;
            $chof->observaciones        = $request->observaciones;
            $chof->estatus              = $request->estatus;
            $chof->save();
        }elseif($request->foto == 2){
            $request->validate([
                'file'  => 'required|image|mimes:jpeg,png,jpg'
            ]);

            if($imagen = $request->File('file')) {
                $rutaGuardarmg = 'panelchoferes';
                $nombreOriginal = $imagen->getClientOriginalName();
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
                $fotoChof = "$nombreOriginal";
            }

            $chof->file = $fotoChof;
            $chof->save();
        }

        return back()->with('success','Chofer actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Chofer::destroy($id);

        return redirect()->route('prochoferes.index')->with('success','Chofer eliminado');
    }
}

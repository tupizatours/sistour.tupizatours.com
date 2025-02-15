<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Qr;
use Image;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qrs = Qr::all();
        
        return view('configuracion.qrs.index', compact('qrs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $qrs = Qr::all();
        
        return view('configuracion.qrs.create', compact('qrs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file'  => 'required|image|mimes:jpeg,png,jpg'
        ]);

        if($imagen = $request->File('file')) {
            $rutaGuardarmg = 'panelqrs';
            $nombreOriginal = $imagen->getClientOriginalName();
            $imagenResized = Image::make($imagen)->fit(300, 300);
            $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
            $fotoQr = "$nombreOriginal";
        }
        
        Qr::create([
            'nombre'    => $request->nombre,
            'file'      => $fotoQr,
            'estado'    => $request->estado,
            'estatus'   => $request->estatus,
        ]);

        return back()->with('success','Qr agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $qr = Qr::find($id);
        
        return view('configuracion.qrs.show', compact('qr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $qr = Qr::find($id);
        
        return view('configuracion.qrs.edit', compact('qr'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file'  => 'required|image|mimes:jpeg,png,jpg'
        ]);

        if($imagen = $request->File('file')) {
            $rutaGuardarmg = 'panelqrs';
            $nombreOriginal = $imagen->getClientOriginalName();
            $imagenResized = Image::make($imagen)->fit(300, 300);
            $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
            $fotoQr = "$nombreOriginal";
        }else{
            unset($fotoQr);
        }

        $qrs = Qr::find($id);

        $qrs->nombre          = $request->nombre;
        $qrs->file            = $request->fotoQr;
        $qrs->estado          = $request->estado;
        $qrs->estatus         = $request->estatus;
        $qrs->save();

        return redirect()->route('confqrs.index')->with('success','Qr actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Qr::destroy($id);

        return redirect()->route('confqrs.index')->with('success','Qr eliminado');
    }
}

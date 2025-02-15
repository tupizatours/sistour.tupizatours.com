<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracion\Link;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = Link::all();
        
        return view('configuracion.links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $links = Link::all();
        
        return view('configuracion.links.create', compact('links'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $links = $request->all();
        Link::create($links);

        return back()->with('success','Link agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $link = Link::find($id);
        
        return view('configuracion.links.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $link = Link::find($id);
        
        return view('configuracion.links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $link = Link::find($id);

        $link->nombre          = $request->nombre;
        $link->descripcion     = $request->descripcion;
        $link->url             = $request->url;
        $link->estatus         = $request->estatus;
        $link->save();

        return redirect()->route('conflinks.index')->with('success','Link actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Link::destroy($id);

        return redirect()->route('conflinks.index')->with('success','Link eliminado');
    }
}

<?php

namespace App\Http\Controllers\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        
        return view('tours.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        
        return view('tours.categorias.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categorias = $request->all();
        Categoria::create($categorias);

        return back()->with('success','Categoria agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        
        return view('tours.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = Categoria::find($id);
        
        return view('tours.categorias.edit', compact('categoria'));
    }

    public function eliminados(Request $request)
    {
        $categorias = Categoria::all();
        
        return view('tours.categorias.eliminados', compact('categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cat = Categoria::find($id);

        $cat->titulo               = $request->titulo;
        $cat->estatus              = $request->estatus;
        $cat->save();

        return back()->with('success','Categoria actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Categoria::destroy($id);

        return redirect()->route('tourcategorias.index')->with('success','Categoria eliminada');
    }
}

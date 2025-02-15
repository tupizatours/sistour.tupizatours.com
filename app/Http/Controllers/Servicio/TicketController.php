<?php

namespace App\Http\Controllers\Servicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();
        
        return view('servicios.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tickets = Ticket::all();
        
        return view('servicios.tickets.create', compact('tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tickets = $request->all();
        Ticket::create($tickets);

        return back()->with('success','Ticket agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        
        return view('servicios.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        
        return view('servicios.tickets.edit', compact('ticket'));
    }

    public function eliminados(Request $request)
    {
        $tickets = Ticket::all();
        
        return view('servicios.tickets.eliminados', compact('tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tick = Ticket::find($id);

        $tick->titulo               = $request->titulo;
        $tick->costo                = $request->costo;
        $tick->cos_ext              = $request->cos_ext;
        $tick->nacionales           = $request->nacionales;
        $tick->extranjeros          = $request->extranjeros;
        $tick->estatus              = $request->estatus;
        $tick->save();

        return back()->with('success','Ticket actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Ticket::destroy($id);

        return redirect()->route('servtickets.index')->with('success','Ticket eliminado');
    }
}

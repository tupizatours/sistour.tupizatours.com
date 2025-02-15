<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva\Resercliente;
use App\Models\Tour;
use App\Models\Tour\HotelTour;
use App\Models\Tour\Categoria;
use App\Models\Servicio;
use App\Models\Servicio\Ticket;
use App\Models\Servicio\Turista;
use App\Models\Servicio\Accesorio;
use App\Models\Servicio\Hotel;
use App\Models\Servicio\Habitacion;
use App\Models\Configuracion\Alergia;
use App\Models\Configuracion\Alimentacion;
use App\Models\Configuracion\Link;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $tours = Tour::all();
        
        return view('ventas.solicitudes.index', compact('reservas', 'tours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reserva = Reserva::find($id);
        $resclis = Resercliente::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $hoteles = Hotel::all();
        $habitaciones = Habitacion::all();

        // Filtrar reservas con estado 2 y capacidad disponible
        $reservasDisponibles = Reserva::withCount('resclientes') // Cuenta resclientes relacionados
        ->where('estado', 2) // Filtra por estado 2
        ->where('estatus', 1) // Filtra por estatus 1
        ->where('fecha', $reserva->fecha) // Filtra por fecha
        ->get()
        ->filter(function ($reserva) {
            return $reserva->resclientes_count < $reserva->can_pri; // Capacidad máxima
        });

        return view('ventas.solicitudes.edit', compact('reserva', 'resclis', 'alimentos', 'alergias', 'hoteles', 'habitaciones', 'reservasDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva\Resercliente;
use App\Models\Venta\Pago;
use App\Models\Tour;
use App\Models\Country;
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
use DB;
use Image;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $tours = Tour::all();
        $countries = Country::all();
        
        return view('ventas.reservas.index', compact('reservas', 'tours', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $resclis = Resercliente::all();
        $reservas = Reserva::all();
        $tours = Tour::all();
        $countries = Country::all();
        $hottus = HotelTour::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        
        return view('ventas.reservas.create', compact('resclis', 'reservas', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'countries', 'hottus', 'categorias', 'servicios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048', // Máximo 2MB
        ]);

        $alergias = json_encode($request->alergias);
        $alimentacion = json_encode($request->alimentacion);
        
        $tickets = json_decode($request->input('tickets_seleccionados'), true);
        $rooms = json_decode($request->input('habitaciones_seleccionadas'), true);
        $accessories = json_decode($request->input('accesorios_seleccionados'), true);
        $services = json_decode($request->input('servicios_seleccionados'), true);

        if($imagen = $request->File('file')) {
            $rutaGuardarmg = 'files_documentos';
            $nombreOriginal = $imagen->getClientOriginalName();
            $extension = $imagen->getClientOriginalExtension();

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Procesar imagen
                $imagenResized = Image::make($imagen)->fit(300, 300);
                $imagenResized->save(public_path($rutaGuardarmg . '/' . $nombreOriginal));
            } elseif ($extension === 'pdf') {
                // Guardar directamente el PDF
                $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
            }

            $fotoQr = "$nombreOriginal";
        }

        // Crea la reserva y guarda los datos
        $in = [
            'codigo'        => str_random(10),
            'subtotal'      => $request->pre_tot,
            'total'         => $request->tour_total,
            'tour_id'       => $request->tour_id,
            'tprivado'      => $request->tprivado,
            'pre_per'       => $request->pre_uni,
            'can_per'       => $request->cantper,
            'pre_pri'       => $request->pre_tot,
            'can_pri'       => $request->max_per,
            'fecha'         => $request->fecha_limite,
            'estado'        => 2,
            'estatus'       => $request->estatus,
        ];

        $store = Reserva::create($in);

        // Crear registros en Resercliente
        for ($i = 0; $i < $request->cantper; $i++) {
            if ($i === 0) {
                // Primer registro, guarda todos los datos
                $rs = [
                    'codigo'            => str_random(10),
                    'pre_per'           => $request->pre_uni,
                    //'subtotal'          => $request->pre_tot,
                    'total'             => $request->tour_total,
                    'reserva_id'        => $store->id,
                    'nombres'           => $request->nombres,
                    'apellidos'         => $request->apellidos,
                    'edad'              => $request->edad,
                    'nacionalidad'      => $request->nacionalidad,
                    'documento'         => $request->documento,
                    'celular'           => $request->celular,
                    'sexo'              => $request->sexo,
                    'correo'            => $request->email,
                    'alergias'          => $alergias,
                    'alimentacion'      => $alimentacion,
                    'nota'              => $request->nota,
                    'file'              => $fotoQr,
                    'tickets'           => $tickets,
                    'habitaciones'      => $rooms,
                    'accesorios'        => $accessories,
                    'servicios'         => $services,
                    'estado'            => 1,
                    'estatus'           => $request->estatus,
                    'esPrincipal'       => true, // Primer registro como principal
                ];
            } else {
                // Registros subsiguientes, guarda solo los campos especificados
                $rs = [
                    'codigo'            => str_random(10),
                    'pre_per'           => $request->pre_uni,
                    'reserva_id'        => $store->id,
                    'estado'            => 1,
                    'estatus'           => $request->estatus,
                    'esPrincipal'       => false, // Registros adicionales no son principales
                ];
            }
            
            Resercliente::create($rs);
        }

        $data = $request->all();
        $tour_id = $request->tour_id;

        //$response = \Mail::to('danielmayurilevano@gmail.com')->send(new ReservaTour($data, $tour_id));

        return redirect('ventas/reservas/'.$request->reserva_id)->with('success','Nueva Cotización agregada.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::find($id);
        $resclis = Resercliente::where('reserva_id', $id)->get(); // Filtrar Resercliente por reserva_id
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $hoteles = Hotel::all();
        
        return view('ventas.reservas.show', compact('reserva', 'resclis', 'alimentos', 'alergias', 'hoteles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $resclis = Resercliente::all();
        $reserva = Reserva::find($id);
        $tours = Tour::all();
        $countries = Country::all();
        $hottus = HotelTour::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        
        return view('ventas.reservas.edit', compact('resclis', 'reserva', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'countries', 'hottus', 'categorias', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if($request->reservas == "reservas"){
            $res = Reserva::find($id);
            $res->can_pri = $request->cantper;
            $res->save();
            
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

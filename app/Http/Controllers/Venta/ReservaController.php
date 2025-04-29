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
use Illuminate\Support\Str;


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
        // Obtener todos los datos de los modelos relacionados
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
    
        // Obtener los modelos necesarios que se usan en la vista
        $tickets = Ticket::all();
        $accesorios = Accesorio::all();
        $turistas = Turista::all();
        $hoteles = Hotel::all(); // Obtener todos los hoteles
    
        // Pasar todos los datos a la vista
        return view('ventas.reservas.create', compact(
            'resclis', 'reservas', 'links', 'onlines', 'qrs', 'habitaciones', 
            'alimentos', 'alergias', 'tours', 'countries', 'hottus', 'categorias', 
            'servicios', 'tickets', 'accesorios', 'turistas', 'hoteles'
        ));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);
    
        // Datos adicionales
        $alergias = json_encode($request->alergias ?? []);
        $alimentacion = json_encode($request->alimentacion ?? []);
    
        $tickets = json_decode($request->input('tickets_seleccionados'), true) ?? [];
        $rooms = json_decode($request->input('habitaciones_seleccionadas'), true) ?? [];
        $accessories = json_decode($request->input('accesorios_seleccionados'), true) ?? [];
        $services = json_decode($request->input('servicios_seleccionados'), true) ?? [];
    
        // Manejo de archivo
        if ($imagen = $request->file('file')) {
            $rutaGuardarmg = 'files_documentos';
            $nombreOriginal = time() . '_' . $imagen->getClientOriginalName();
            $extension = $imagen->getClientOriginalExtension();
    
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                Image::make($imagen)->fit(300, 300)->save(public_path("$rutaGuardarmg/$nombreOriginal"));
            } elseif ($extension === 'pdf') {
                $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
            }
    
            $fotoQr = $nombreOriginal;
        }
    
        // 🧠 Lógica de cálculo unitaria y adicional
        $precioUnidad = floatval($request->pre_uni);
        $cantidad = intval($request->cantper);
        $esPrivado = $request->tprivado ? true : false;

        $tickets = json_decode($request->input('tickets_seleccionados'), true) ?? [];
        $rooms = json_decode($request->input('habitaciones_seleccionadas'), true) ?? [];
        $accessories = json_decode($request->input('accesorios_seleccionados'), true) ?? [];
        $services = json_decode($request->input('servicios_seleccionados'), true) ?? [];

        $adicionales = collect(array_merge($tickets, $rooms, $accessories, $services))
            ->pluck('price')
            ->sum();

        $pre_pri = $esPrivado 
            ? floatval($request->pre_tot)
            : $precioUnidad * $cantidad;

        $totalReserva = 0;

        // Crear reserva inicialmente sin total
        $reserva = Reserva::create([
            'codigo'    => Str::random(10),
            'subtotal'  => 0, // será recalculado luego
            'total'     => 0, // será recalculado luego
            'tour_id'   => $request->tour_id,
            'tprivado'  => $esPrivado,
            'pre_per'   => $precioUnidad,
            'can_per'   => $cantidad,
            'pre_pri'   => $pre_pri,
            'can_pri'   => $request->max_per,
            'fecha'     => $request->fecha_limite,
            'estado'    => 2,
            'estatus'   => $request->estatus,
        ]);

        // Crear turistas asociados
        for ($i = 0; $i < $cantidad; $i++) {
            $esPrincipal = $i === 0;

            $totalUnitario = $precioUnidad + ($esPrincipal ? $adicionales : 0);
            $totalReserva += $totalUnitario;

            $rescli = [
                'codigo'        => Str::random(10),
                'pre_per'       => $precioUnidad,
                'total'         => $totalUnitario,
                'reserva_id'    => $reserva->id,
                'estado'        => 1,
                'estatus'       => $request->estatus,
                'esPrincipal'   => $esPrincipal,
            ];

            if ($esPrincipal) {
                $rescli = array_merge($rescli, [
                    'nombres'       => $request->nombres,
                    'apellidos'     => $request->apellidos,
                    'edad'          => $request->edad,
                    'nacionalidad'  => $request->nacionalidad,
                    'documento'     => $request->documento,
                    'celular'       => $request->celular,
                    'sexo'          => $request->sexo,
                    'correo'        => $request->email,
                    'alergias'      => $alergias,
                    'alimentacion'  => $alimentacion,
                    'nota'          => $request->nota,
                    'file'          => $fotoQr,
                    'tickets'       => $tickets,
                    'habitaciones'  => $rooms,
                    'accesorios'    => $accessories,
                    'servicios'     => $services,
                ]);
            }

            Resercliente::create($rescli);
        }

        // Actualizar totales reales
        $reserva->update([
            'subtotal' => $precioUnidad * $cantidad,
            'total'    => $totalReserva,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::find($id);
        $resclis = Resercliente::where('reserva_id', $id)->get(); // Filtrar Resercliente por reserva_id
    
        // ✅ Validar que los pagos sumen solo si están activos (estatus = 1)
        foreach ($resclis as $rescli) {
            $rescli->pagado = Pago::where('rescli_id', $rescli->id)
                                ->where('estatus', 1)
                                ->sum('conversion');
    
            // ✅ Ajustar saldo pendiente a 0 si es negativo
            $rescli->saldo_pendiente = max(($rescli->total - $rescli->pagado), 0);
        }
    
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

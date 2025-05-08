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
use App\Mail\ReservaTour;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;


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
    public function create(Request $request)
    {
        // Asegurarse de que venga el ID del tour por GET
        $tourId = $request->query('tour_id');
    
        if (!$tourId) {
            return redirect()->back()->with('error', 'No se ha especificado un tour.');
        }
    
        // Obtener el tour solicitado
        $tour = Tour::findOrFail($tourId);
    
        // Decodificar los elementos seleccionados en el tour
        $ticket_ids     = json_decode($tour->tickets, true) ?? [];
        $accesorio_ids  = json_decode($tour->accesorios, true) ?? [];
        $turista_ids    = json_decode($tour->turistas, true) ?? [];
        $hotel_ids_flat = array_merge(...(json_decode($tour->hoteles, true) ?? []));
    
        // Datos relacionados específicos para este tour
        $tickets     = Ticket::whereIn('id', $ticket_ids)->get();
        $accesorios  = Accesorio::whereIn('id', $accesorio_ids)->get();
        $turistas    = Turista::whereIn('id', $turista_ids)->get();
        $hoteles     = Hotel::whereIn('id', $hotel_ids_flat)->with('habitaciones')->get();
        $habitaciones = Habitacion::all(); // Se requieren para match de radios en cada hotel
    
        // Datos auxiliares para el formulario
        $countries   = Country::all();
        $alergias    = Alergia::all();
        $alimentos   = Alimentacion::all();
        $links       = Link::where('estatus', 1)->get();
        $onlines     = Online::where('estatus', 1)->get();
        $qrs         = Qr::where('estatus', 1)->get();
    
        // Pasar a la vista solo lo que el formulario necesita
        return view('ventas.reservas.create', compact(
            'tour',
            'tickets',
            'hoteles',
            'habitaciones',
            'accesorios',
            'turistas',
            'countries',
            'alergias',
            'alimentos',
            'links',
            'onlines',
            'qrs'
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

        $esExterno = $request->input('pagina') === 'user_external';

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
            'estado'    => $esExterno ? 1 : 2,
            'estatus'   => $request->estatus,
        ]);

        // Crear turistas asociados
        for ($i = 0; $i < $cantidad; $i++) {
            $esPrincipal = $i === 0;
        
            $sub = $precioUnidad;
            $res_total = $esPrincipal
                ? $precioUnidad + $adicionales  // Principal paga los adicionales
                : $precioUnidad;                // Otros solo pagan su unidad
        
            $totalReserva += $res_total;
        
            $rescli = [
                'codigo'      => Str::random(10),
                'pre_per'     => $precioUnidad,
                'subtotal'    => $sub,
                'total'       => $res_total,
                'reserva_id'  => $reserva->id,
                'estado'      => 1,
                'estatus'     => $request->estatus,
                'esPrincipal' => $esPrincipal,
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

        // Envío de notificación con nuevo servicio
        $cliente = Resercliente::where('reserva_id', $reserva->id)
         ->where('esPrincipal', true)
         ->first();
 
        // Detecta página origen
        $pagina = $request->input('pagina') ?? 'user_external';

        // Ruta al PDF si aplica (ajusta el método según tu lógica)
        $pdfPath = null;

        if (! $esExterno) {
            $pdfPath = $this->generarResumenReservaPDF($reserva, $cliente);
        }

        $turistasAdicionales = Resercliente::where('reserva_id', $reserva->id)
        ->where('esPrincipal', false)
        ->whereNull('nombres') // puedes ajustar esta lógica según tu criterio de "datos incompletos"
        ->get()
        ->map(function ($turista) {
            return [
                'link' => route('venresclisuser', $turista->id),
            ];
        })
        ->toArray();
    
        // Envío de notificación
        try {
            Mail::to($cliente->correo)->send(
                new ReservaTour(
                    $reserva,
                    $cliente,
                    $pagina,
                    $turistasAdicionales,
                    $pdfPath   // adjunto, opcional
                )
            );        
        } catch (\Exception $e) {
            \Log::error('No se pudo enviar el correo de reserva: ' . $e->getMessage(), [
                'correo' => $cliente->correo ?? 'sin correo',
                'reserva_id' => $reserva->id,
            ]);
        }
 
        return redirect($esExterno ? '/tienda' : '/ventas/reservas')
       ->with('success', 'Reserva creada correctamente.');
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
        if ($request->reservas == "reservas") {
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

    private function generarResumenReservaPDF($reserva, $cliente)
    {
        // Decodificar o usar directamente si ya es array
        $habitacionesRaw = is_string($cliente->habitaciones) ? json_decode($cliente->habitaciones, true) : ($cliente->habitaciones ?? []);
        $ticketsRaw = is_string($cliente->tickets) ? json_decode($cliente->tickets, true) : ($cliente->tickets ?? []);
        $accesoriosRaw = is_string($cliente->accesorios) ? json_decode($cliente->accesorios, true) : ($cliente->accesorios ?? []);
        $serviciosRaw = is_string($cliente->servicios) ? json_decode($cliente->servicios, true) : ($cliente->servicios ?? []);
    
        // Habitaciones con hotel
        $habitaciones = collect($habitacionesRaw)->map(function ($item) {
            $habit = \App\Models\Servicio\Habitacion::with('hotel')->find($item['id'] ?? 0);
            return [
                'hotel' => $habit?->hotel?->titulo ?? 'Hotel no especificado',
                'name'  => $item['name'] ?? 'Habitación',
                'price' => $item['price'] ?? 0,
            ];
        });
    
        // Resto como colecciones limpias
        $tickets    = collect($ticketsRaw ?? []);
        $accesorios = collect($accesoriosRaw ?? []);
        $servicios  = collect($serviciosRaw ?? []);
    
        // Alergias y alimentación
        $alergias = collect();
        $alimentos = collect();
    
        $alergiaIds = is_string($cliente->alergias) ? json_decode($cliente->alergias, true) : ($cliente->alergias ?? []);
        $alimentacionIds = is_string($cliente->alimentacion) ? json_decode($cliente->alimentacion, true) : ($cliente->alimentacion ?? []);
    
        if (is_array($alergiaIds) && !empty($alergiaIds)) {
            $alergias = \App\Models\Configuracion\Alergia::whereIn('id', $alergiaIds)->get();
        }
    
        if (is_array($alimentacionIds) && !empty($alimentacionIds)) {
            $alimentos = \App\Models\Configuracion\Alimentacion::whereIn('id', $alimentacionIds)->get();
        }
    
        // Generar PDF
        $pdf = Pdf::loadView('pdf.reserva', compact(
            'reserva',
            'cliente',
            'habitaciones',
            'tickets',
            'accesorios',
            'servicios',
            'alergias',
            'alimentos'
        ));
    
        // Guardar en ruta definida
        $folderPath = public_path('reservas');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true); // Crea la carpeta si no existe
        }

        $pdfPath = $folderPath . '/resumen_' . $reserva->codigo . '.pdf';
        $pdf->save($pdfPath);
        
    
        return $pdfPath;
    }
    
}

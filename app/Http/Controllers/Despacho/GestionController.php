<?php

namespace App\Http\Controllers\Despacho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Reserva\Resercliente;
use App\Models\Tour;
use App\Models\Tour\HotelTour;
use App\Models\Servicio;
use App\Models\Tour\Categoria;
use App\Models\Servicio\Vagoneta;
use App\Models\Servicio\Caballo;
use App\Models\Servicio\Bicicleta;
use App\Models\Propietario;
use App\Models\Propietario\Chofer;
use App\Models\Propietario\Cocinero;
use App\Models\Propietario\Guia;
use App\Models\Propietario\Traductor;
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
use App\Models\Despacho\Gestion;
use App\Models\Caja\Porpago;
use App\Models\Venta\Pago;

use Illuminate\Support\Str;

class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $tours = Tour::all();
        $servicios = Servicio::all();
        $vagonetas = Vagoneta::all();
        $caballors = Caballo::all();
        $bicicletas = Bicicleta::all();
        $propietarios = Propietario::all();
        $chofers = Chofer::all();
        $cocineros = Cocinero::all();
        $guias = Guia::all();
        $traductors = Traductor::all();

        return view('despachos.gestiones.index', compact('reservas', 'tours', 'servicios', 'vagonetas', 'caballors', 'bicicletas', 'propietarios', 'chofers', 'cocineros', 'guias', 'traductors'));
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
        if ($request->pagina == "gestions") {
            $rs = [
                'codigo'                => str_random(10),
                'reserva_id'            => $request->reserva_id,
                'tour_id'               => $request->tour_id,
                'servicio_id'           => $request->servicio_id,
                'servicio_t'            => $request->servicio_t,
                'guia_id'               => $request->guia_id,
                'guia_t'                => $request->guia_t,
                'traductor_id'          => $request->traductor_id,
                'traductor_t'           => $request->traductor_t,
                'cocinero_id'           => $request->cocinero_id,
                'cocinero_t'            => $request->cocinero_t,
                'chofer_id'             => $request->chofer_id,
                'chofer_t'              => $request->chofer_t,
                'vagoneta_id'           => $request->vagoneta_id,
                'provag_id'             => $request->provag_id,
                'vagoneta_t'            => $request->vagoneta_t,
                'caballo_id'            => $request->caballo_id,
                'procab_id'             => $request->procab_id,
                'caballo_t'             => $request->caballo_t,
                'bicicleta_id'          => $request->bicicleta_id,
                'probic_id'             => $request->probic_id,
                'bicicleta_t'           => $request->bicicleta_t,
                'estado'                => 1,
                'estatus'               => 1,
            ];

            Gestion::create($rs);

            // 🔁 Crear/Actualizar Porpago por cada servicio correspondiente
            $this->guardarPorpagos($request);

            return redirect('despachos/gestiones/' . $request->reserva_id);
        } else {
        
            $reserva = Reserva::findOrFail($request->reserva_id);

            $totalPagado = Resercliente::join('pagos', 'reserclientes.id', '=', 'pagos.rescli_id')
            ->where('reserclientes.reserva_id', $reserva->id) // 👈 este es el fix
            ->where('pagos.estatus', 1)
            ->sum('pagos.conversion');

           if (bccomp($totalPagado, $reserva->total, 2) !== 0) {
                return redirect()->back()->with('error', '⚠️ No se puede despachar. El total pagado no coincide con el total de la reserva.');
            }

            $reserva->estado = 3;
            $reserva->save();
            
            return redirect('despachos/gestiones/' . $request->reserva_id);
            
        }
    }

    public function obtenerVagonetas($propietario_id)
    {
        $vagonetas = Vagoneta::where('propietario_id', $propietario_id)->get();
        return response()->json($vagonetas);
    }

    public function obtenerCaballos($propietario_id)
    {
        $caballos = Caballo::where('propietario_id', $propietario_id)->get();
        return response()->json($caballos);
    }

    public function obtenerBicicletas($propietario_id)
    {
        $bicicletas = Bicicleta::where('propietario_id', $propietario_id)->get();
        return response()->json($bicicletas);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reserva::find($id); // ✅ Primero defines

        $existePorpago = Porpago::where('reserva_id', $reserva->id)
            ->where('tour_id', $reserva->tour_id)
            ->get();

        $resclis = Resercliente::where('reserva_id', $id)->get(); // Filtrar Resercliente por reserva_id

        $gestion = Gestion::with([
            'vagoneta',
            'provag',
            'caballo',
            'procab',
            'bicicleta',
            'probic',
            'guia',
            'traductor',
            'cocinero',
            'chofer',
            'servicio'
        ])->where('reserva_id', $id)->first();

        $serv_tour_ids = json_decode($reserva->tour->serv_tour);
        $servicios = Servicio::whereIn('id', $serv_tour_ids)->get();

        $tours = Tour::where('estatus', 1)->get();
        $hottus = HotelTour::all();
        $categorias = Categoria::where('estatus', 1)->get();
        $alergias = Alergia::where('estatus', 1)->get();
        $alimentos = Alimentacion::where('estatus', 1)->get();
        $habitaciones = Habitacion::where('estatus', 1)->get();
        $links = Link::where('estatus', 1)->get();
        $onlines = Online::where('estatus', 1)->get();
        $qrs = Qr::where('estatus', 1)->get();
        $guias = Guia::where('estatus', 1)->get();
        $traductors = Traductor::where('estatus', 1)->get();
        $chofers = Chofer::where('estatus', 1)->get();
        $cocineros = Cocinero::where('estatus', 1)->get();
        $propietarios = Propietario::where('estatus', 1)->get();
        $vagonetas = Vagoneta::where('estatus', 1)->get();
        $bicicletas = Bicicleta::where('estatus', 1)->get();
        $caballos = Caballo::where('estatus', 1)->get();
        $turistas = Turista::where('estatus', 1)->get();

        $clientesConDatos = $resclis->map(function ($resercliente) {
            $hotelesSeleccionados = $resercliente->habitaciones ?? [];
            $ticketsSeleccionados = $resercliente->tickets ?? [];
            $accesoriosSeleccionados = $resercliente->accesorios ?? [];
            $serviciosSeleccionados = $resercliente->servicios ?? [];

            $sumaHoteles = 0;
            $sumaTickets = 0;
            $sumaAccesorios = 0;
            $sumaServicios = 0;

            $detallesHoteles = [];
            $detallesTickets = [];
            $detallesAccesorios = [];
            $detallesServicios = [];

            // Procesar Hoteles
            foreach ($hotelesSeleccionados as $hotel) {
                $habitacion = \App\Models\Servicio\Habitacion::find($hotel['id']);

                if ($habitacion) {
                    $detallesHoteles[] = [
                        'nombre' => $habitacion->titulo,
                        'costo' => $habitacion->costo,
                    ];
                    $sumaHoteles += $habitacion->costo;
                }
            }

            // Procesar Tickets
            foreach ($ticketsSeleccionados as $ticket) {
                $ticketData = \App\Models\Servicio\Ticket::find($ticket['id']);
                if ($ticketData) {
                    $detallesTickets[] = [
                        'nombre' => $ticketData->titulo,
                        'costo' => $ticketData->costo,
                    ];
                    $sumaTickets += $ticketData->costo;
                }
            }

            // Procesar Accesorios
            foreach ($accesoriosSeleccionados as $accesorio) {
                $accesorioData = \App\Models\Servicio\Accesorio::find($accesorio['id']);
                if ($accesorioData) {
                    $detallesAccesorios[] = [
                        'nombre' => $accesorioData->titulo,
                        'costo' => $accesorioData->costo,
                    ];
                    $sumaAccesorios += $accesorioData->costo;
                }
            }

            // Procesar Servicios
            foreach ($serviciosSeleccionados as $servicio) {
                $servicioData = \App\Models\Servicio\Turista::find($servicio['id']);
                if ($servicioData) {
                    $detallesServicios[] = [
                        'nombre' => $servicioData->titulo,
                        'costo' => $servicioData->costo,
                    ];
                    $sumaServicios += $servicioData->costo;
                }
            }

            return [
                'hoteles' => $detallesHoteles,
                'tickets' => $detallesTickets,
                'accesorios' => $detallesAccesorios,
                'servicios' => $detallesServicios,
                'total_hoteles' => $sumaHoteles,
                'total_tickets' => $sumaTickets,
                'total_accesorios' => $sumaAccesorios,
                'total_servicios' => $sumaServicios,
            ];
        });

        $prestatariosEnGestion = collect();

        if ($gestion) {
            $ids = collect([
                $gestion->provag_id,
                $gestion->procab_id,
                $gestion->probic_id
            ])->filter()->unique();

        $prestatariosEnGestion = Propietario::whereIn('id', $ids)->get();
        }
        
        // Suma general para todos los clientes
        $totalGeneralHoteles = $clientesConDatos->sum('total_hoteles');
        $totalGeneralTickets = $clientesConDatos->sum('total_tickets');
        $totalGeneralAccesorios = $clientesConDatos->sum('total_accesorios');
        $totalGeneralServicios = $clientesConDatos->sum('total_servicios');
        $totalGeneralGasto = $totalGeneralHoteles + $totalGeneralTickets + $totalGeneralAccesorios + $totalGeneralServicios;
        $tiposTotalidadesPagadas = Porpago::where('reserva_id', $reserva->id)
        ->where('tour_id', $reserva->tour_id)
        ->whereIn('tipo_servicio', ['hoteles', 'tickets', 'accesorios', 'servicios'])
        ->pluck('tipo_servicio')
        ->map(fn($item) => strtolower($item))
        ->toArray();

        foreach ($resclis as $rescli) {
            $pagado = Pago::where('rescli_id', $rescli->id)
                ->where('estatus', 1)
                ->sum('conversion');

            // 👇 Usa total si está definido, si no, cae en pre_per como fallback
            $totalCliente = $rescli->total ?? $rescli->pre_per;

            $rescli->pagado = $pagado;
            $rescli->total_cliente = $totalCliente;
            $rescli->saldo_pendiente = max($totalCliente - $pagado, 0);
        }
          

        return view('despachos.gestiones.show', compact(
            'clientesConDatos',
            'totalGeneralHoteles',
            'totalGeneralTickets',
            'totalGeneralAccesorios',
            'totalGeneralServicios',
            'totalGeneralGasto',
            'turistas',
            'gestion',
            'caballos',
            'bicicletas',
            'vagonetas',
            'propietarios',
            'cocineros',
            'chofers',
            'traductors',
            'guias',
            'resclis',
            'reserva',
            'links',
            'onlines',
            'qrs',
            'habitaciones',
            'alimentos',
            'alergias',
            'tours',
            'hottus',
            'categorias',
            'servicios',
            'existePorpago',
            'prestatariosEnGestion',
            'tiposTotalidadesPagadas'
        ));
    }

    public function gesanticipos(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $resclis = Resercliente::all();
        $reserva = Reserva::find($id);
        $tours = Tour::all();
        $hottus = HotelTour::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();

        return view('despachos.gestiones.show', compact('resclis', 'reserva', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'hottus', 'categorias', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->pagina == "gestions") {
            $rs = [
                'codigo'        => Str::random(10),
                'reserva_id'    => $request->reserva_id,
                'tour_id'       => $request->tour_id,
                'servicio_id'   => $request->servicio_id ?? null,
                'servicio_t'    => $request->servicio_t ?? null,
                'guia_id'       => $request->guia_id ?? null,
                'guia_t'        => $request->guia_t ?? null,
                'traductor_id'  => $request->traductor_id ?? null,
                'traductor_t'   => $request->traductor_t ?? null,
                'cocinero_id'   => $request->cocinero_id ?? null,
                'cocinero_t'    => $request->cocinero_t ?? null,
                'chofer_id'     => $request->chofer_id ?? null,
                'chofer_t'      => $request->chofer_t ?? null,
                'vagoneta_id'   => $request->vagoneta_id ?? null,
                'provag_id'     => $request->provag_id ?? null,
                'vagoneta_t'    => $request->vagoneta_t ?? null,
                'caballo_id'    => $request->caballo_id ?? null,
                'procab_id'     => $request->procab_id ?? null,
                'caballo_t'     => $request->caballo_t ?? null,
                'bicicleta_id'  => $request->bicicleta_id ?? null,
                'probic_id'     => $request->probic_id ?? null,
                'bicicleta_t'   => $request->bicicleta_t ?? null,
                'estado'        => 1,
                'estatus'       => 1,
            ];            

            $gestion = Gestion::find($id);
            $gestion->update($rs);

            // 🔁 Crear/Actualizar Porpago por cada servicio correspondiente
            $this->guardarPorpagos($request);

            return redirect('despachos/gestiones/' . $request->reserva_id);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    private function guardarPorpagos(Request $request)
    {
        $servicios = [
            'servicio' => [
                'servicio_id'   => $request->servicio_id,
                'costo'         => $request->servicio_t,
                'es_prestatario' => false,
            ],
            'guia' => [
                'servicio_id'   => $request->guia_id,
                'costo'         => $request->guia_t,
                'es_prestatario' => true,
            ],
            'traductor' => [
                'servicio_id'   => $request->traductor_id,
                'costo'         => $request->traductor_t,
                'es_prestatario' => true,
            ],
            'cocinero' => [
                'servicio_id'   => $request->cocinero_id,
                'costo'         => $request->cocinero_t,
                'es_prestatario' => true,
            ],
            'chofer' => [
                'servicio_id'   => $request->chofer_id,
                'costo'         => $request->chofer_t,
                'es_prestatario' => true, // si lo consideras prestatario
            ],
            'vagoneta' => [
                'servicio_id'   => $request->provag_id,
                'pres_serv_id'  => $request->vagoneta_id,
                'costo'         => $request->vagoneta_t,
                'es_prestatario' => true,
            ],
            'caballo' => [
                'servicio_id'   => $request->procab_id,
                'pres_serv_id'  => $request->caballo_id,
                'costo'         => $request->caballo_t,
                'es_prestatario' => true,
            ],
            'bicicleta' => [
                'servicio_id'   => $request->probic_id,
                'pres_serv_id'  => $request->bicicleta_id,
                'costo'         => $request->bicicleta_t,
                'es_prestatario' => true,
            ]
        ];

        foreach ($servicios as $tipo => $data) {
            if (!empty($data['costo']) && !empty($data['servicio_id'])) {
                \App\Models\Caja\Porpago::updateOrCreate(
                    [
                        'reserva_id'    => $request->reserva_id,
                        'tour_id'       => $request->tour_id,
                        'tipo_servicio' => $tipo,
                    ],
                    [
                        'servicio_id'   => $data['servicio_id'],
                        'pres_serv_id'  => $data['pres_serv_id'] ?? null,
                        'costo'         => $data['costo'],
                        'es_prestatario' => $data['es_prestatario'],
                        'estado'        => 'pendiente',
                    ]
                );
            }
        }
    }
}

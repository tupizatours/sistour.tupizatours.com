<?php

namespace App\Http\Controllers\Venta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Venta\Pago;
use App\Models\Reserva\Resercliente;
use App\Models\Reserva;
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
use DB;
use Image;
use App\Models\Configuracion\Online;
use App\Models\Configuracion\Qr;
use App\Models\Configuracion\Cobro;

class RescliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if ($request->pagina == "add_turista") {
            $request->validate([
                'file' => 'required|mimes:jpeg,jpg,png,pdf|max:2048', // Máximo 2MB
            ]);

            $alergias = json_encode($request->alergias);
            $alimentacion = json_encode($request->alimentacion);

            $tickets = json_decode($request->input('tickets_seleccionados'), true);
            $rooms = json_decode($request->input('habitaciones_seleccionadas'), true);
            $accessories = json_decode($request->input('accesorios_seleccionados'), true);
            $services = json_decode($request->input('servicios_seleccionados'), true);

            if ($imagen = $request->File('file')) {
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

            $rs = [
                'codigo'            => str_random(10),
                'pre_per'           => $request->pre_uni,
                //'subtotal'          => $request->pre_tot,
                'total'             => $request->tour_total,
                'reserva_id'        => $request->reserva_id,
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
                'estado'            => 2,
                'estatus'           => $request->estatus,
                'esPrincipal'       => false, // Registros adicionales no son principales
            ];

            Resercliente::create($rs);

            // 🔹 **Actualizar `can_per` en la reserva**
            $reserva = Reserva::find($request->reserva_id);
            if ($reserva) {
                $reserva->increment('can_per'); // Suma 1 a can_per
            }

            $data = $request->all();
            $tour_id = $request->tour_id;

            //$response = \Mail::to('danielmayurilevano@gmail.com')->send(new ReservaTour($data, $tour_id));

            return redirect('ventas/reservas/' . $request->reserva_id)->with('success', 'Nueva Cotización agregada.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rescli = Resercliente::find($id);

        if (!$rescli) {
            return redirect()->back()->with('error', 'Reserva no encontrada.');
        }

        $sumaMonto = Pago::where('rescli_id', $id)->where('estatus', 1)->sum('conversion') ?? 0;
        $reservas = Reserva::all();
        $tours = Tour::all();
        $countries = Country::all();
        $hottus = HotelTour::all();
        $hoteles = Hotel::all();
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $tickets = Ticket::all();
        $turistas = Turista::all();
        $accesorios = Accesorio::all();
        $alergias = Alergia::all();
        $alimentos = Alimentacion::all();
        $habitaciones = Habitacion::all();
        $links = Link::all();
        $onlines = Online::all();
        $qrs = Qr::all();
        $cobros = Cobro::all();
        $pagos = Pago::all();

        return view('ventas.resclis.show', compact(
            'sumaMonto',
            'pagos',
            'cobros',
            'rescli',
            'reservas',
            'links',
            'onlines',
            'qrs',
            'habitaciones',
            'alimentos',
            'alergias',
            'tours',
            'countries',
            'hottus',
            'hoteles',
            'categorias',
            'servicios',
            'tickets',
            'turistas',
            'accesorios'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rescli = Resercliente::find($id);
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

        return view('ventas.resclis.edit', compact('rescli', 'reservas', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'countries', 'hottus', 'categorias', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        if ($request->pagina == "file_panel" || $request->pagina == "user_external") {
            $alergias     = json_encode($request->alergias);
            $alimentacion = json_encode($request->alimentacion);

            $tickets    = json_decode($request->input('tickets_seleccionados'), true);
            $rooms      = json_decode($request->input('habitaciones_seleccionadas'), true);
            $accessories = json_decode($request->input('accesorios_seleccionados'), true);
            $services   = json_decode($request->input('servicios_seleccionados'), true);

            $in = Resercliente::find($id);
            $fotoQr = $in->file;

            // Procesar imagen si se sube una nueva
            if ($imagen = $request->file('file')) {
                $rutaGuardarmg = 'files_documentos';
                $nombreOriginal = time() . '_' . $imagen->getClientOriginalName();
                $extension = $imagen->getClientOriginalExtension();

                // Eliminar imagen anterior si existe
                if ($in->file && file_exists(public_path("$rutaGuardarmg/{$in->file}"))) {
                    unlink(public_path("$rutaGuardarmg/{$in->file}"));
                }

                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $imagenResized = Image::make($imagen)->fit(300, 300);
                    $imagenResized->save(public_path("$rutaGuardarmg/$nombreOriginal"));
                } elseif (in_array($extension, ['pdf', 'doc', 'docx'])) {
                    $imagen->move(public_path($rutaGuardarmg), $nombreOriginal);
                }

                $fotoQr = $nombreOriginal;
            }

            // Datos para actualizar Resercliente
            $rs = [
                'pre_per'      => $request->pre_uni,
                'total'        => $request->tour_total,
                'nombres'      => $request->nombres,
                'apellidos'    => $request->apellidos,
                'edad'         => $request->edad,
                'nacionalidad' => $request->nacionalidad,
                'documento'    => $request->documento,
                'celular'      => $request->celular,
                'sexo'         => $request->sexo,
                'correo'       => $request->email,
                'alergias'     => $alergias,
                'alimentacion' => $alimentacion,
                'nota'         => $request->nota,
                'file'         => $fotoQr,
                'tickets'      => $tickets,
                'habitaciones' => $rooms,
                'accesorios'   => $accessories,
                'servicios'    => $services,
            ];

            // Actualizar registro de Resercliente
            $in->update($rs);

            // 🔁 RE-CALCULAR el total de la reserva sumando todos los rescli
            $reservaId = $request->reserva_id;
            $reservaClientes = Resercliente::where('reserva_id', $reservaId)->get();

            $totalRecalculado = $reservaClientes->sum('total');

            // Actualizar la tabla reservas con el nuevo total
            Reserva::where('id', $reservaId)->update([
                'total' => $totalRecalculado,
            ]);

            // Si viene del externo, mostrar mensaje especial
            if ($request->pagina == "user_external") {
                return view('reservas.confirmacion-user', ['nombre' => $request->nombres]);
            }

            return redirect('ventas/reservas/' . $reservaId)
                ->with('success', 'Reserva actualizada correctamente.');
        }
    }

    public function externalUpdate(Request $request, $id)
    {
        if ($request->pagina !== 'user_external') {
            abort(403, 'No autorizado');
        }

        return $this->update($request, $id); // o copia el contenido completo aquí si prefieres separar
    }

    /**
     * Remove the specified resource from storage.
     */
    public function user($id)
    {
        $rescli = Resercliente::find($id);
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

        return view('ventas.resclis.user', compact('rescli', 'reservas', 'links', 'onlines', 'qrs', 'habitaciones', 'alimentos', 'alergias', 'tours', 'countries', 'hottus', 'categorias', 'servicios'));
    }

    public function destroy($id) {}
}

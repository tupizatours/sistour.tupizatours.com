<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Validator;

use App\Models\Reserva;
use App\Models\Tour;
use App\Models\Servicio;
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
use DB;
use App\Models\Despacho\Gestion;
use App\Models\Caja\Porcobro;
use App\Models\Caja\Porpago;

class PorcobroController extends Controller
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
        // Por cobrar - Validar los datos del formulario
        $data = $request->validate([
            'serv_name' => 'nullable|array',
            'serv_cost' => 'nullable|array',
            'guia_name' => 'nullable|array',
            'guia_cost' => 'nullable|array',
            'trad_name' => 'nullable|array',
            'trad_cost' => 'nullable|array',
            'coci_name' => 'nullable|array',
            'coci_cost' => 'nullable|array',
            'chof_name' => 'nullable|array',
            'chof_cost' => 'nullable|array',
            'checkboxes' => 'nullable|array', // Permite que checkboxes esté vacío o no presente
        ]);

        // Filtrar checkboxes no seleccionados
        $tickets = [];
        if (isset($data['checkboxes'])) {
            foreach ($data['checkboxes'] as $key => $checkbox) {
                // Excluir el checkbox 'check_todos' (Totalidad) de la lista
                if ($key !== 'check_todos') {
                    // Verificar si el checkbox no fue marcado
                    if (!isset($checkbox['selected']) || $checkbox['selected'] !== 'on') {
                        $tickets[] = [
                            'nombre' => $checkbox['nombre'],
                            'monto' => $checkbox['monto'],
                            'selected' => false, // Marcar como no seleccionado
                        ];
                    }
                }
            }
        }

        // Guardar la información
        $cobrar = new Porcobro([
            'reserva_id' => $request->reserva_id,
            'tour_id' => $request->tour_id,
            'servicios' => array_combine($data['serv_name'] ?? [], $data['serv_cost'] ?? []),
            'guias' => array_combine($data['guia_name'] ?? [], $data['guia_cost'] ?? []),
            'traductors' => array_combine($data['trad_name'] ?? [], $data['trad_cost'] ?? []),
            'cocineros' => array_combine($data['coci_name'] ?? [], $data['coci_cost'] ?? []),
            'chofers' => array_combine($data['chof_name'] ?? [], $data['chof_cost'] ?? []),
            'tickets' => $tickets, // Guardar los no seleccionados
        ]);

        $cobrar->save();
        
        // Por pagar - Validar los datos del formulario
        $paga = $request->validate([
            'reserva_id' => 'required|integer',
            'tour_id' => 'required|integer',
            'anticipoActual' => 'nullable|numeric',
            'subtotal' => 'nullable|numeric',
            'prestatario' => 'nullable|numeric',
            'anticipoAnterior' => 'nullable|numeric',
            'saldo' => 'nullable|numeric',
            'checkboxes' => 'nullable|array',
            'vago_pres' => 'nullable|array',
            'vago_name' => 'nullable|array',
            'vago_cost' => 'nullable|array',
            'caba_pres' => 'nullable|array',
            'caba_name' => 'nullable|array',
            'caba_cost' => 'nullable|array',
            'bici_pres' => 'nullable|array',
            'bici_name' => 'nullable|array',
            'bici_cost' => 'nullable|array',
        ]);

        // Filtrar checkboxes seleccionados y no seleccionados
        $ticketsP = [];
        if (isset($paga['checkboxes'])) {
            foreach ($paga['checkboxes'] as $key => $checkbox) {
                if (!empty($checkbox['selected'])) { // Si está seleccionado
                    $ticketsP[] = [
                        'nombre' => $checkbox['nombre'],
                        'monto' => $checkbox['monto'],
                        'selected' => true,
                    ];
                }
            }
        }

        if ($request->dserv == 'vagoneta') {
            $servi_pago = array_map(function ($pres, $name, $cost) {
                return ['pres' => $pres, 'name' => $name, 'cost' => $cost];
            }, $request->vago_pres ?? [], $request->vago_name ?? [], $request->vago_cost ?? []);
        } elseif ($request->dserv == 'caballo') {
            $servi_pago = array_map(function ($pres, $name, $cost) {
                return ['pres' => $pres, 'name' => $name, 'cost' => $cost];
            }, $request->caba_pres ?? [], $request->caba_name ?? [], $request->caba_cost ?? []);
        } elseif ($request->dserv == 'bicicleta') {
            $servi_pago = array_map(function ($pres, $name, $cost) {
                return ['pres' => $pres, 'name' => $name, 'cost' => $cost];
            }, $request->bici_pres ?? [], $request->bici_name ?? [], $request->bici_cost ?? []);
        } else {
            $servi_pago = null;
        }

        $pago = new Porpago([
            'reserva_id' => $request->reserva_id,
            'tour_id' => $request->tour_id,
            'vagonetas' => $request->dserv == 'vagoneta' ? $servi_pago : null,
            'caballos' => $request->dserv == 'caballo' ? $servi_pago : null,
            'bicicletas' => $request->dserv == 'bicicleta' ? $servi_pago : null,
            'tickets' => $ticketsP,
            'anticipoActual' => $request->anticipoActual ?? null,
            'subtotal' => $request->subtotal ?? null,
            'prestatario' => $request->prestatario ?? null,
            'anticipoAnterior' => $request->anticipoAnterior ?? null,
            'saldo' => $request->saldo ?? null,
            'dserv' => $request->dserv,
        ]);

        $pago->save();

        return redirect()->back()->with('dserv', $request->dserv);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

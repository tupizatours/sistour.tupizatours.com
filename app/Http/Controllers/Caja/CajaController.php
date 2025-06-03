<?php

namespace App\Http\Controllers\Caja;

use App\Models\Caja\Caja;
use App\Models\Caja\MovimientoCaja;
use App\Models\Caja\CuentaCaja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CajaController extends Controller
{
    /**
     * Mostrar todas las cajas.
     */
    public function index()
    {
        $cajaAbierta = Caja::with('user')
            ->where('cerrada', false)
            ->latest()
            ->first();

        $ingresos = $cajaAbierta
            ? $cajaAbierta->movimientos()->where('tipo', 'ingreso')->sum('monto')
            : 0;

        $egresos = $cajaAbierta
            ? $cajaAbierta->movimientos()->where('tipo', 'egreso')->sum('monto')
            : 0;

        $saldoActual = $cajaAbierta
            ? $cajaAbierta->monto_inicial + $ingresos - $egresos
            : 0;

        return view('caja.index', compact('cajaAbierta', 'ingresos', 'egresos', 'saldoActual'));
    }




    /**
     * Abrir una nueva caja.
     */
    public function abrir(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        if (Caja::where('cerrada', false)->exists()) {
            return back()->with('error', 'Ya hay una caja abierta.');
        }

        Caja::create([
            'apertura' => now(),
            'monto_inicial' => $request->input('monto_inicial'),
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Caja abierta correctamente.');
    }

    /**
     * Cerrar la última caja abierta.
     */
    public function cerrar(Request $request)
    {
        $caja = Caja::where('cerrada', false)->latest()->first();

        if (!$caja) {
            return back()->with('error', 'No hay caja abierta.');
        }

        $totalIngresos = $caja->movimientos()->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $caja->movimientos()->where('tipo', 'egreso')->sum('monto');

        $caja->update([
            'cerrada' => true,
            'cierre' => now(),
            'monto_final' => $caja->monto_inicial + $totalIngresos - $totalEgresos,
        ]);

        return back()->with('success', 'Caja cerrada correctamente.');
    }

    /**
     * Mostrar una caja específica.
     */
    public function show(Caja $caja)
    {
        $caja->load('movimientos.cuenta', 'user');
        return view('pages.caja.show', compact('caja'));
    }


    /**
     * Mostrar movimientos.
     */

     public function movimientos()
     {
        $userId = auth()->id();
    
        // Buscar la caja abierta asociada al usuario actual
        $caja = Caja::where('user_id', $userId)->where('cerrada', false)->latest()->first();
    
        if (!$caja) {
            return redirect()->route('caja.index')->with('error', 'Debes abrir una caja primero.');
        }
    
        $tipo = request('tipo'); // ingreso | egreso | null
    
        $movimientos = $caja->movimientos()
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->latest()
            ->paginate(10);
    
        $cuentas = CuentaCaja::all(); // No filtrar por tipo para el modal

    
        return view('caja.movimientos', compact('caja', 'movimientos', 'cuentas'));
    }
     
    

    public function registrarMovimiento(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'cuenta_caja_id' => 'required|exists:cuentas_caja,id',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'nullable|string|max:255',
            'origen_id' => 'nullable|integer',
        ]);
    
        $caja = \App\Models\Caja\Caja::where('cerrada', false)->latest()->first();
    
        if (!$caja) {
            return redirect()->back()->with('error', 'Debe abrir una caja para registrar movimientos.');
        }
    
        MovimientoCaja::create([
            'caja_id'        => $caja->id,
            'cuenta_caja_id' => $request->cuenta_caja_id,
            'tipo'           => $request->tipo,
            'origen_id'      => $request->origen_id,
            'monto'          => $request->monto,
            'descripcion'    => $request->descripcion,
            'user_id'        => auth()->id(),
        ]);
    
        return redirect()->back()->with('success', 'Movimiento registrado correctamente.');
    }
    
}    

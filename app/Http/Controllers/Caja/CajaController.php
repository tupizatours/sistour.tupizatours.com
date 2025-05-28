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
        $caja = Caja::where('cerrada', false)->latest()->first();
    
        if (!$caja) {
            return redirect()->route('caja.index')->with('error', 'Debe abrir una caja primero.');
        }
    
        $tipo = request('tipo');

        $movimientos = $caja->movimientos()
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->latest()
            ->paginate(10);

    
        return view('caja.movimientos', compact('caja', 'movimientos'));
    }
}    

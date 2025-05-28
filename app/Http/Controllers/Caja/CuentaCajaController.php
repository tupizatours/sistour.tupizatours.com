<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja\CuentaCaja;

class CuentaCajaController extends Controller
{
    /**
     * Mostrar lista de cuentas contables.
     */
    public function index()
    {
        $cuentas = CuentaCaja::orderBy('tipo')->orderBy('nombre')->get();
        return view('pages.caja.cuentas.index', compact('cuentas'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('pages.caja.cuentas.create');
    }

    /**
     * Guardar una nueva cuenta contable.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:cuenta_cajas,nombre',
            'tipo' => 'required|in:ingreso,egreso',
        ]);

        CuentaCaja::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('cuentas.index')->with('success', 'Cuenta creada correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(CuentaCaja $cuenta)
    {
        if ($cuenta->protegida) {
            return redirect()->route('cuentas.index')->with('error', 'No se puede editar una cuenta protegida.');
        }

        return view('pages.caja.cuentas.edit', compact('cuenta'));
    }

    /**
     * Actualizar una cuenta existente.
     */
    public function update(Request $request, CuentaCaja $cuenta)
    {
        if ($cuenta->protegida) {
            return redirect()->route('cuentas.index')->with('error', 'No se puede editar una cuenta protegida.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100|unique:cuenta_cajas,nombre,' . $cuenta->id,
            'tipo' => 'required|in:ingreso,egreso',
        ]);

        $cuenta->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('cuentas.index')->with('success', 'Cuenta actualizada.');
    }

    /**
     * Eliminar una cuenta.
     */
    public function destroy(CuentaCaja $cuenta)
    {
        if ($cuenta->protegida) {
            return redirect()->route('cuentas.index')->with('error', 'No se puede eliminar una cuenta protegida.');
        }

        $cuenta->delete();
        return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada.');
    }
}

<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja\Porpago;

class MovimientoAjaxController extends Controller
{
    public function porpagosDisponibles(Request $request)
    {
        $subtipo = $request->subtipo;
    
        $query = Porpago::where('estado', 'pendiente');
    
        switch ($subtipo) {
            case 'con_bienes':
                $query->where('con_bienes', true)->where('es_prestatario', true);
                break;

            case 'sin_bienes':
                $query->where('con_bienes', false)->where('es_prestatario', true);
                break;

            case 'todos':
                $query->where('es_prestatario', true);
                break;

            default:
                return response()->json([], 200);
        }
    
        $registros = $query->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre_prestatario . " ({$p->tipo_servicio}) - ",
                'monto' => $p->costo,
            ];
        });

        return response()->json($registros);
    }
}

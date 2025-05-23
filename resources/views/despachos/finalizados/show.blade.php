@extends('layouts.app')

@section('template_title')
    Ver finalizado
@endsection

@section('estilos')
    <style>
        h4.title_dir {
            font-size: 18px;
            font-weight: 700;
            color: rgb(63, 66, 87);
        }
        .bg-moradito {
            border: 1px dashed rgb(98, 95, 241);
        }
        .card-infos, dt {
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .form_cantidad {
            max-width: 60px;
        }
        .input-spinner .btn-white {
            width: 40px;
        }
        .form_tran .form-control {
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
<div class="main-body">
    <div class="row">
        <div class="col-lg-12">

            {{-- INFORMACIÓN DE LA RESERVA --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Datos de la Reserva</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <dl class="col-md-3"><dt>Código</dt><dd>{{ $reserva->codigo }}</dd></dl>
                        <dl class="col-md-3"><dt>Tour</dt><dd>{{ $reserva->tour->titulo }}</dd></dl>
                        <dl class="col-md-2"><dt>Min/Max</dt><dd>{{ $reserva->tour->min_per }}/{{ $reserva->can_pri }}</dd></dl>
                        <dl class="col-md-2">
                            <dt>Total Pagado</dt>
                            <dd>
                                @php
                                    use App\Models\Venta\Pago;
                                    $totalPagado = $resclis->sum(fn($r) => Pago::where('rescli_id', $r->id)->where('estatus', 1)->sum('conversion'));
                                @endphp
                                Bs. {{ number_format($totalPagado, 2, '.', ',') }}
                            </dd>
                        </dl>
                        <dl class="col-md-2"><dt>Personas</dt><dd>{{ $reserva->can_pri }}</dd></dl>
                    </div>
                </div>
            </div>

            {{-- TURISTAS --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Turistas Registrados</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Doc</th>
                                    <th>País</th>
                                    <th>Edad</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resclis as $i => $r)
                                    @php
                                        $pagado = Pago::where('rescli_id', $r->id)->where('estatus', 1)->sum('conversion');
                                        $total = $r->total ?? ($r->esPrincipal
                                            ? $reserva->total - (($reserva->can_per - 1) * $reserva->pre_per)
                                            : $r->pre_per);
                                        $saldo = $total - $pagado;
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $r->nombres }} {{ $r->apellidos }}</td>
                                        <td>{{ $r->documento }}</td>
                                        <td>{{ $r->nacionalidad }}</td>
                                        <td>{{ $r->edad ?? '-' }}</td>
                                        <td>{{ $r->celular }}</td>
                                        <td>{{ $r->correo }}</td>
                                        <td class="text-right">Bs. {{ number_format($total, 2, '.', ',') }}</td>
                                        <td class="text-right">Bs. {{ number_format($pagado, 2, '.', ',') }}</td>
                                        <td class="text-right">Bs. {{ number_format($saldo, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- GESTIÓN OPERATIVA --}}
            @if($gestion)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Gestión Operativa</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tbody>
                                @if($gestion->guia_id)
                                    <tr><th>Guía</th><td>{{ optional($gestion->guia)->nombre }}</td></tr>
                                @endif
                                @if($gestion->traductor_id)
                                    <tr><th>Traductor</th><td>{{ optional($gestion->traductor)->nombre }}</td></tr>
                                @endif
                                @if($gestion->chofer_id)
                                    <tr>
                                        <th>Chofer</th>
                                        <td>
                                            {{ optional($gestion->chofer)->nombre }}
                                            @if($gestion->chofer->licencia)
                                                <br><small class="text-muted">Licencia: {{ $gestion->chofer->licencia }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($gestion->vagoneta_id)
                                    <tr>
                                        <th>Vagoneta</th>
                                        <td>
                                            {{ optional($gestion->vagoneta)->marca }}
                                            @if($gestion->vagoneta->patente)
                                                <br><small class="text-muted">Patente: {{ $gestion->vagoneta->patente }}</small>
                                            @endif
                                            @if($gestion->provag)
                                                <br><small class="text-muted">Prestatario: {{ $gestion->provag->nombre }} {{ $gestion->provag->apellido }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($gestion->caballo_id)
                                    <tr>
                                        <th>Caballo</th>
                                        <td>
                                            {{ optional($gestion->caballo)->nombre }}
                                            @if($gestion->procab)
                                                <br><small class="text-muted">Prestatario: {{ $gestion->procab->nombre }} {{ $gestion->procab->apellido }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($gestion->bicicleta_id)
                                    <tr>
                                        <th>Bicicleta</th>
                                        <td>
                                            {{ optional($gestion->bicicleta)->nombre }}
                                            @if($gestion->probic)
                                                <br><small class="text-muted">Prestatario: {{ $gestion->probic->nombre }} {{ $gestion->probic->apellido }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
    
@endsection
@extends('layouts.app')

@section('template_title')
    Ver en tránsito
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
                <div class="card">
                    <div class="card-header bg-transparent pt-3 pb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 title_page">RESERVA</h6>
                            </div>
                        </div>
                    </div>

                    <?php
                        use App\Models\Venta\Pago;
                    ?>

                    <div class="card-body">
                        <h6 class="card-title mb-4">
                            Salida del tour: {{ $reserva->fecha }}
                        </h6>

                        <div class="row mt-4">
                            <dl class="col-md-2">
                                <dt class="col-sm-12">Código de reserva</dt>
                                <dd class="col-sm-12">{{ $reserva->codigo }}</dd>
                            </dl>

                            <dl class="col-md-3">
                                <dt class="col-sm-12">Nombre del tour</dt>
                                <dd class="col-sm-12">{{ $reserva->tour->titulo }}</dd>
                            </dl>

                            <dl class="col-md-2">
                                <dt class="col-sm-12">Capacidad Min/Max</dt>
                                <dd class="col-sm-12">
                                    {{ $reserva->tour->min_per.'/' }}
                                    <span id="aumen_pers">{{ $reserva->can_pri }}</span>
                                </dd>
                            </dl>

                            <dl class="col-md-2">
                                <dt class="col-sm-12">Total Pagado</dt>
                                <dd class="col-sm-12">
                                    @php $tot_dir = 0; @endphp

                                    @foreach($resclis as $rescli)
                                        @if($rescli->estatus == "1")
                                            @php
                                                $sumaMonto = Pago::where('rescli_id', $rescli->id)
                                                                ->where('estatus', 1)
                                                                ->sum('conversion');

                                                $tot_dir += $sumaMonto;
                                            @endphp
                                        @endif
                                    @endforeach

                                    {{ 'Bs. '.number_format($tot_dir, 2, '.', ',') }}
                                </dd>
                            </dl>

                            <dl class="col-md-3">
                                <form action="{{ route('venreservas.update', $reserva->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                
                                    <dt class="col-sm-12">Capacidad</dt>
                                    <dd class="col-sm-12">{{ $reserva->can_pri.' personas' }}</dd>

                                    <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id" />
                                    <input type="hidden" value="reservas" id="reservas" name="reservas" />
                                </form>
                            </dl>
                        </div>

                        <div class="row">
                            @if($gestion)
                                <dl class="col-md-2">
                                    <form action="{{ route('desfin.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id">

                                        <button type="submit" class="btn btn-success col-md-12">Finalizar</button>
                                    </form>
                                </dl>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-transparent pt-3 pb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 title_page">LISTADO DE TURISTAS</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table">
                                <thead class="">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Solicitud</th>
                                        <th>Nombres y apellidos</th>
                                        <th>Nacionalidad</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Total</th>
                                        <th>Pagado</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($resclis as $rescli)
                                        @if($rescli->estatus == "1")
                                            @php
                                                $originalDate = $rescli->created_at;
                                                $newDate = date("d-m-Y", strtotime($originalDate));
                                            @endphp

                                            <tr>
                                                <td style="text-transform: uppercase;">
                                                    @if($rescli->estado == 1)
                                                        {{ $reserva->codigo }}
                                                    @elseif($rescli->estado == 2)
                                                        {{ $rescli->codigo }}
                                                    @endif
                                                </td>

                                                <td>{{ $newDate }}</td>
                                                <td>{{ $rescli->nombres.' '.$rescli->apellidos }}</td>
                                                <td>{{ $rescli->nacionalidad }}</td>
                                                <td>@if($rescli->edad) {{ $rescli->edad.' años' }} @endif</td>
                                                <td>{{ $rescli->sexo }}</td>
                                                <td>{{ $rescli->celular }}</td>
                                                <td>{{ $rescli->correo }}</td>
                                                
                                                <td>
                                                    @if($rescli->esPrincipal)
                                                        @php
                                                            $pag_tot = ($reserva->total - (($reserva->can_per - 1) * $reserva->pre_per));
                                                            $pagado = $pag_tot - $rescli->pagado;
                                                        @endphp

                                                        {{ 'Bs. '.number_format($pag_tot, 2, '.', ',') }}
                                                    @else
                                                        @if($rescli->total)
                                                            {{ 'Bs. '.number_format($rescli->total, 2, '.', ',') }}
                                                        @else
                                                            {{ 'Bs. '.number_format($rescli->pre_per, 2, '.', ',') }}
                                                        @endif
                                                    @endif
                                                </td>

                                                @php
                                                    $sumaMonto = Pago::where('rescli_id', $rescli->id)->sum('conversion');
                                                @endphp

                                                <td>{{ 'Bs. '.number_format($sumaMonto, 2, '.', ',') }}</td>
                                                
                                                <td>
                                                    @if($rescli->esPrincipal)
                                                        @php
                                                            $pag_tot = ($reserva->total - (($reserva->can_per - 1) * $reserva->pre_per));
                                                            $pagado = $pag_tot - $rescli->pagado;
                                                        @endphp

                                                        {{ 'Bs. '.number_format($pag_tot - $sumaMonto, 2, '.', ',') }}
                                                    @else
                                                        @if($rescli->total)
                                                            {{ 'Bs. '.number_format($rescli->total - $sumaMonto, 2, '.', ',') }}
                                                        @else
                                                            {{ 'Bs. '.number_format($rescli->pre_per - $sumaMonto, 2, '.', ',') }}
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if($gestion)
                            <form action="{{ route('desges.update', $gestion->id) }}" class="form_tran" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" value="gestions" name="pagina" id="pagina" />
                                <input type="hidden" value="{{ $reserva->id }}" name="reserva_id" id="reserva_id" />
                                <input type="hidden" value="{{ $reserva->tour_id }}" name="tour_id" id="tour_id" />
                            
                                <div class="row g-3 pt-3 pb-2 col-md-6">
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label class="mb-2">Elegir servicio</label>
                                        <select class="form-control form-control-solid" id="servicio_id" name="servicio_id" type="select" onchange="servicioCosto()">
                                            <option value="{{ $gestion->servicio->id }}" data-tarifa="{{ number_format($gestion->servicio->costo, 2, '.', '') }}">{{ $gestion->servicio->titulo }}</option>
                                            <option value="">Seleccionar</option>
                                            
                                            @foreach($tours as $tour)
                                                @php
                                                    $serv_tour_id = json_decode($tour->serv_tour);
                                                @endphp

                                                @if($tour->id == $reserva->tour_id)
                                                    @foreach($serv_tour_id as $value)
                                                        @foreach($servicios as $servicio)
                                                            @if($value == $servicio->id)
                                                                <option value="{{ $servicio->id }}" data-tarifa="{{ number_format($servicio->costo, 2, '.', '') }}">{{ $servicio->titulo }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label class="mb-2">Precio costo</label>
                                        <input class="form-control form-control-solid" id="servicio_t" name="servicio_t" type="number" value="{{ $gestion->servicio_t }}" />
                                    </div>

                                    @foreach($tours as $tour)
                                        @php
                                            $serv_tour_id = json_decode($tour->serv_tour);
                                        @endphp

                                        @if($tour->id == $reserva->tour_id)
                                            @foreach($serv_tour_id as $value)
                                                @if($value == 100)
                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Elegir guia</label>

                                                        <select class="form-control form-control-solid" id="guia_id" name="guia_id" type="text" required onchange="mostrarCosto()">
                                                            <option value="{{ $gestion->guia->id }}" data-tarifa="{{ number_format($gestion->guia->tarifa, 2, '.', '') }}">{{ $gestion->guia->nombre.' '.$gestion->guia->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($guias as $guia)
                                                                <option value="{{ $guia->id }}" data-tarifa="{{ number_format($guia->tarifa, 2, '.', '') }}">{{ $guia->nombre.' '.$guia->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="guia_t" name="guia_t" type="number" value="{{ $gestion->guia_t }}" />
                                                    </div>
                                                @elseif($value == 101)
                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Elegir traductor</label>

                                                        <select class="form-control form-control-solid" id="traductor_id" name="traductor_id" type="select" onchange="traductorCosto()">
                                                            <option value="{{ $gestion->traductor->id }}" data-tarifa="{{ number_format($gestion->traductor->tarifa, 2, '.', '') }}">{{ $gestion->traductor->nombre.' '.$gestion->traductor->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($traductors as $traductor)
                                                                <option value="{{ $traductor->id }}" data-tarifa="{{ number_format($traductor->tarifa, 2, '.', '') }}">{{ $traductor->nombre.' '.$traductor->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="traductor_t" name="traductor_t" type="number" value="{{ $gestion->traductor_t }}" />
                                                    </div>
                                                @elseif($value == 102)
                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Elegir cocinero</label>

                                                        <select class="form-control form-control-solid" id="cocinero_id" name="cocinero_id" type="select" onchange="cocineroCosto()">
                                                            <option value="{{ $gestion->cocinero->id }}" data-tarifa="{{ number_format($gestion->cocinero->tarifa, 2, '.', '') }}">{{ $gestion->cocinero->nombre.' '.$gestion->cocinero->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($cocineros as $cocinero)
                                                                <option value="{{ $cocinero->id }}" data-tarifa="{{ number_format($cocinero->tarifa, 2, '.', '') }}">{{ $cocinero->nombre.' '.$cocinero->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="cocinero_t" name="cocinero_t" type="number" value="{{ $gestion->cocinero_t }}" />
                                                    </div>
                                                @elseif($value == 103)
                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Elegir chofer</label>

                                                        <select class="form-control form-control-solid" id="chofer_id" name="chofer_id" type="select" onchange="choferCosto()">
                                                            <option value="{{ $gestion->chofer->id }}" data-tarifa="{{ number_format($gestion->chofer->tarifa, 2, '.', '') }}">{{ $gestion->chofer->nombre.' '.$gestion->chofer->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($chofers as $chofer)
                                                                <option value="{{ $chofer->id }}" data-tarifa="{{ number_format($chofer->tarifa, 2, '.', '') }}">{{ $chofer->nombre.' '.$chofer->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-6">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="chofer_t" name="chofer_t" type="number" value="{{ $gestion->chofer_t }}" />
                                                    </div>
                                                @elseif($value == 104)
                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir prestatario</label>

                                                        <select class="form-control form-control-solid" id="provag_id" name="provag_id" type="select" required onchange="cargarVagonetas(this.value)">
                                                            <option value="{{ $gestion->provag->id }}">{{ $gestion->provag->nombre.' '.$gestion->provag->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($propietarios as $propietario)
                                                                <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir vagoneta</label>

                                                        <select class="form-control form-control-solid" id="vagoneta_id" name="vagoneta_id" type="select" onchange="vagonetaCosto()">
                                                            <option value="{{ $gestion->vagoneta->id }}" data-tarifa="{{ number_format($gestion->vagoneta->costo, 2, '.', '') }}">{{ $gestion->vagoneta->marca }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($vagonetas as $vagoneta)
                                                                <option value="{{ $vagoneta->id }}" data-tarifa="{{ number_format($vagoneta->costo, 2, '.', '') }}">{{ $vagoneta->marca }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="vagoneta_t" name="vagoneta_t" type="number" value="{{ $gestion->vagoneta_t }}" />
                                                    </div>
                                                @elseif($value == 105)
                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir prestatario</label>

                                                        <select class="form-control form-control-solid" id="procab_id" name="procab_id" type="select" required onchange="cargarCaballos(this.value)">
                                                            <option value="{{ $gestion->procab->id }}">{{ $gestion->procab->nombre.' '.$gestion->procab->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($propietarios as $propietario)
                                                                <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir caballo</label>

                                                        <select class="form-control form-control-solid" id="caballo_id" name="caballo_id" type="select" onchange="caballoCosto()">
                                                            <option value="{{ $gestion->caballo->id }}" data-tarifa="{{ number_format($gestion->caballo->costo, 2, '.', '') }}">{{ $gestion->caballo->nombre }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($caballos as $caballo)
                                                                <option value="{{ $caballo->id }}" data-tarifa="{{ number_format($caballo->costo, 2, '.', '') }}">{{ $caballo->nombre }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="caballo_t" name="caballo_t" type="number" value="{{ $gestion->caballo_t }}" />
                                                    </div>
                                                @elseif($value == 106)
                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir prestatario</label>

                                                        <select class="form-control form-control-solid" id="probic_id" name="probic_id" type="select" required onchange="cargarBicicletas(this.value)">
                                                            <option value="{{ $gestion->probic->id }}">{{ $gestion->probic->nombre.' '.$gestion->probic->apellido }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($propietarios as $propietario)
                                                                <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Elegir bicicleta</label>

                                                        <select class="form-control form-control-solid" id="bicicleta_id" name="bicicleta_id" type="select" onchange="bicicletaCosto()">
                                                            <option value="{{ $gestion->bicicleta->id }}" data-tarifa="{{ number_format($gestion->bicicleta->costo, 2, '.', '') }}">{{ $gestion->bicicleta->nombre }}</option>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($bicicletas as $bicicleta)
                                                                <option value="{{ $bicicleta->id }}" data-tarifa="{{ number_format($bicicleta->costo, 2, '.', '') }}">{{ $bicicleta->nombre }}</option>
                                                            @endforeach    
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2 mt-2 col-md-4">
                                                        <label class="mb-2">Precio costo</label>
                                                        <input class="form-control form-control-solid" id="bicicleta_t" name="bicicleta_t" type="number" value="{{ $gestion->bicicleta_t }}" />
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    
@endsection
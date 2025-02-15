@extends('layouts.app')

@section('template_title')
    Ver gestion
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
        .data_disabled {
            position: absolute;
            opacity: 0.4;
            z-index: 2;
            top: 0;
            bottom: 0;
        }
        .prelative {
            position: relative;
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
                                <dt class="col-sm-12">Capacidad</dt>
                                <dd class="col-sm-12">{{ $reserva->can_pri }} </dd>
                            </dl>
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
                                        <th>País</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Total</th>
                                        <th>Pagado</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $saldoPagado = 0
                                    @endphp
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
                                                <td>@if($rescli->edad) {{ $rescli->edad }} @endif</td>
                                                <td>{{ $rescli->sexo }}</td>
                                                <td>{{ $rescli->celular }}</td>
                                                <td>{{ $rescli->correo }}</td>
                                                
                                                <td>
                                                    @if($rescli->esPrincipal)
                                                        @php
                                                            $pag_tot = ($reserva->total - (($reserva->can_per - 1) * $reserva->pre_per));
                                                            $saldoPagado += $pag_tot;
                                                            $pagado = $pag_tot - $rescli->pagado;
                                                        @endphp

                                                        {{ 'Bs. '.number_format($pag_tot, 2, '.', '') }}
                                                    @else
                                                        @if($rescli->total)
                                                            {{ 'Bs. '.number_format($rescli->total, 2, '.', ',') }}
                                                            @php $saldoPagado += $rescli->total; @endphp
                                                        @else
                                                            {{ 'Bs. '.number_format($rescli->pre_per, 2, '.', ',') }}
                                                            @php $saldoPagado += $rescli->pre_per; @endphp
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
                                                
                                                <td>
                                                    <div class="d-flex order-actions">
                                                        @if($rescli->esPrincipal == "1")
                                                            
                                                        @else
                                                            <a href="{{ URL::to('ventas/resclis/user/' . $rescli->id) }}" target="_BLANK" class="">
                                                                <i class="bx bxs-user"></i>
                                                            </a>
                                                        @endif

                                                        <a href="{{ URL::to('ventas/resclis/' . $rescli->id . '/edit') }}" class="">
                                                            <i class="bx bxs-edit"></i>
                                                        </a>

                                                        <a href="{{ URL::to('ventas/resclis/' . $rescli->id) }}" class="ms-1">
                                                            <i class="fadeIn animated bx bx-dollar-circle"></i>
                                                        </a>

                                                        <form action="{{ route('estatus.update', $reserva->id) }}" class="ms-1" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <button type="button" class="btn boton-eliminar" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $reserva->id }}">
                                                                <i class="bx bxs-trash"></i>
                                                            </button>

                                                            <input type="hidden" value="2" id="estatus" name="estatus" />
                                                            <input type="hidden" value="solicitudes" id="pagina" name="pagina" />

                                                            @include('ventas.solicitudes.predelete')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-transparent pt-3 pb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 title_page">SELECCIONAR PRESTATARIOS</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($gestion)
                            <form action="{{ route('desges.update', $gestion->id) }}" class="" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" value="gestions" name="pagina" id="pagina" />
                                <input type="hidden" value="{{ $reserva->id }}" name="reserva_id" id="reserva_id" />
                                <input type="hidden" value="{{ $reserva->tour_id }}" name="tour_id" id="tour_id" />
                            
                                @php
                                    $serv_tour_id = json_decode($reserva->tour->serv_tour);
                                @endphp

                                @if($reserva->tour->id == $reserva->tour_id && $servicios->isNotEmpty())
                                    <div class="row g-3 pt-3 pb-2 col-md-12">
                                        <div class="form-group mb-2 mt-2 col-md-6">
                                            <label class="mb-2">Elegir servicio</label>
                                            <select class="form-control form-control-solid" id="servicio_id" name="servicio_id" type="select" onchange="servicioCosto()">
                                                <option value="">Seleccionar</option>
                                                @foreach($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}" @if($servicio->id == $gestion->servicio_id) selected @endif data-tarifa="{{ number_format($servicio->costo, 2, '.', '') }}">{{ $servicio->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="form-group mb-2 mt-2 col-md-6">
                                            <label class="mb-2">Precio costo</label>
                                            <input class="form-control form-control-solid" id="servicio_t" name="servicio_t" type="number" value="{{ $gestion->servicio_t }}" />
                                        </div>
                                    </div>
                                @endif

                                @if($reserva->tour->id == $reserva->tour_id)
                                    @foreach($serv_tour_id as $value)
                                        @if($value == 100)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
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
                                            </div>
                                        @elseif($value == 101)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
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
                                            </div>
                                        @elseif($value == 102)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
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
                                            </div>
                                        @elseif($value == 103)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
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
                                            </div>
                                        @elseif($value == 104)
                                            <div class="row g-3 pt-3 pb-2 col-md-12 prelative">
                                                @foreach($existePorpago as $porpago)
                                                    @if($porpago->dserv == 'vagoneta')
                                                        <div class="alert alert-success data_disabled"></div>
                                                    @endif
                                                @endforeach
                                                
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
                                                    <label for="check_vago" class="mb-2">Precio costo</label> <input class="form-check-input" type="checkbox" id="check_vago" name="check_pres" data-pres="{{ $gestion->provag->id }}" data-serv="vagoneta" data-servid="{{ $gestion->vagoneta->id }}" data-target="{{ $gestion->vagoneta_t }}" data-exclusive="true" />
                                                    <input class="form-control form-control-solid" id="vagoneta_t" name="vagoneta_t" type="number" value="{{ $gestion->vagoneta_t }}" />
                                                </div>
                                            </div>
                                        @elseif($value == 105)
                                            <div class="row g-3 pt-3 pb-2 col-md-12 prelative">
                                                @foreach($existePorpago as $porpago)
                                                    @if($porpago->dserv == 'caballo')
                                                        <div class="alert alert-success data_disabled"></div>
                                                    @endif
                                                @endforeach

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
                                                    <label for="check_caba" class="mb-2">Precio costo</label> <input class="form-check-input" type="checkbox" id="check_caba" name="check_pres" data-pres="{{ $gestion->procab->id }}" data-serv="caballo" data-servid="{{ $gestion->caballo->id }}" data-target="{{ $gestion->caballo_t }}" data-exclusive="true" />
                                                    <input class="form-control form-control-solid" id="caballo_t" name="caballo_t" type="number" value="{{ $gestion->caballo_t }}" />
                                                </div>
                                            </div>
                                        @elseif($value == 106)
                                            <div class="row g-3 pt-3 pb-2 col-md-12 prelative">
                                                @if($existePorpago)
                                                    @if($existePorpago->dserv == 'bicicleta')
                                                        <div class="alert alert-success data_disabled"></div>
                                                    @endif
                                                @endif

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
                                                    <label for="check_bici" class="mb-2">Precio costo</label> <input class="form-check-input" type="checkbox" id="check_bici" name="check_pres" data-pres="{{ $gestion->probic->id }}" data-serv="bicicleta" data-servid="{{ $gestion->bicicleta->id }}" data-target="{{ $gestion->bicicleta_t }}" data-exclusive="true" />
                                                    <input class="form-control form-control-solid" id="bicicleta_t" name="bicicleta_t" type="number" value="{{ $gestion->bicicleta_t }}" />
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="row g-3 pt-3 pb-2 col-md-12">
                                    <div class="form-group mb-2 mt-2 col-md-12">
                                        <button type="submit" class="btn btn-success col-md-12 font-14">ACTUALIZAR</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                <form action="{{ route('destra.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id">

                                    <button type="submit" class="btn btn-success col-md-12 text-uppercase">Iniciar Tour</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('desges.store') }}" class="" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" value="gestions" name="pagina" id="pagina" />
                                <input type="hidden" value="{{ $reserva->id }}" name="reserva_id" id="reserva_id" />
                                <input type="hidden" value="{{ $reserva->tour_id }}" name="tour_id" id="tour_id" />

                                @php
                                    $serv_tour_id = json_decode($reserva->tour->serv_tour);
                                @endphp

                                @if($reserva->tour->id == $reserva->tour_id && $servicios->isNotEmpty())
                                    <div class="row g-3 pt-3 pb-2 col-md-12">
                                        <div class="form-group mb-2 mt-2 col-md-6">
                                            <label class="mb-2">Elegir servicio</label>
                                            <select class="form-control form-control-solid" id="servicio_id" name="servicio_id" type="select" onchange="servicioCosto()">
                                                <option value="">Seleccionar</option>
                                                @foreach($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}" data-tarifa="{{ number_format($servicio->costo, 2, '.', '') }}">{{ $servicio->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="form-group mb-2 mt-2 col-md-6">
                                            <label class="mb-2">Precio costo</label>
                                            <input class="form-control form-control-solid" id="servicio_t" name="servicio_t" type="number" />
                                        </div>
                                    </div>
                                @endif
                            
                                <div class="row g-3 pt-3 pb-2 col-md-12">
                                    @if($reserva->tour->id == $reserva->tour_id)
                                        @foreach($serv_tour_id as $value)
                                            @if($value == 100)
                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Elegir guia</label>

                                                    <select class="form-control form-control-solid" id="guia_id" name="guia_id" type="text" required onchange="mostrarCosto()">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($guias as $guia)
                                                            <option value="{{ $guia->id }}" data-tarifa="{{ number_format($guia->tarifa, 2, '.', '') }}">{{ $guia->nombre.' '.$guia->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="guia_t" name="guia_t" type="number" />
                                                </div>
                                            @elseif($value == 101)
                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Elegir traductor</label>

                                                    <select class="form-control form-control-solid" id="traductor_id" name="traductor_id" type="select" onchange="traductorCosto()">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($traductors as $traductor)
                                                            <option value="{{ $traductor->id }}" data-tarifa="{{ number_format($traductor->tarifa, 2, '.', '') }}">{{ $traductor->nombre.' '.$traductor->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="traductor_t" name="traductor_t" type="number" />
                                                </div>
                                            @elseif($value == 102)
                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Elegir cocinero</label>

                                                    <select class="form-control form-control-solid" id="cocinero_id" name="cocinero_id" type="select" onchange="cocineroCosto()">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($cocineros as $cocinero)
                                                            <option value="{{ $cocinero->id }}" data-tarifa="{{ number_format($cocinero->tarifa, 2, '.', '') }}">{{ $cocinero->nombre.' '.$cocinero->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="cocinero_t" name="cocinero_t" type="number" />
                                                </div>
                                            @elseif($value == 103)
                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Elegir chofer</label>

                                                    <select class="form-control form-control-solid" id="chofer_id" name="chofer_id" type="select" onchange="choferCosto()">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($chofers as $chofer)
                                                            <option value="{{ $chofer->id }}" data-tarifa="{{ number_format($chofer->tarifa, 2, '.', '') }}">{{ $chofer->nombre.' '.$chofer->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-6">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="chofer_t" name="chofer_t" type="number" />
                                                </div>
                                            @elseif($value == 104)
                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir prestatario</label>
                                                
                                                    <select class="form-control form-control-solid" id="provag_id" name="provag_id" type="select" onchange="cargarVagonetas(this.value)">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($propietarios as $propietario)
                                                            <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir vagoneta</label>

                                                    <select class="form-control form-control-solid" id="vagoneta_id" name="vagoneta_id" type="select" onchange="vagonetaCosto()">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="vagoneta_t" name="vagoneta_t" type="number" />
                                                </div>
                                            @elseif($value == 105)
                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir prestatario</label>
                                                
                                                    <select class="form-control form-control-solid" id="procab_id" name="procab_id" type="select" onchange="cargarCaballos(this.value)">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($propietarios as $propietario)
                                                            <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir caballo</label>

                                                    <select class="form-control form-control-solid" id="caballo_id" name="caballo_id" type="select" onchange="caballoCosto()">
                                                        <option value="">Seleccionar</option> 
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="caballo_t" name="caballo_t" type="number" />
                                                </div>
                                            @elseif($value == 106)
                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir prestatario</label>

                                                    <select class="form-control form-control-solid" id="probic_id" name="probic_id" type="select" onchange="cargarBicicletas(this.value)">
                                                        <option value="">Seleccionar</option>
                                                        @foreach($propietarios as $propietario)
                                                            <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Elegir bicicleta</label>

                                                    <select class="form-control form-control-solid" id="bicicleta_id" name="bicicleta_id" type="select" onchange="bicicletaCosto()">
                                                        <option value="">Seleccionar</option>  
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2 mt-2 col-md-4">
                                                    <label class="mb-2">Precio costo</label>
                                                    <input class="form-control form-control-solid" id="bicicleta_t" name="bicicleta_t" type="number" />
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="row g-3 pt-3 pb-2 col-md-12">
                                    <div class="form-group mb-2 mt-2 col-md-12">
                                    @if($saldoPagado == $tot_dir)
                                        <button type="submit" class="btn btn-primary col-md-12 font-14">GUARDAR</button>
                                        @else
                                        <strong style="color:red">DEBE PAGAR EL SALDO DE LA RESERVA</strong>
                                    @endif
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <form action="{{ route('cajacobros.store') }}" class="" id="formulario" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card">
                        <div class="card-header bg-transparent pt-3 pb-3">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 title_page form-check">
                                        <input type="hidden" name="checkboxes[check_todos][nombre]" value="Totalidad" />
                                        <input type="hidden" name="checkboxes[check_todos][monto]" value="{{ $totalGeneralGasto }}" />

                                        <input class="form-check-input" type="checkbox" data-nombre="Totalidad" data-monto="{{ $totalGeneralGasto }}" id="check_todos" name="checkboxes[check_todos][selected]" />
                                        <label class="form-check-label" for="check_todos">DETALLES DE TOTALIDAD</label>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <dl class="col-md-12 row mt-2">
                                <dt class="col-sm-9">
                                    <div class="form-check">
                                        <input type="hidden" name="checkboxes[check_hotel][nombre]" value="Hoteles" />
                                        <input type="hidden" name="checkboxes[check_hotel][monto]" value="{{ $totalGeneralHoteles }}" />

                                        <input class="form-check-input" type="checkbox" data-nombre="Hoteles" data-monto="{{ $totalGeneralHoteles }}" id="check_hotel" name="checkboxes[check_hotel][selected]" data-group="right" />
                                        <label class="form-check-label" for="check_hotel">Total Hoteles</label>
                                    </div>
                                </dt>

                                <dd class="col-sm-3 text-right">{{ 'Bs. '.number_format($totalGeneralHoteles, 2) }}</dd>
                            </dl>

                            <dl class="col-md-12 row">
                                <dt class="col-sm-9">
                                    <div class="form-check">
                                        <input type="hidden" name="checkboxes[check_ticket][nombre]" value="Tickets" />
                                        <input type="hidden" name="checkboxes[check_ticket][monto]" value="{{ $totalGeneralTickets }}" />

                                        <input class="form-check-input" type="checkbox" data-nombre="Tickets" data-monto="{{ $totalGeneralTickets }}" id="check_ticket" name="checkboxes[check_ticket][selected]" data-group="right" />
                                        <label class="form-check-label" for="check_ticket">Total Tickets</label>
                                    </div>
                                </dt>

                                <dd class="col-sm-3 text-right">{{ 'Bs. '.number_format($totalGeneralTickets, 2) }}</dd>
                            </dl>

                            <dl class="col-md-12 row">
                                <dt class="col-sm-9">
                                    <div class="form-check">
                                        <input type="hidden" name="checkboxes[check_accesorio][nombre]" value="Accesorios" />
                                        <input type="hidden" name="checkboxes[check_accesorio][monto]" value="{{ $totalGeneralAccesorios }}" />

                                        <input class="form-check-input" type="checkbox" data-nombre="Accesorios" data-monto="{{ $totalGeneralAccesorios }}" id="check_accesorio" name="checkboxes[check_accesorio][selected]" data-group="right" />
                                        <label class="form-check-label" for="check_accesorio">Total Accesorios</label>
                                    </div>
                                </dt>

                                <dd class="col-sm-3 text-right">{{ 'Bs. '.number_format($totalGeneralAccesorios, 2) }}</dd>
                            </dl>

                            <dl class="col-md-12 row mb-0">
                                <dt class="col-sm-9">
                                    <div class="form-check">
                                        <input type="hidden" name="checkboxes[check_servicio][nombre]" value="Servicios" />
                                        <input type="hidden" name="checkboxes[check_servicio][monto]" value="{{ $totalGeneralServicios }}" />

                                        <input class="form-check-input" type="checkbox" data-nombre="Servicios" data-monto="{{ $totalGeneralServicios }}" id="check_servicio" name="checkboxes[check_servicio][selected]" data-group="right" />
                                        <label class="form-check-label" for="check_servicio">Total Servicios</label>
                                    </div>
                                </dt>

                                <dd class="col-sm-3 text-right">{{ 'Bs. '.number_format($totalGeneralServicios, 2) }}</dd>
                            </dl>
                        </div>

                        <div class="card-footer">
                            <dl class="col-md-12 row mb-0">
                                <dt class="col-sm-9">
                                    <div class="form-check">
                                        <label class="form-check-label">Totalidad de Gastos</label>
                                    </div>
                                </dt>

                                <dd class="col-sm-3 text-right">{{ 'Bs. ' . number_format($totalGeneralGasto, 2) }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($gestion)
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" value="gestions" name="pagina" id="pagina" />
                                <input type="hidden" value="{{ $reserva->id }}" name="reserva_id" id="reserva_id" />
                                <input type="hidden" value="{{ $reserva->tour_id }}" name="tour_id" id="tour_id" />

                                <div class="row g-3 pt-3 pb-2 col-md-12">
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="anticipoActual" class="mb-2"><b>Anticipos actual</b></label>
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <input class="form-control form-control-solid" id="anticipoActual" name="anticipoActual" type="number" />
                                    </div>

                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="subtotal" class="mb-2"><b>Subtotal</b></label>
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <input class="form-control form-control-solid" id="subtotal" name="subtotal" type="number" />
                                    </div>

                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="prestatario" class="mb-2"><b>Prestatario</b></label>
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <input class="form-control form-control-solid" id="prestatario" name="prestatario" type="number" />
                                    </div>

                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="anticipoAnterior" class="mb-2"><b>Anticipos anteriores</b></label>
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <input class="form-control form-control-solid" id="anticipoAnterior" name="anticipoAnterior" type="number" />
                                    </div>

                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <label for="saldo" class="mb-2"><b>Saldo</b></label>
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-6">
                                        <input class="form-control form-control-solid" id="saldo" name="saldo" type="number" />
                                    </div>

                                    <input type="hidden" id="dpres" name="dpres" />
                                    <input type="hidden" id="dserv" name="dserv" />
                                    <input type="hidden" id="dserid" name="dserid" />

                                    <div id="campos-dinamicos">
                                        <input type="hidden" id="input-servicio_id" name="serv_name[]" readonly />
                                        <input type="hidden" id="input-servicio_t" name="serv_cost[]" readonly />

                                        <input type="hidden" id="input-guia_id" name="guia_name[]" readonly />
                                        <input type="hidden" id="input-guia_t" name="guia_cost[]" readonly />

                                        <input type="hidden" id="input-traductor_id" name="trad_name[]" readonly />
                                        <input type="hidden" id="input-traductor_t" name="trad_cost[]" readonly />

                                        <input type="hidden" id="input-cocinero_id" name="coci_name[]" readonly />
                                        <input type="hidden" id="input-cocinero_t" name="coci_cost[]" readonly />

                                        <input type="hidden" id="input-chofer_id" name="chof_name[]" readonly />
                                        <input type="hidden" id="input-chofer_t" name="chof_cost[]" readonly />

                                        <input type="hidden" id="input-provag_id" name="vago_pres[]" readonly />
                                        <input type="hidden" id="input-vagoneta_id" name="vago_name[]" readonly />
                                        <input type="hidden" id="input-vagoneta_t" name="vago_cost[]" readonly />

                                        <input type="hidden" id="input-procab_id" name="caba_pres[]" readonly />
                                        <input type="hidden" id="input-caballo_id" name="caba_name[]" readonly />
                                        <input type="hidden" id="input-caballo_t" name="caba_cost[]" readonly />

                                        <input type="hidden" id="input-probic_id" name="bici_pres[]" readonly />
                                        <input type="hidden" id="input-bicicleta_id" name="bici_name[]" readonly />
                                        <input type="hidden" id="input-bicicleta_t" name="bici_cost[]" readonly />
                                    </div>
                                
                                    <div class="form-group mb-2 mt-2 col-md-12">
                                        <button type="submit" class="btn btn-primary col-md-12 font-14">PAGAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttonMinus = document.getElementById("button-minus");
            const buttonPlus = document.getElementById("button-plus");
            const cantPerInput = document.getElementById("cantper");
            
            // Valores mínimo (fijado en el máximo de capacidad) y el límite superior
            const minPer = {{ $reserva->tour->max_per }};
            const maxPer = 10; // Puedes ajustar este valor según tu necesidad

            buttonPlus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value);
                if (cantidad < maxPer) {
                    cantidad++;
                    cantPerInput.value = cantidad;
                }
            });

            buttonMinus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value);
                if (cantidad > minPer) {
                    cantidad--;
                    cantPerInput.value = cantidad;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkTodos = document.getElementById('check_todos'); // Checkbox principal (derecha)
            const rightCheckboxes = document.querySelectorAll('.form-check-input[data-group="right"]:not(#check_todos)'); // Checkboxes de la derecha
            const exclusiveCheckboxes = document.querySelectorAll('.form-check-input[data-exclusive="true"]'); // Checkboxes exclusivos
            const subtotalInput = document.getElementById('subtotal'); // Campo de subtotal
            const anticipoAnterior = document.getElementById('anticipoAnterior'); // Campo de anticipo anterior
            const anticipoActual = document.getElementById('anticipoActual'); // Campo de anticipo actual
            const prestatarioInput = document.getElementById('prestatario'); // Campo prestatario
            const saldoInput = document.getElementById('saldo'); // Campo saldo
            const hiddenInputs = {
                dpres: document.getElementById('dpres'),
                dserv: document.getElementById('dserv'),
                dserid: document.getElementById('dserid')
            };

            // Función para calcular el subtotal
            function calculateSubtotal() {
                let subtotal = 0;

                // Verificar si el checkbox principal está marcado
                if (checkTodos.checked) {
                    subtotal = parseFloat(checkTodos.dataset.monto) || 0;
                } else {
                    rightCheckboxes.forEach((checkbox) => {
                        if (checkbox.checked) {
                            const value = parseFloat(checkbox.dataset.monto) || 0;
                            subtotal += value;
                        }
                    });
                }

                // Sumar el valor de "anticipo actual"
                const anticipoActualValue = parseFloat(anticipoActual.value) || 0;
                subtotal += anticipoActualValue;

                // Mostrar el subtotal
                subtotalInput.value = subtotal.toFixed(2);

                // Actualizar prestatario y saldo
                updatePrestatarioAndSaldo();
            }

            // Función para actualizar prestatario y saldo
            function updatePrestatarioAndSaldo() {
                let selectedCheckbox = null;

                // Buscar el checkbox seleccionado
                exclusiveCheckboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        selectedCheckbox = checkbox;
                    }
                });

                // Si hay un checkbox seleccionado, tomar su valor, de lo contrario, usar 0
                const prestatarioValue = selectedCheckbox
                    ? parseFloat(selectedCheckbox.dataset.target) || 0
                    : 0;

                // Actualizar el campo prestatario
                prestatarioInput.value = prestatarioValue.toFixed(2);

                // Actualizar los campos ocultos
                if (selectedCheckbox) {
                    hiddenInputs.dpres.value = selectedCheckbox.dataset.pres || '';
                    hiddenInputs.dserv.value = selectedCheckbox.dataset.serv || '';
                    hiddenInputs.dserid.value = selectedCheckbox.dataset.servid || '';
                } else {
                    hiddenInputs.dpres.value = '';
                    hiddenInputs.dserv.value = '';
                    hiddenInputs.dserid.value = '';
                }

                // Calcular saldo
                const anticipoAnteriorValue = parseFloat(anticipoAnterior.value) || 0;
                const anticipoActualValue = parseFloat(anticipoActual.value) || 0;
                const saldoValue = prestatarioValue - anticipoAnteriorValue - anticipoActualValue;
                saldoInput.value = saldoValue.toFixed(2);
            }

            // Evento para manejar checkboxes exclusivos
            exclusiveCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        // Desmarcar todos los demás checkboxes exclusivos
                        exclusiveCheckboxes.forEach((otherCheckbox) => {
                            if (otherCheckbox !== this) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }

                    // Recalcular el subtotal y actualizar prestatario
                    calculateSubtotal();
                });
            });

            // Evento para el checkbox principal
            checkTodos.addEventListener('change', function () {
                const isChecked = this.checked;

                // Cambiar el estado de los checkboxes de la derecha
                rightCheckboxes.forEach((checkbox) => {
                    checkbox.checked = isChecked;
                });

                // Calcular el subtotal después de cambiar los estados
                calculateSubtotal();
            });

            // Evento para los checkboxes individuales de la derecha
            rightCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    if (!this.checked) {
                        checkTodos.checked = false;
                    }

                    const allChecked = Array.from(rightCheckboxes).every(cb => cb.checked);
                    checkTodos.checked = allChecked;

                    calculateSubtotal();
                });
            });

            // Eventos para los campos de anticipo
            anticipoActual.addEventListener('input', calculateSubtotal);
            anticipoAnterior.addEventListener('input', updatePrestatarioAndSaldo);

            // Inicializar los valores al cargar la página
            calculateSubtotal();
        });

        window.addEventListener("load", function () {
            // Definir los nombres de los campos dinámicos
            const campos = [
                "servicio_id", 
                "guia_id", 
                "traductor_id", 
                "cocinero_id", 
                "chofer_id", 
                "vagoneta_id", 
                "caballo_id", 
                "bicicleta_id", 
                "provag_id", 
                "procab_id", 
                "probic_id",
                "servicio_t",
                "guia_t",
                "traductor_t",
                "cocinero_t",
                "chofer_t",
                "vagoneta_t",
                "caballo_t",
                "bicicleta_t"
            ];

            // Función para actualizar el valor de los campos dinámicos
            function actualizarCampos(campo, valor) {
                const inputDerecho = document.getElementById(`input-${campo}`);
                if (inputDerecho) {
                    inputDerecho.value = valor; // Asigna el nuevo valor al campo derecho
                } else {
                    // Crear dinámicamente un campo input si no existe
                    const nuevoInput = document.createElement("input");
                    nuevoInput.type = "text";
                    nuevoInput.id = `input-${campo}`;
                    nuevoInput.name = campo;
                    nuevoInput.value = valor;
                    nuevoInput.readOnly = true;

                    // Agregar al contenedor de campos dinámicos
                    document.getElementById("campos-dinamicos").appendChild(nuevoInput);
                }
            }

            // Inicializar valores de los campos dinámicos
            campos.forEach((campo) => {
                const elementoIzquierdo = document.querySelector(`[name="${campo}"]`);
                const valor = elementoIzquierdo ? elementoIzquierdo.value : null;
                actualizarCampos(campo, valor); // Inicializar con los valores actuales
            });

            // Función para manejar cambios dinámicos en "servicio_id"
            function servicioCosto() {
                const select = document.getElementById('servicio_id');
                const selectedOption = select.options[select.selectedIndex];
                const cost = selectedOption.getAttribute('data-tarifa') || ''; // Obtener la tarifa del atributo

                // Actualizar el campo de costo en el lado izquierdo
                document.getElementById('servicio_t').value = cost;
                document.getElementById('input-servicio_t').value = cost;

                // Actualizar el valor correspondiente en los campos dinámicos
                actualizarCampos('servicio_id', select.value); // Actualizar el id del servicio
                actualizarCampos('servicio_t', cost); // Actualizar el costo del servicio
                actualizarCampos('input-servicio_t', cost); // Actualizar el costo del servicio
            }

            // Agregar evento de cambio al selector "servicio_id"
            const servicioSelect = document.getElementById('servicio_id');
            if (servicioSelect) {
                servicioSelect.addEventListener("change", servicioCosto);
            }

            // Función para manejar cambios dinámicos en "guia_id"
            function guiaCosto() {
                const select = document.getElementById('guia_id');
                const selectedOption = select.options[select.selectedIndex];
                const cost = selectedOption.getAttribute('data-tarifa') || ''; // Obtener la tarifa del atributo

                // Actualizar el campo de costo en el lado izquierdo
                document.getElementById('guia_t').value = cost;
                document.getElementById('input-guia_t').value = cost;

                // Actualizar el valor correspondiente en los campos dinámicos
                actualizarCampos('guia_id', select.value); // Actualizar el id de la guia
                actualizarCampos('guia_t', cost); // Actualizar el costo de la guia
                actualizarCampos('input-guia_t', cost); // Actualizar el costo de la guia
            }

            // Agregar evento de cambio al selector "guia_id"
            const guiaSelect = document.getElementById('guia_id');
            if (guiaSelect) {
                guiaSelect.addEventListener("change", guiaCosto);
            }

            // Función para manejar cambios dinámicos en "traductor_id"
            function traductorCosto() {
                const select = document.getElementById('traductor_id');
                const selectedOption = select.options[select.selectedIndex];
                const cost = selectedOption.getAttribute('data-tarifa') || ''; // Obtener la tarifa del atributo

                // Actualizar el campo de costo en el lado izquierdo
                document.getElementById('traductor_t').value = cost;
                document.getElementById('input-traductor_t').value = cost;

                // Actualizar el valor correspondiente en los campos dinámicos
                actualizarCampos('traductor_id', select.value); // Actualizar el id del traductor
                actualizarCampos('traductor_t', cost); // Actualizar el costo del traductor
                actualizarCampos('input-traductor_t', cost); // Actualizar el costo del traductor
            }

            // Agregar evento de cambio al selector "traductor_id"
            const traductorSelect = document.getElementById('traductor_id');
            if (traductorSelect) {
                traductorSelect.addEventListener("change", traductorCosto);
            }

            // Función para manejar cambios dinámicos en "cocinero_id"
            function cocineroCosto() {
                const select = document.getElementById('cocinero_id');
                const selectedOption = select.options[select.selectedIndex];
                const cost = selectedOption.getAttribute('data-tarifa') || ''; // Obtener la tarifa del atributo

                // Actualizar el campo de costo en el lado izquierdo
                document.getElementById('cocinero_t').value = cost;
                document.getElementById('input-cocinero_t').value = cost;

                // Actualizar el valor correspondiente en los campos dinámicos
                actualizarCampos('cocinero_id', select.value); // Actualizar el id del cocinero
                actualizarCampos('cocinero_t', cost); // Actualizar el costo del cocinero
                actualizarCampos('input-cocinero_t', cost); // Actualizar el costo del cocinero
            }

            // Agregar evento de cambio al selector "cocinero_id"
            const cocineroSelect = document.getElementById('cocinero_id');
            if (cocineroSelect) {
                cocineroSelect.addEventListener("change", cocineroCosto);
            }

            // Función para manejar cambios dinámicos en "chofer_id"
            function choferCosto() {
                const select = document.getElementById('chofer_id');
                const selectedOption = select.options[select.selectedIndex];
                const cost = selectedOption.getAttribute('data-tarifa') || ''; // Obtener la tarifa del atributo

                // Actualizar el campo de costo en el lado izquierdo
                document.getElementById('chofer_t').value = cost;
                document.getElementById('input-chofer_t').value = cost;

                // Actualizar el valor correspondiente en los campos dinámicos
                actualizarCampos('chofer_id', select.value); // Actualizar el id del chofer
                actualizarCampos('chofer_t', cost); // Actualizar el costo del chofer
                actualizarCampos('input-chofer_t', cost); // Actualizar el costo del chofer
            }

            // Agregar evento de cambio al selector "chofer_id"
            const choferSelect = document.getElementById('chofer_id');
            if (choferSelect) {
                choferSelect.addEventListener("change", choferCosto);
            }

            // Función para cargar las vagonetas según el prestatario seleccionado
            function cargarVagonetas(propietarioId) {
                const selectVagoneta = document.getElementById('vagoneta_id');
                selectVagoneta.innerHTML = '<option value="">Seleccionar</option>'; // Reiniciar las opciones

                if (propietarioId) {
                    fetch(`{{ url('/despachos/vagonetas/') }}/${propietarioId}`)
                        .then(response => response.json())
                        .then(vagonetas => {
                            vagonetas.forEach(vagoneta => {
                                const option = document.createElement('option');
                                option.value = vagoneta.id;
                                option.text = vagoneta.marca;
                                option.setAttribute('data-tarifa', vagoneta.costo); // Agregar tarifa al atributo
                                selectVagoneta.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error al cargar vagonetas:', error));
                }

                // Actualizar el campo dinámico con el prestatario seleccionado
                actualizarCampos('provag_id', propietarioId);
            }

            // Función para actualizar el costo según la vagoneta seleccionada
            function vagonetaCosto() {
                const selectVagoneta = document.getElementById('vagoneta_id');
                const selectedOption = selectVagoneta.options[selectVagoneta.selectedIndex];
                const cost = selectedOption ? selectedOption.getAttribute('data-tarifa') : ''; // Obtener la tarifa

                // Actualizar el campo de costo en el formulario principal
                document.getElementById('vagoneta_t').value = cost || '';

                // Actualizar los campos dinámicos con la vagoneta seleccionada y su costo
                actualizarCampos('vagoneta_id', selectVagoneta.value);
                actualizarCampos('vagoneta_t', cost);
            }

            // Asociar los eventos a los selectores del HTML
            const prestatarioSelect = document.getElementById('provag_id');
            if (prestatarioSelect) {
                prestatarioSelect.addEventListener("change", function () {
                    const propietarioId = prestatarioSelect.value;
                    cargarVagonetas(propietarioId);
                });
            }

            const vagonetaSelect = document.getElementById('vagoneta_id');
            if (vagonetaSelect) {
                vagonetaSelect.addEventListener("change", vagonetaCosto);
            }

            // Función para cargar las vagonetas según el prestatario seleccionado
            function cargarCaballos(propietarioId) {
                const selectCaballo = document.getElementById('caballo_id');
                selectCaballo.innerHTML = '<option value="">Seleccionar</option>'; // Reiniciar las opciones

                if (propietarioId) {
                    fetch(`{{ url('/despachos/caballos/') }}/${propietarioId}`)
                        .then(response => response.json())
                        .then(caballos => {
                            caballos.forEach(caballo => {
                                const option = document.createElement('option');
                                option.value = caballo.id;
                                option.text = `${caballo.nombre}`;
                                option.setAttribute('data-tarifa', caballo.costo);
                                selectCaballo.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error al cargar caballos:', error));
                }

                // Actualizar el campo dinámico con el prestatario seleccionado
                actualizarCampos('procab_id', propietarioId);
            }

            // Función para actualizar el costo según el caballo seleccionado
            function caballoCosto() {
                const selectCaballo = document.getElementById('caballo_id');
                const selectedOption = selectCaballo.options[selectCaballo.selectedIndex];
                const cost = selectedOption ? selectedOption.getAttribute('data-tarifa') : ''; // Obtener la tarifa

                // Actualizar el campo de costo en el formulario principal
                document.getElementById('caballo_t').value = cost || '';

                // Actualizar los campos dinámicos con el caballo seleccionado y su costo
                actualizarCampos('caballo_id', selectCaballo.value);
                actualizarCampos('caballo_t', cost);
            }

            // Asociar los eventos a los selectores del HTML
            const prestatarioSelectC = document.getElementById('procab_id');
            if (prestatarioSelectC) {
                prestatarioSelectC.addEventListener("change", function () {
                    const propietarioId = prestatarioSelectC.value;
                    cargarCaballos(propietarioId);
                });
            }

            const caballoSelect = document.getElementById('caballo_id');
            if (caballoSelect) {
                caballoSelect.addEventListener("change", caballoCosto);
            }

            // Función para cargar las biciletas según el prestatario seleccionado
            function cargarBicicletas(propietarioId) {
                const selectBicicleta = document.getElementById('bicicleta_id');
                selectBicicleta.innerHTML = '<option value="">Seleccionar</option>'; // Reiniciar las opciones

                if (propietarioId) {
                    fetch(`{{ url('/despachos/bicicletas/') }}/${propietarioId}`)
                        .then(response => response.json())
                        .then(bicicletas => {
                            bicicletas.forEach(bicicleta => {
                                const option = document.createElement('option');
                                option.value = bicicleta.id;
                                option.text = `${bicicleta.nombre}`;
                                option.setAttribute('data-tarifa', bicicleta.costo);
                                selectBicicleta.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error al cargar bicicletas:', error));
                }

                // Actualizar el campo dinámico con el prestatario seleccionado
                actualizarCampos('probic_id', propietarioId);
            }

            // Función para actualizar el costo según la bicicleta seleccionada
            function bicicletaCosto() {
                const selectBicicleta = document.getElementById('bicicleta_id');
                const selectedOption = selectBicicleta.options[selectBicicleta.selectedIndex];
                const cost = selectedOption ? selectedOption.getAttribute('data-tarifa') : ''; // Obtener la tarifa

                // Actualizar el campo de costo en el formulario principal
                document.getElementById('bicicleta_t').value = cost || '';

                // Actualizar los campos dinámicos con la bicicleta seleccionada y su costo
                actualizarCampos('bicicleta_id', selectBicicleta.value);
                actualizarCampos('bicicleta_t', cost);
            }

            // Asociar los eventos a los selectores del HTML
            const prestatarioSelectB = document.getElementById('probic_id');
            if (prestatarioSelectB) {
                prestatarioSelectB.addEventListener("change", function () {
                    const propietarioId = prestatarioSelectB.value;
                    cargarBicicletas(propietarioId);
                });
            }

            const bicicletaSelect = document.getElementById('bicicleta_id');
            if (bicicletaSelect) {
                bicicletaSelect.addEventListener("change", bicicletaCosto);
            }
        });
    </script>

    <script>
        
    </script>
@endsection
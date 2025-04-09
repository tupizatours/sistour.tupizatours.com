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
                            Salida del tour: {{ \Carbon\Carbon::parse($reserva->fecha)->format('d-m-Y') }} 
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
                                                        <button type="button" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#ModalCambiarEstado{{ $reserva->id }}">
                                                            <i class="bx bx-undo"></i> {{-- ícono de flecha inversa --}}
                                                        </button>
                                                    </div>
                                                
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="ModalCambiarEstado{{ $reserva->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form action="{{ route('estatus.update', $reserva->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="pagina" value="estado_reserva">
                                                                    <input type="hidden" name="estado" value="2">
                                                
                                                                    <div class="modal-header bg-light">
                                                                        <h5 class="modal-title">Revertir estado de la reserva</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                                    </div>
                                                
                                                                    <div class="modal-body">
                                                                        ¿Deseas cambiar el estado de esta reserva a <strong>"en revisión"</strong>?
                                                                    </div>
                                                
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-warning">Aceptar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
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
                                        <x-prestatario-select
                                            id="servicio_id"
                                            name="servicio_id"
                                            label="Elegir Servicio"
                                            :items="$servicios"
                                            :selected="$gestion->servicio_id"
                                            onchange="servicioCosto()"
                                            tarifa="servicio_t"
                                            value-tarifa={{ $gestion->servicio_t }}
                                            tarifa-field="costo"
                                        />
                                    </div>
                                @endif

                                @if($reserva->tour->id == $reserva->tour_id)
                                    @foreach($serv_tour_id as $value)
                                        @if($value == 100)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                                <x-prestatario-select
                                                    id="guia_id"
                                                    name="guia_id"
                                                    label="Elegir Guía"
                                                    :items="$guias"
                                                    :selected="$gestion->guia_id"
                                                    onchange="guiaCosto()"
                                                    tarifa="guia_t"
                                                    value-tarifa="{{ $gestion->guia_t }}"
                                                    tarifa-field="tarifa"
                                                />
                                            </div>
                                        @elseif($value == 101)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                                <x-prestatario-select
                                                    id="traductor_id"
                                                    name="traductor_id"
                                                    label="Elegir Traductor"
                                                    :items="$traductors"
                                                    :selected="$gestion->traductor_id"
                                                    onchange="traductorCosto()"
                                                    tarifa="traductor_t"
                                                    value-tarifa="{{ $gestion->traductor_t }}"
                                                    tarifa-field="tarifa"
                                                />
                                            </div>
                                        @elseif($value == 102)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                                <x-prestatario-select
                                                    id="cocinero_id"
                                                    name="cocinero_id"
                                                    label="Elegir cocinero"
                                                    :items="$cocineros"
                                                    :selected="$gestion->cocinero_id"
                                                    onchange="cocineroCosto()"
                                                    tarifa="cocinero_t"
                                                    value-tarifa="{{ $gestion->cocinero_t }}"
                                                    tarifa-field="tarifa"
                                                />
                                            </div>
                                        @elseif($value == 103)
                                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                                <x-prestatario-select
                                                    id="chofer_id"
                                                    name="chofer_id"
                                                    label="Elegir chofer"
                                                    :items="$chofers"
                                                    :selected="$gestion->chofer_id"
                                                    onchange="choferCosto()"
                                                    tarifa="chofer_t"
                                                    value-tarifa="{{ $gestion->chofer_t }}"
                                                    tarifa-field="tarifa"
                                                />
                                            </div>
                                        @elseif($value == 104)
                                            <x-prestatario-recurso
                                                prestatario-id="provag_id"
                                                prestatario-name="provag_id"
                                                prestatario-label="Elegir prestatario"
                                                :prestatario-items="$propietarios"
                                                :prestatario-selected="$gestion->provag_id"
                                                prestatario-onchange="cargarVagonetas(this.value)"
                                            
                                                recurso-id="vagoneta_id"
                                                recurso-name="vagoneta_id"
                                                recurso-label="Elegir vagoneta"
                                                :recurso-items="$vagonetas"
                                                :recurso-selected="$gestion->vagoneta_id"
                                                recurso-onchange="vagonetaCosto()"
                                            
                                                tarifa-id="vagoneta_t"
                                                tarifa-value="{{ $gestion->vagoneta_t }}"
                                            
                                                checkbox-id="check_vago"
                                                checkbox-pres="{{ $gestion->provag->id }}"
                                                checkbox-serv="vagoneta"
                                                checkbox-servid="{{ $gestion->vagoneta->id }}"
                                                checkbox-target="{{ $gestion->vagoneta_t }}"
                                            >
                                                @foreach($existePorpago as $porpago)
                                                    @if($porpago->dserv == 'vagoneta')
                                                        <div class="alert alert-success data_disabled"></div>
                                                    @endif
                                                @endforeach
                                            </x-prestatario-recurso>
                                    
                                        @elseif($value == 105)
                                                <x-prestatario-recurso
                                                    prestatario-id="procab_id"
                                                    prestatario-name="procab_id"
                                                    prestatario-label="Elegir prestatario"
                                                    :prestatario-items="$propietarios"
                                                    :prestatario-selected="$gestion->procab_id"
                                                    prestatario-onchange="cargarCaballos(this.value)"

                                                    recurso-id="caballo_id"
                                                    recurso-name="caballo_id"
                                                    recurso-label="Elegir caballo"
                                                    :recurso-items="$caballos"
                                                    :recurso-selected="$gestion->caballo_id"
                                                    recurso-onchange="caballoCosto()"

                                                    tarifa-id="caballo_t"
                                                    tarifa-value="{{ $gestion->caballo_t }}"

                                                    checkbox-id="check_caba"
                                                    checkbox-pres="{{ $gestion->procab->id }}"
                                                    checkbox-serv="caballo"
                                                    checkbox-servid="{{ $gestion->caballo->id }}"
                                                    checkbox-target="{{ $gestion->caballo_t }}"
                                                />
                                        @elseif($value == 106)
                                            <x-prestatario-recurso
                                                prestatario-id="probic_id"
                                                prestatario-name="probic_id"
                                                prestatario-label="Elegir prestatario"
                                                :prestatario-items="$propietarios"
                                                :prestatario-selected="$gestion->probic_id"
                                                prestatario-onchange="cargarBicicletas(this.value)"
                                            
                                                recurso-id="bicicleta_id"
                                                recurso-name="bicicleta_id"
                                                recurso-label="Elegir bicicleta"
                                                :recurso-items="$bicicletas"
                                                :recurso-selected="$gestion->bicicleta_id"
                                                recurso-onchange="bicicletaCosto()"
                                            
                                                tarifa-id="bicicleta_t"
                                                tarifa-value="{{ $gestion->bicicleta_t }}"
                                            
                                                checkbox-id="check_bici"
                                                checkbox-pres="{{ $gestion->probic->id }}"
                                                checkbox-serv="bicicleta"
                                                checkbox-servid="{{ $gestion->bicicleta->id }}"
                                                checkbox-target="{{ $gestion->bicicleta_t }}"
                                            >
                                                @if($existePorpago && $existePorpago->dserv == 'bicicleta')
                                                    <div class="alert alert-success data_disabled"></div>
                                                @endif
                                            </x-prestatario-recurso>
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
                            <form action="{{ route('desges.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                        
                                <input type="hidden" name="pagina" value="gestions" />
                                <input type="hidden" name="reserva_id" value="{{ $reserva->id }}" />
                                <input type="hidden" name="tour_id" value="{{ $reserva->tour_id }}" />
                        
                                @php
                                    $serv_tour_id = json_decode($reserva->tour->serv_tour);
                                @endphp
                        
                                @if($reserva->tour->id == $reserva->tour_id && $servicios->isNotEmpty())
                                    <div class="row g-3 pt-3 pb-2 col-md-12">
                                        <x-prestatario-select
                                            id="servicio_id"
                                            name="servicio_id"
                                            label="Elegir Servicio"
                                            :items="$servicios"
                                            :selected="null"
                                            onchange="servicioCosto()"
                                            tarifa="servicio_t"
                                            value-tarifa=""
                                            tarifa-field="costo"
                                        />
                                    </div>
                                @endif
                        
                                <div class="row g-3 pt-3 pb-2 col-md-12">
                                    @if($reserva->tour->id == $reserva->tour_id)
                                        @foreach($serv_tour_id as $value)
                                            @if($value == 100)
                                                <x-prestatario-select
                                                    id="guia_id"
                                                    name="guia_id"
                                                    label="Elegir Guía"
                                                    :items="$guias"
                                                    :selected="null"
                                                    onchange="guiaCosto()"
                                                    tarifa="guia_t"
                                                    value-tarifa=""
                                                    tarifa-field="tarifa"
                                                />
                                            @elseif($value == 101)
                                                <x-prestatario-select
                                                    id="traductor_id"
                                                    name="traductor_id"
                                                    label="Elegir Traductor"
                                                    :items="$traductors"
                                                    :selected="null"
                                                    onchange="traductorCosto()"
                                                    tarifa="traductor_t"
                                                    value-tarifa=""
                                                    tarifa-field="tarifa"
                                                />
                                            @elseif($value == 102)
                                                <x-prestatario-select
                                                    id="cocinero_id"
                                                    name="cocinero_id"
                                                    label="Elegir Cocinero"
                                                    :items="$cocineros"
                                                    :selected="null"
                                                    onchange="cocineroCosto()"
                                                    tarifa="cocinero_t"
                                                    value-tarifa=""
                                                    tarifa-field="tarifa"
                                                />
                                            @elseif($value == 103)
                                                <x-prestatario-select
                                                    id="chofer_id"
                                                    name="chofer_id"
                                                    label="Elegir Chofer"
                                                    :items="$chofers"
                                                    :selected="null"
                                                    onchange="choferCosto()"
                                                    tarifa="chofer_t"
                                                    value-tarifa=""
                                                    tarifa-field="tarifa"
                                                />
                                            @elseif($value == 104)
                                                <x-prestatario-recurso
                                                    prestatario-id="provag_id"
                                                    prestatario-name="provag_id"
                                                    prestatario-label="Elegir prestatario"
                                                    :prestatario-items="$propietarios"
                                                    :prestatario-selected="null"
                                                    prestatario-onchange="cargarVagonetas(this.value)"
                        
                                                    recurso-id="vagoneta_id"
                                                    recurso-name="vagoneta_id"
                                                    recurso-label="Elegir vagoneta"
                                                    :recurso-items="$vagonetas"
                                                    :recurso-selected="null"
                                                    recurso-onchange="vagonetaCosto()"
                        
                                                    tarifa-id="vagoneta_t"
                                                    tarifa-value=""
                        
                                                    checkbox-id="check_vago"
                                                    checkbox-pres=""
                                                    checkbox-serv="vagoneta"
                                                    checkbox-servid=""
                                                    checkbox-target=""
                                                />
                                            @elseif($value == 105)
                                                <x-prestatario-recurso
                                                    prestatario-id="procab_id"
                                                    prestatario-name="procab_id"
                                                    prestatario-label="Elegir prestatario"
                                                    :prestatario-items="$propietarios"
                                                    :prestatario-selected="null"
                                                    prestatario-onchange="cargarCaballos(this.value)"
                        
                                                    recurso-id="caballo_id"
                                                    recurso-name="caballo_id"
                                                    recurso-label="Elegir caballo"
                                                    :recurso-items="$caballos"
                                                    :recurso-selected="null"
                                                    recurso-onchange="caballoCosto()"
                        
                                                    tarifa-id="caballo_t"
                                                    tarifa-value=""
                        
                                                    checkbox-id="check_caba"
                                                    checkbox-pres=""
                                                    checkbox-serv="caballo"
                                                    checkbox-servid=""
                                                    checkbox-target=""
                                                />
                                            @elseif($value == 106)
                                                <x-prestatario-recurso
                                                    prestatario-id="probic_id"
                                                    prestatario-name="probic_id"
                                                    prestatario-label="Elegir prestatario"
                                                    :prestatario-items="$propietarios"
                                                    :prestatario-selected="null"
                                                    prestatario-onchange="cargarBicicletas(this.value)"
                        
                                                    recurso-id="bicicleta_id"
                                                    recurso-name="bicicleta_id"
                                                    recurso-label="Elegir bicicleta"
                                                    :recurso-items="$bicicletas"
                                                    :recurso-selected="null"
                                                    recurso-onchange="bicicletaCosto()"
                        
                                                    tarifa-id="bicicleta_t"
                                                    tarifa-value=""
                        
                                                    checkbox-id="check_bici"
                                                    checkbox-pres=""
                                                    checkbox-serv="bicicleta"
                                                    checkbox-servid=""
                                                    checkbox-target=""
                                                />
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
                                <div id="campos-dinamicos" style="display: none;"></div>

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

       
    </script>

    <script>
        window.addEventListener("load", function () {
            const campos = [
                "servicio_id", "guia_id", "traductor_id", "cocinero_id", "chofer_id",
                "vagoneta_id", "caballo_id", "bicicleta_id",
                "provag_id", "procab_id", "probic_id",
                "servicio_t", "guia_t", "traductor_t", "cocinero_t", "chofer_t",
                "vagoneta_t", "caballo_t", "bicicleta_t"
            ];

            const campoDinamicoContainer = document.getElementById("campos-dinamicos");

            function actualizarCampos(campo, valor) {
                let input = document.getElementById(`input-${campo}`);
                if (!input) {
                    input = document.createElement("input");
                    input.type = "text";
                    input.id = `input-${campo}`;
                    input.name = campo;
                    input.readOnly = true;
                    campoDinamicoContainer.appendChild(input);
                }
                input.value = valor;
            }

            // Inicializar campos al cargar
            campos.forEach(campo => {
                const el = document.querySelector(`[name="${campo}"]`);
                if (el) actualizarCampos(campo, el.value);
            });

            // Generalizar funciones de actualización de tarifa
            function conectarSelectConTarifa({ selectId, tarifaId }) {
                const select = document.getElementById(selectId);
                if (!select) return;

                select.addEventListener("change", () => {
                    const opt = select.options[select.selectedIndex];
                    const costo = opt?.getAttribute("data-tarifa") || "";

                    document.getElementById(tarifaId).value = costo;
                    actualizarCampos(selectId, select.value);
                    actualizarCampos(tarifaId, costo);
                });
            }

            [
                { selectId: "servicio_id", tarifaId: "servicio_t" },
                { selectId: "guia_id", tarifaId: "guia_t" },
                { selectId: "traductor_id", tarifaId: "traductor_t" },
                { selectId: "cocinero_id", tarifaId: "cocinero_t" },
                { selectId: "chofer_id", tarifaId: "chofer_t" },
                { selectId: "vagoneta_id", tarifaId: "vagoneta_t" },
                { selectId: "caballo_id", tarifaId: "caballo_t" },
                { selectId: "bicicleta_id", tarifaId: "bicicleta_t" },
            ].forEach(conectarSelectConTarifa);

            // Carga dinámica de recursos (vagoneta, caballo, bicicleta)
            function cargarRecursos({ url, propietarioId, selectId }) {
                const select = document.getElementById(selectId);
                if (!select) return;

                select.innerHTML = `<option value="">Seleccionar</option>`;

                if (!propietarioId) return;

                fetch(`${url}/${propietarioId}`)
                    .then(res => res.json())
                    .then(items => {
                        items.forEach(item => {
                            const opt = document.createElement("option");
                            opt.value = item.id;
                            opt.text = item.nombre || item.marca;
                            opt.setAttribute("data-tarifa", item.costo);
                            select.appendChild(opt);
                        });
                    })
                    .catch(err => console.error(`Error al cargar ${selectId}:`, err));

                actualizarCampos(selectId.replace('_id', 'pres'), propietarioId);
            }

            // Asociar cambios a selects de prestatarios
            const configCargas = [
                { triggerId: 'provag_id', targetId: 'vagoneta_id', endpoint: 'vagonetas' },
                { triggerId: 'procab_id', targetId: 'caballo_id', endpoint: 'caballos' },
                { triggerId: 'probic_id', targetId: 'bicicleta_id', endpoint: 'bicicletas' },
            ];

            configCargas.forEach(({ triggerId, targetId, endpoint }) => {
                const el = document.getElementById(triggerId);
                if (!el) return;

                el.addEventListener("change", () =>
                    cargarRecursos({
                        url: `{{ url('/despachos/${endpoint}') }}`,
                        propietarioId: el.value,
                        selectId: targetId
                    })
                );
            });
        });
    </script>
@endsection
@extends('layouts.app')

@section('template_title')
Agregar turista
@endsection

@section('estilos')
<style>
    .text-right {
        text-align: right;
    }

    .form_cantidad {
        max-width: 50px;
    }

    .form_date {
        max-width: 200px;
    }

    #totpre {
        display: none;
    }

    /*cargar file */
    @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css);
    @import url('https://fonts.googleapis.com/css?family=Roboto');

    .uploader {
        display: block;
        clear: both;
        margin: 0 auto;
        width: 100%;

        #file-drag {
            float: left;
            clear: both;
            width: 100%;
            padding: 2rem 1.5rem;
            text-align: center;
            background: #fff;
            border-radius: 7px;
            border: 3px solid #eee;
            transition: all .2s ease;
            user-select: none;

            &:hover {
                border-color: $theme;
            }

            &.hover {
                border: 3px solid $theme;
                box-shadow: inset 0 0 0 6px #eee;

                #start {
                    i.fa {
                        transform: scale(0.8);
                        opacity: 0.3;
                    }
                }
            }
        }

        #start {
            float: left;
            clear: both;
            width: 100%;

            &.hidden {
                display: none;
            }

            i.fa {
                font-size: 50px;
                margin-bottom: 1rem;
                transition: all .2s ease-in-out;
            }
        }

        #response {
            float: left;
            clear: both;
            width: 100%;

            &.hidden {
                display: none;
            }

            #messages {
                margin-bottom: .5rem;
            }
        }

        #file-image {
            display: inline;
            margin: 0 auto .5rem auto;
            width: auto;
            height: auto;
            max-width: 180px;

            &.hidden {
                display: none;
            }
        }

        #notimage {
            display: block;
            float: left;
            clear: both;
            width: 100%;

            &.hidden {
                display: none;
            }
        }

        progress,
        .progress {
            // appearance: none;
            display: inline;
            clear: both;
            margin: 0 auto;
            width: 100%;
            max-width: 180px;
            height: 8px;
            border: 0;
            border-radius: 4px;
            background-color: #eee;
            overflow: hidden;
        }

        .progress[value]::-webkit-progress-bar {
            border-radius: 4px;
            background-color: #eee;
        }

        .progress[value]::-webkit-progress-value {
            background: linear-gradient(to right, darken($theme, 8%) 0%, $theme 50%);
            border-radius: 4px;
        }

        .progress[value]::-moz-progress-bar {
            background: linear-gradient(to right, darken($theme, 8%) 0%, $theme 50%);
            border-radius: 4px;
        }

        input[type="file"] {
            display: none;
        }

        .btn {
            display: inline-block;
            margin: .5rem .5rem 1rem .5rem;
            clear: both;
            font-family: inherit;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            text-transform: initial;
            border: none;
            border-radius: .2rem;
            outline: none;
            padding: 0 1rem;
            height: 36px;
            line-height: 36px;
            color: #fff;
            transition: all 0.2s ease-in-out;
            box-sizing: border-box;
            background: $theme;
            border-color: $theme;
            cursor: pointer;
        }
    }

    .hidden {
        display: none;
    }

    .tab-pane .form-check-label {
            width: 100%;
    }
    .tab-pane .form-check-label span {
        float: right;
    }
</style>
@endsection

@section('content')
<link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />

<form action="{{ route('venresclis.store') }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
    @csrf

    @php
    use App\Models\Servicio\Hotel;
    use App\Models\Servicio\Ticket;
    use App\Models\Servicio\Turista;
    use App\Models\Servicio\Accesorio;
    @endphp

    @foreach($tours as $tour)
    @if($tour->id == $reserva->tour_id)
    <?php
    $ticket_ids = json_decode($tour->tickets, true) ?? [];
    $accesorio_ids = json_decode($tour->accesorios, true) ?? [];
    $turista_ids = json_decode($tour->turistas, true) ?? [];
    $hotel_ids = array_merge(...json_decode($tour->hoteles, true) ?? []);

    // Filtrar solo los elementos necesarios
    $tickets = Ticket::whereIn('id', $ticket_ids)->get();
    $accesorios = Accesorio::whereIn('id', $accesorio_ids)->get();
    $turistas = Turista::whereIn('id', $turista_ids)->get();
    $hoteles = Hotel::whereIn('id', $hotel_ids)->with('habitaciones')->get();

    $hotelesSeleccionados = json_decode($tour->hoteles, true);
    ?>

    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-5">
            <div class="card">
                <div class="card border-primary mb-0">
                    <div class="card-body pt-5 pb-5 p-4 fase" id="primera_fase" style="display: none;">
                        @php
                        $originalDate = $tour->created_at;
                        $newDate = date("m/d/Y", strtotime($originalDate));
                        @endphp

                        <input type="hidden" value="add_turista" id="pagina" name="pagina" />
                        <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id">

                        <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}" />
                        <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}" />
                        <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $reserva->pre_tot }}" />
                        <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $reserva->pre_per }}" />
                        <input type="hidden" id="created_at" name="created_at" value="{{ $newDate }}" />
                        <input type="hidden" id="tour_id" name="tour_id" value="{{ $tour->id }}" />
                        <input type="hidden" id="estatus" name="estatus" value="1" />

                        <h5 class="card-title text-black text-center"><b>{{ $tour->titulo }}</b></h5>

                        <dl class="row">
                            <dt class="col-sm-3">Precio</dt>
                            <dd class="col-sm-9 text-right">{{ 'Bs. '.number_format($tour->pre_uni, 2, '.', '') }}</dd>
                        </dl>

                        <hr>

                        <dl class="row">
                            <dt class="col-sm-3">Personas</dt>
                            <dd class="col-sm-9 text-right">
                                <div class="input-group input-spinner justify-content-end">
                                    <button class="btn btn-white" type="button" id="button-minus"> - </button>
                                    <input type="text" id="cantper" name="cantper" class="form-control form_cantidad text-center" value="1">
                                    <button class="btn btn-white" type="button" id="button-plus"> + </button>
                                </div>
                            </dd>
                        </dl>

                        <p class="card-text">{{ $tour->descripcion }}</p>

                        <hr>

                        <dl class="row">
                            <dt class="col-sm-3">Fecha del tour</dt>
                            <dd class="col-sm-9 text-right">
                                <div class="input-group input-spinner justify-content-end">
                                    <input type="date" class="form-control form_date text-center" id="fecha_limite" name="fecha_limite" />
                                </div>
                            </dd>
                        </dl>

                        <hr>

                        <div class="form-check form-switch">
                            <input class="form-check-input" value="1" type="checkbox" role="switch" id="tprivado" />
                            <label class="form-check-label" for="tprivado">Deseas privado</label>
                        </div>

                        <hr>

                        <div class="d-flex align-items-center gap-2">
                            <!-- a href="javascript:;" class="btn btn-danger regresar col-md-6" data-prev=""><i class="bx bx-microphone"></i>Cancelar</a -->
                            <a href="javascript:;" class="btn btn-primary continuar col-md-12" data-next="segunda_fase">Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i></a>
                        </div>
                    </div>

                    <div class="card-body pt-5 pb-5 p-4 fase" id="segunda_fase">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres <span>*</span></label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required />
                            </div>

                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos <span>*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required />
                            </div>

                            <div class="col-md-6">
                                <label for="edad" class="form-label">Edad <span>*</span></label>
                                <input type="number" class="form-control" id="edad" name="edad" required />
                            </div>

                            <div class="col-md-6">
                                <label for="nacionalidad" class="form-label">Nacionalidad <span>*</span></label>
                                <select class="form-select" id="nacionalidad" name="nacionalidad" type="select">
                                    <option value="">Seleccionar</option>
                                    @foreach($countries as $countrie)
                                    <option value="{{ $countrie->iso }}">{{ $countrie->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="documento" class="form-label">Número de documento <span>*</span></label>
                                <input type="number" class="form-control" id="documento" name="documento" required />
                            </div>

                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular <span>*</span></label>
                                <input type="number" class="form-control" id="celular" name="celular" required />
                            </div>

                            <div class="col-md-6">
                                <label for="sexo" class="form-label">Sexo <span>*</span></label>
                                <select class="form-control" id="sexo" name="sexo" type="select" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span>*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-md-12">
                                <label for="alergias" class="form-label">Alergias</label>
                                <select class="form-select" id="alergias" name="alergias[]" multiple>
                                    @if(isset($alergias) && count($alergias) > 0)
                                        @foreach($alergias as $alergia)
                                            <option value="{{ $alergia->id }}" 
                                                {{ in_array($alergia->id, json_decode($reserva->alergias, true) ?? []) ? 'selected' : '' }}>
                                                {{ $alergia->titulo }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">No hay opciones disponibles</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="alimentacion" class="form-label">Tipo alimentación</label>
                                <select class="form-select" id="alimentacion" name="alimentacion[]" multiple>
                                    @if(isset($alimentos) && count($alimentos) > 0)
                                        @foreach($alimentos as $alimento)
                                            <option value="{{ $alimento->id }}" 
                                                {{ in_array($alimento->id, json_decode($reserva->alimentacion, true) ?? []) ? 'selected' : '' }}>
                                                {{ $alimento->titulo }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">No hay opciones disponibles</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="nota" class="form-label">Nota adicional</label>
                                <input type="text" class="form-control" id="nota" name="nota" />
                            </div>
                            <div class="col-md-12">
                                <!-- Input para cargar una nueva imagen -->
                                <input class="form-control form-control-solid" id="file-upload" name="file" type="file" accept=".pdf, .doc, .docx, image/*" required />
                            
                                <label for="file-upload" id="file-drag">
                                    <img id="file-image" src="#" alt="Preview" class="hidden" style="max-width: 100%; height: auto;">
                                    <iframe id="pdf-preview" style="display: none;" class="hidden" width="100%" height="500px"></iframe>
                            
                                    <div id="start">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        <div id="pdf-upload">Selecciona el archivo a cargar</div>
                                        <div id="notimage" class="hidden">Selecciona una imagen</div>
                                        <span id="file-upload-btn" class="btn btn-primary">Selecciona un archivo</span>
                                    </div>
                            
                                    <div id="response" class="hidden">
                                        <div id="messages"></div>
                                        <progress class="progress" id="file-progress" value="0"><span>0</span>%</progress>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-5 pb-5 p-4 fase" id="tercera_fase">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tourtickets" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Tickets</div>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tourhoteles" role="tab" aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Hoteles</div>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#touraccesorios" role="tab" aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Accesorios</div>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tourservicios" role="tab" aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-title">Servicios</div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content py-3">
                            <!-- ✅ TAB TICKETS -->
                            <div class="tab-pane fade show active" id="tourtickets" role="tabpanel">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all_tickets">
                                        <label class="form-check-label" for="select_all_tickets">
                                            <strong>Seleccionar todos</strong>
                                        </label>
                                    </div>
                                    @foreach($tickets as $ticket)
                                    <div class="form-check">
                                        <input class="form-check-input ticket-checkbox" type="checkbox" name="ticket_id[]" value="{{ $ticket->id }}" 
                                            data-name="{{ $ticket->titulo }}" 
                                            data-hnac="{{ number_format($ticket->nacionales, 2, '.', '') }}"
                                            data-hext="{{ number_format($ticket->extranjeros, 2, '.', '') }}"   
                                            id="ticket_{{ $ticket->id }}">
                                        <label class="form-check-label" for="ticket_{{ $ticket->id }}">
                                            {{ $ticket->titulo }} - Bs. {{ number_format($ticket->nacionales, 2, '.', '') }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        
                            <!-- ✅ TAB HOTELES -->
                            <div class="tab-pane fade" id="tourhoteles" role="tabpanel">
                                @foreach(json_decode($tour->hoteles, true) as $dia => $hotelIds)
                                    <div class="row g-3">
                                        <div class="col-md-12 form-check">
                                            <label class="form-label fw-bold">Día {{ $dia }}</label>

                                            @foreach ($hoteles as $hotel)
                                                @if(in_array($hotel->id, $hotelIds))
                                                    <div class="form-check mb-2">
                                                        <label class="form-check-label fw-bold">{{ $hotel->titulo }}</label>

                                                        @foreach($habitaciones->where('hotel_id', $hotel->id) as $habitacion)
                                                            <div class="form-check">
                                                                <input class="form-check-input habitacion-checkbox"
                                                                    type="radio"
                                                                    name="habitacion_dia_{{ $dia }}"
                                                                    id="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $dia }}"
                                                                    value="{{ $habitacion->id }}"
                                                                    data-name="{{ $habitacion->titulo }}"
                                                                    data-precio="{{ number_format($habitacion->nacionales, 2, '.', '') }}"
                                                                    data-hnac="{{ number_format($habitacion->nacionales, 2, '.', '') }}"
                                                                    data-hext="{{ number_format($habitacion->extranjeros, 2, '.', '') }}" 
                                                                    data-dia="{{ $dia }}"
                                                                />
                                                                <label class="form-check-label" for="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $dia }}">
                                                                    {{ $habitacion->titulo }} - Bs. {{ number_format($habitacion->nacionales, 2, '.', '') }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                                                    
                            <!-- ✅ TAB ACCESORIOS -->
                            <div class="tab-pane fade" id="touraccesorios" role="tabpanel">
                                <div class="col-md-12">
                                    @foreach($accesorios as $accesorio)
                                    <div class="form-check">
                                        <input class="form-check-input accesorio-checkbox" type="checkbox" 
                                            name="accesorio_id[]" value="{{ $accesorio->id }}" 
                                            data-name="{{ $accesorio->titulo }}" 
                                            data-precio="{{ number_format($accesorio->venta, 2, '.', '') }}" 
                                            id="accesorio_{{ $accesorio->id }}">
                                        <label class="form-check-label" for="accesorio_{{ $accesorio->id }}">
                                            {{ $accesorio->titulo }} - Bs. {{ number_format($accesorio->venta, 2, '.', '') }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        
                            <!-- ✅ TAB SERVICIOS -->
                            <div class="tab-pane fade" id="tourservicios" role="tabpanel">
                                <div class="col-md-12">
                                    @foreach($turistas as $turista)
                                    <div class="form-check">
                                        <input class="form-check-input servicio-checkbox" type="checkbox" 
                                            name="servicio_id[]" value="{{ $turista->id }}" 
                                            data-name="{{ $turista->titulo }}" 
                                            data-precio="{{ number_format($turista->venta, 2, '.', '') }}" 
                                            id="servicio_{{ $turista->id }}">
                                        <label class="form-check-label" for="servicio_{{ $turista->id }}">
                                            {{ $turista->titulo }} - Bs. {{ number_format($turista->venta, 2, '.', '') }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>                        

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="javascript:;" class="btn btn-danger regresar col-md-6" data-prev="segunda_fase"><i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar</a>
                                    <button type="submit" class="btn btn-primary continuar col-md-6">Reservar <i class="fadeIn animated bx bx-arrow-to-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card border-primary mb-0">
                    <div class="card-body pt-5 pb-5 p-4">
                        <dl class="row col-md-12" id="porpre">
                            <dt class="col-sm-5">Precio / persona</dt>
                            <dd class="col-sm-7 text-right" id="precio_count">
                                {{ 'Bs. '.number_format($tour->pre_uni, 2, '.', '') }}
                            </dd>

                            <dt class="col-sm-5">Cantidad de persona</dt>
                            <dd class="col-sm-7 text-right" id="cant_pers">{{ $reserva->can_per.' personas' }}</dd>
                        </dl>

                        <dl class="row col-md-12" id="totpre" style="display: none;">
                            <dt class="col-sm-5">Precio</dt>
                            <dd class="col-sm-7 text-right" id="max_precio"></dd>

                            <dt class="col-sm-5">Cantidad de persona</dt>
                            <dd class="col-sm-7 text-right" id="max_personas"></dd>
                        </dl>

                        <dl class="col-md-12 row tickets_cont" id="tickets_cont" style="display: none;">
                            <dt class="col-sm-12">
                                <span class="btn btn-inverse-success mb-3 col-md-12">Tickets</span>
                            </dt>

                            <dt class="col-sm-5" id="tic_name"></dt>
                            <dd class="col-sm-7 text-right" id="tic_pre"></dd>
                        </dl>

                        <dl class="col-md-12 row habitaciones_cont" id="habitaciones_cont" style="display: none;">
                            <dt class="col-sm-12">
                                <span class="btn btn-inverse-success mb-3 col-md-12">Habitaciones</span>
                            </dt>

                            <dt class="col-sm-5" id="hab_name"></dt>
                            <dd class="col-sm-7 text-right" id="hab_pre"></dd>
                        </dl>

                        <dl class="col-md-12 row accesorios_cont" id="accesorios_cont" style="display: none;">
                            <dt class="col-sm-12">
                                <span class="btn btn-inverse-success mb-3 col-md-12">Accesorios</span>
                            </dt>

                            <dt class="col-sm-5" id="acc_name"></dt>
                            <dd class="col-sm-7 text-right" id="acc_pre"></dd>
                        </dl>

                        <dl class="col-md-12 row servicios_cont" id="servicios_cont" style="display: none;">
                            <dt class="col-sm-12">
                                <span class="btn btn-inverse-success mb-3 col-md-12">Servicios</span>
                            </dt>

                            <dt class="col-sm-5" id="ser_name"></dt>
                            <dd class="col-sm-7 text-right" id="ser_pre"></dd>
                        </dl>

                        <dl class="row col-md-12">
                            <dt class="col-sm-3"></dt>
                            <dd class="col-sm-9 text-right">
                                <b>Subtotal:</b> <span id="tour_Sbt">{{ 'Bs. '.number_format($tour->pre_uni, 2, '.', '') }}</span>
                            </dd>
                        </dl>

                        <input type="hidden" name="tickets_seleccionados" id="tickets_seleccionados" value="">
                        <input type="hidden" name="habitaciones_seleccionadas" id="habitaciones_seleccionadas" value="">
                        <input type="hidden" name="accesorios_seleccionados" id="accesorios_seleccionados" value="">
                        <input type="hidden" name="servicios_seleccionados" id="servicios_seleccionados" value="">
                        <input type="hidden" name="tour_total" id="tour_total" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</form>
@endsection

@section('footer_scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("✅ Script cargado correctamente");

        const cantPerInput = document.getElementById("cantper");
        const preUni = parseFloat(document.getElementById("pre_uni")?.value || 0);
        const maxPer = parseFloat(document.getElementById("max_per")?.value || 0);
        const tourSbt = document.getElementById("tour_Sbt");
        const tourTotal = document.getElementById("tour_total");
        const nacionalidadSelect = document.getElementById("nacionalidad");

        // Contenedores dinámicos
        const ticketsCont = document.getElementById("tickets_cont");
        const ticName = document.getElementById("tic_name");
        const ticPre = document.getElementById("tic_pre");

        const accesoriosCont = document.getElementById("accesorios_cont");
        const accName = document.getElementById("acc_name");
        const accPre = document.getElementById("acc_pre");

        const serviciosCont = document.getElementById("servicios_cont");
        const serName = document.getElementById("ser_name");
        const serPre = document.getElementById("ser_pre");

        const habitacionesCont = document.getElementById("habitaciones_cont");
        const habName = document.getElementById("hab_name");
        const habPre = document.getElementById("hab_pre");

        // Checkboxes de selección
        const checkboxesTickets = document.querySelectorAll(".ticket-checkbox");
        const checkboxesAccesorios = document.querySelectorAll(".accesorio-checkbox");
        const checkboxesServicios = document.querySelectorAll(".servicio-checkbox");
        const checkboxesHabitaciones = document.querySelectorAll(".habitacion-checkbox");

        let totalTickets = 0, totalAccesorios = 0, totalServicios = 0, totalHabitaciones = 0;

        /** ✅ Selección de todos los tickets */
        document.getElementById('select_all_tickets')?.addEventListener('change', function () {
            checkboxesTickets.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateAllTotals();
        });

        /** ✅ Selección única de habitaciones */
        checkboxesHabitaciones.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                let groupName = this.name;
                if (this.checked) {
                    checkboxesHabitaciones.forEach(otherCheckbox => {
                        if (otherCheckbox !== this && otherCheckbox.name === groupName) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
                updateAllTotals();
            });
        });

        /** ✅ Muestra precios según nacionalidad */
        function handleNacionalidadChange() {
            const isBolivia = nacionalidadSelect.value === "BO";
            document.querySelectorAll(".seccion-mexico").forEach(el => el.classList.toggle("hidden", !isBolivia));
            document.querySelectorAll(".seccion-otros").forEach(el => el.classList.toggle("hidden", isBolivia));
            updateAllTotals();
        }
        nacionalidadSelect.addEventListener("change", handleNacionalidadChange);

        /** ✅ Actualiza los totales de todos los checkboxes */
        function updateAllTotals() {
            totalTickets = updateTotal(checkboxesTickets, ticketsCont, ticName, ticPre, "hnac", "hext");
            totalAccesorios = updateTotal(checkboxesAccesorios, accesoriosCont, accName, accPre, "precio");
            totalServicios = updateTotal(checkboxesServicios, serviciosCont, serName, serPre, "precio");
            totalHabitaciones = updateTotal(checkboxesHabitaciones, habitacionesCont, habName, habPre, "hnac", "hext");

            updateGrandTotal();
        }
                /** ✅ Muestra u oculta los contenedores dinámicamente y suma los valores */
        function updateTotal(checkboxes, container, nameField, priceField, priceAttrBO = "hnac", priceAttrExt = "hext") {
            let total = 0;
            let names = [], prices = [];
            let checkedCount = 0;

            const isBolivian = nacionalidadSelect.value === "BO";

            checkboxes.forEach(checkbox => {
                const priceAttr = isBolivian ? checkbox.dataset[priceAttrBO] : checkbox.dataset[priceAttrExt];
                const price = parseFloat(priceAttr || "0");

                if (checkbox.checked) {
                    checkedCount++;
                    total += price;
                    names.push(checkbox.dataset.name);
                    prices.push(`Bs. ${price.toFixed(2)}`);
                }
            });

            if (checkedCount > 0) {
                container.style.display = "block";
                nameField.innerHTML = names.join("<br>");
                priceField.innerHTML = prices.join("<br>");
            } else {
                container.style.display = "none";
            }

            return total;
        }



        /** ✅ Actualiza el subtotal total */
        function updateGrandTotal() {
            let cantidad = parseInt(cantPerInput.value) || 1;
            let subtotal = cantidad * preUni;
            let totalSum = subtotal + totalTickets + totalAccesorios + totalServicios + totalHabitaciones;

            tourSbt.innerText = `Bs. ${totalSum.toFixed(2)}`;
            tourTotal.value = totalSum.toFixed(2);
        }

        function saveSelections(checkboxes, hiddenFieldId, priceAttrBO = "hnac", priceAttrExt = "hext") {
            const isBolivian = nacionalidadSelect.value === "BO";

            const selected = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => {
                    const price = parseFloat(isBolivian ? checkbox.dataset[priceAttrBO] : checkbox.dataset[priceAttrExt]) || 0;

                    const selection = {
                        id: checkbox.value,
                        name: checkbox.dataset.name,
                        price: price
                    };

                    if (checkbox.dataset.dia) {
                        selection.dia = parseInt(checkbox.dataset.dia);
                    }

                    return selection;
                });

            document.getElementById(hiddenFieldId).value = JSON.stringify(selected);
        }

        // ✅ Eventos de checkboxes
        checkboxesTickets.forEach(checkbox => checkbox.addEventListener("change", updateAllTotals));
        checkboxesAccesorios.forEach(checkbox => checkbox.addEventListener("change", updateAllTotals));
        checkboxesServicios.forEach(checkbox => checkbox.addEventListener("change", updateAllTotals));
        checkboxesHabitaciones.forEach(checkbox => checkbox.addEventListener("change", updateAllTotals));

        function updateSelectedItems() {
            saveSelections(checkboxesTickets, "tickets_seleccionados", "hnac", "hext");
            saveSelections(checkboxesHabitaciones, "habitaciones_seleccionadas", "hnac", "hext");
            saveSelections(checkboxesAccesorios, "accesorios_seleccionados", "precio");
            saveSelections(checkboxesServicios, "servicios_seleccionados", "precio");
        }

        // ✅ Detecta cambios en los checkboxes y guarda los valores seleccionados
        checkboxesTickets.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
        checkboxesAccesorios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
        checkboxesServicios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
        checkboxesHabitaciones.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));


        // ✅ Inicia valores
        handleNacionalidadChange();
        updateAllTotals();
        updateSelectedItems();
    });
</script>

<script>
    $(document).ready(function() {
        // Aplicar Select2 a los selectores ya generados
        $('#alergias').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: 'Seleccionar',
            closeOnSelect: false
        });

        $('#alimentacion').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: 'Seleccionar',
            closeOnSelect: false
        });
    });
</script>

<script>
    // File Upload
    // 
    function ekUpload() {
        function Init() {

            console.log("Upload Initialised");

            var fileSelect = document.getElementById('file-upload'),
                fileDrag = document.getElementById('file-drag'),
                submitButton = document.getElementById('submit-button');

            fileSelect.addEventListener('change', fileSelectHandler, false);

            // Is XHR2 available?
            var xhr = new XMLHttpRequest();
            if (xhr.upload) {
                // File Drop
                fileDrag.addEventListener('dragover', fileDragHover, false);
                fileDrag.addEventListener('dragleave', fileDragHover, false);
                fileDrag.addEventListener('drop', fileSelectHandler, false);
            }
        }

        function fileDragHover(e) {
            var fileDrag = document.getElementById('file-drag');

            e.stopPropagation();
            e.preventDefault();

            fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
        }

        function fileSelectHandler(e) {
            // Fetch FileList object
            var files = e.target.files || e.dataTransfer.files;

            // Cancel event and hover styling
            fileDragHover(e);

            // Process all File objects
            for (var i = 0, f; f = files[i]; i++) {
                parseFile(f);
                uploadFile(f);
            }
        }

        // Output
        function output(msg) {
            // Response
            var m = document.getElementById('messages');
            m.innerHTML = msg;
        }

        function parseFile(file) {
            var fileName = file.name.toLowerCase();
            var isImage = /\.(gif|jpg|jpeg|png)$/i.test(fileName);
            var isPDF = /\.pdf$/i.test(fileName);

            if (isImage) {
                // Mostrar imagen
                document.getElementById('file-image').classList.remove("hidden");
                document.getElementById('file-image').src = URL.createObjectURL(file);
                document.getElementById('pdf-preview').classList.add("hidden");
                document.getElementById('pdf-upload').textContent = 'Selecciona el archivo a cargar';
            } else if (isPDF) {
                // Mostrar PDF en iframe
                document.getElementById('pdf-preview').classList.remove("hidden");
                document.getElementById('pdf-preview').src = URL.createObjectURL(file);
                document.getElementById('file-image').classList.add("hidden");
                document.getElementById('pdf-upload').textContent = fileName;
            } else {
                // Archivo no soportado
                document.getElementById('file-image').classList.add("hidden");
                document.getElementById('pdf-preview').classList.add("hidden");
                alert('Por favor selecciona un archivo válido (imagen o PDF).');
            }
        }

        function setProgressMaxValue(e) {
            var pBar = document.getElementById('file-progress');

            if (e.lengthComputable) {
                pBar.max = e.total;
            }
        }

        function updateFileProgress(e) {
            var pBar = document.getElementById('file-progress');

            if (e.lengthComputable) {
                pBar.value = e.loaded;
            }
        }

        function uploadFile(file) {

            var xhr = new XMLHttpRequest(),
                fileInput = document.getElementById('class-roster-file'),
                pBar = document.getElementById('file-progress'),
                fileSizeLimit = 1024; // In MB
            if (xhr.upload) {
                // Check if file is less than x MB
                if (file.size <= fileSizeLimit * 1024 * 1024) {
                    // Progress bar
                    pBar.style.display = 'inline';
                    xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
                    xhr.upload.addEventListener('progress', updateFileProgress, false);

                    // File received / failed
                    xhr.onreadystatechange = function(e) {
                        if (xhr.readyState == 4) {
                            // Everything is good!

                            // progress.className = (xhr.status == 200 ? "success" : "failure");
                            // document.location.reload(true);
                        }
                    };

                    // Start upload
                    xhr.open('POST', document.getElementById('file-upload-form').action, true);
                    xhr.setRequestHeader('X-File-Name', file.name);
                    xhr.setRequestHeader('X-File-Size', file.size);
                    xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                    xhr.send(file);
                } else {
                    output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
                }
            }
        }

        // Check for the various File API support.
        if (window.File && window.FileList && window.FileReader) {
            Init();
        } else {
            document.getElementById('file-drag').style.display = 'none';
        }
    }
    ekUpload();
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const continuarButtons = document.querySelectorAll(".continuar");
        const regresarButtons = document.querySelectorAll(".regresar");

        continuarButtons.forEach(button => {
            button.addEventListener("click", function() {
                const currentSection = button.closest(".fase");
                const nextSectionId = button.getAttribute("data-next");

                if (nextSectionId) {
                    currentSection.style.display = "none"; // Oculta la sección actual
                    document.getElementById(nextSectionId).style.display = "block"; // Muestra la siguiente sección
                }
            });
        });

        regresarButtons.forEach(button => {
            button.addEventListener("click", function() {
                const currentSection = button.closest(".fase");
                const prevSectionId = button.getAttribute("data-prev");

                if (prevSectionId) {
                    currentSection.style.display = "none"; // Oculta la sección actual
                    document.getElementById(prevSectionId).style.display = "block"; // Muestra la sección anterior
                }
            });
        });
    });
</script>
<script>
    function continuar2() {
        const continuarButtons = document.querySelectorAll(".continuar2");
        const regresarButtons = document.querySelectorAll(".regresar2");
        const requiredFields = document.querySelectorAll("#segunda_fase [required]");
        let allFilled = true;
        requiredFields.forEach(field => {
            if (!field.value) {
                allFilled = false;
            }
        });
        const valorFinal = allFilled;
        if (valorFinal) {
            document.getElementById('segunda_fase').style.display = "none"; // Oculta la sección actual
            document.getElementById('tercera_fase').style.display = "block";
        } else {
            alert('Por favor llene los campos obligatorios *');
        }
    }

    function regresar2() {
        document.getElementById('segunda_fase').style.display = "none"; // Oculta la sección actual
        document.getElementById('primera_fase').style.display = "block";
    }
</script>

@endsection
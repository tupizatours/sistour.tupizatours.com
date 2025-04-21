@extends('layouts.app')

@section('template_title')
    Reservar
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
            background: linear-gradient(to right, darken($theme,8%) 0%, $theme 50%);
            border-radius: 4px; 
        }
        .progress[value]::-moz-progress-bar {
            background: linear-gradient(to right, darken($theme,8%) 0%, $theme 50%);
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

        #preview-container {
            display: flex;
            justify-content: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente si hay espacio */
            flex-direction: column; /* Asegura que el contenido se apile correctamente */
            text-align: center; /* Alinea el texto en el centro */
            margin-top: 10px; /* Espacio arriba */
        }

        #preview-container img {
            max-width: 100%; /* Asegura que la imagen no desborde */
            height: auto;
            display: block;
            margin: 0 auto; /* Centra horizontalmente */
        }

        #preview-container p {
            margin-top: 10px; /* Espacio entre la imagen y el texto */
        }
    </style>
@endsection

@section('content')
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
    
    <form action="{{ route('venresclis.update', $rescli->id) }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            use App\Models\Servicio\Hotel;
            use App\Models\Servicio\Ticket;
            use App\Models\Servicio\Turista;
            use App\Models\Servicio\Accesorio;
        @endphp

        @foreach($tours as $tour)
            @if($tour->id == $rescli->reserva->tour_id)
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

                                    <input type="hidden" value="file_panel" id="pagina" name="pagina" />
                                    <input type="hidden" value="{{ $rescli->reserva->id }}" id="reserva_id" name="reserva_id">
                                    <input type="hidden" value="{{ $rescli->id }}" id="rescli_id" name="rescli_id">
                                    <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}" />
                                    <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}" />
                                    <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $rescli->reserva->pre_tot }}" />
                                    <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $rescli->reserva->pre_per }}" />
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

                                </div>

                                <div class="card-body pt-5 pb-5 p-4 fase" id="segunda_fase">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="nombres" class="form-label">Nombres <span>*</span></label>
                                            <input type="text" class="form-control" id="nombres" name="nombres" required value="{{ $rescli->nombres }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="apellidos" class="form-label">Apellidos <span>*</span></label>
                                            <input type="text" class="form-control" id="apellidos" name="apellidos" required value="{{ $rescli->apellidos }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="edad" class="form-label">Edad <span>*</span></label>
                                            <input type="number" class="form-control" id="edad" name="edad" required value="{{ $rescli->edad }}" />
                                        </div>

                                        

                                        <div class="col-md-6">
                                            <label for="nacionalidad" class="form-label">Nacionalidad <span>*</span></label>
                                            <select class="form-select" id="nacionalidad" name="nacionalidad" required>
                                                <option value="">Seleccionar</option>
                                                @foreach($countries as $countrie)
                                                    <option value="{{ $countrie->iso }}" {{ $rescli->nacionalidad == $countrie->iso ? 'selected' : '' }}>{{ $countrie->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="documento" class="form-label">Número de documento <span>*</span></label>
                                            <input type="number" class="form-control" id="documento" name="documento" required value="{{ $rescli->documento }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="celular" class="form-label">Celular <span>*</span></label>
                                            <input type="number" class="form-control" id="celular" name="celular" required value="{{ $rescli->celular }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="sexo" class="form-label">Sexo <span>*</span></label>
                                            <select class="form-control" id="sexo" name="sexo" type="select" required>
                                                <option value="">Seleccionar</option>
                                                <option value="Hombre" {{ $rescli->sexo == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                                <option value="Mujer" {{ $rescli->sexo == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span>*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" required value="{{ $rescli->correo }}" >
                                        </div>

                                        @php
                                            $alergia_id = json_decode($rescli->alergias);
                                            $alimentacion_id = json_decode($rescli->alimentacion);
                                        @endphp

                                        <div class="col-md-12">
                                            <label for="alergias" class="form-label">Alergias</label>
                                            <select class="form-select" id="alergias" name="alergias[]" type="select" data-placeholder="Seleccionar" multiple>
                                                @if($alergia_id && is_array($alergia_id))
                                                    @foreach($alergia_id as $key => $value)
                                                        @foreach($alergias as $alergia)
                                                            @if($value == $alergia->id)
                                                                <option selected value="{{ $alergia->id }}">{{ $alergia->titulo }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @foreach($alergias as $alergia)
                                                                <option value="{{ $alergia->id }}">{{ $alergia->titulo }}</option>
                                                    @endforeach                                                    
                                                @endif
                                                @if($alergias && is_array($alergias))
                                                @foreach($alergias as $alergia)
                                                    <option value="{{ $alergia->id }}">{{ $alergia->titulo }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="alimentacion" class="form-label">Tipo alimentación</label>
                                            <select class="form-select" id="alimentacion" name="alimentacion[]" type="select" data-placeholder="Seleccionar" multiple>
                                                @if($alimentacion_id && is_array($alimentacion_id))
                                                    @foreach($alimentacion_id as $key => $value)
                                                        @foreach($alimentos as $alimento)
                                                            @if($value == $alimento->id)
                                                                <option selected value="{{ $alimento->id }}">{{ $alimento->titulo }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else 
                                                    @foreach($alimentos as $alimento)
                                                        <option value="{{ $alimento->id }}">{{ $alimento->titulo }}</option>
                                                    @endforeach    
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="nota" class="form-label">Nota adicional</label>
                                            <input type="text" class="form-control" id="nota" name="nota" value="{{ $rescli->nota }}" />
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <!-- Descripción arriba del botón -->
                                            <div class="mb-2">
                                                <p class="text-muted mb-1">
                                                    Es importante subir una imagen o un PDF del documento de identidad para su seguridad y la nuestra.
                                                </p>
                                                <strong>(Campo requerido *)</strong>
                                            </div>
                                        
                                            <!-- Botón de carga centrado -->
                                            <label for="file-upload" id="file-upload-btn" class="btn btn-primary mb-3">
                                                Seleccionar un archivo
                                            </label>
                                        
                                            <!-- Input de archivo oculto -->
                                            <input 
                                                class="form-control form-control-solid d-none"
                                                id="file-upload" 
                                                name="file" 
                                                type="file" 
                                                accept=".pdf, .doc, .docx, image/*"
                                                onchange="handleFileUpload(this)"
                                                {{ empty($rescli->file) ? 'required' : '' }} 
                                            />
                                        
                                            <!-- Previsualización del archivo si existe -->
                                            <div id="preview-container">
                                                @if(!empty($rescli->file))
                                                    @php
                                                        $filePath = asset("public/files_documentos/$rescli->file");
                                                        $extension = pathinfo($rescli->file, PATHINFO_EXTENSION);
                                                        $fileName = pathinfo($rescli->file, PATHINFO_BASENAME);
                                                    @endphp
                                        
                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img id="file-image" src="{{ $filePath }}" alt="Documento de identidad" 
                                                            style="max-width: 300px; display: block;">
                                                    @elseif($extension === 'pdf')
                                                        <p id="file-name"><strong>Archivo actual:</strong> {{ $fileName }}</p>
                                                        <a href="{{ $filePath }}" target="_blank" class="btn btn-primary">Descargar PDF</a>
                                                    @endif
                                                @endif
                                            </div>
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

                                    @php
                                        $ticket_id = is_string($rescli->tickets) ? json_decode($rescli->tickets, true) : $rescli->tickets;
                                        $habitacion_id = array_values(is_string($rescli->habitaciones) ? json_decode($rescli->habitaciones, true) : ($rescli->habitaciones ?? []));
                                        $accesorio_id = is_string($rescli->accesorios) ? json_decode($rescli->accesorios, true) : $rescli->accesorios;
                                        $servicio_id = is_string($rescli->servicios) ? json_decode($rescli->servicios, true) : $rescli->servicios;
                                    @endphp

                                    <div class="tab-content py-3">
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
                                                        <input class="form-check-input ticket-checkbox" type="checkbox" name="ticket_id[]" @if($rescli->tickets) @foreach($ticket_id as $tic) @if($tic["id"] == $ticket->id) checked @endif @endforeach @endif value="{{ $ticket->id }}" id="ticket_{{ $ticket->id }}" 
                                                            data-name="{{ $ticket->titulo }}"
                                                            data-nac="{{ number_format($ticket->nacionales, 2, '.', '') }}"
                                                            data-ext="{{ number_format($ticket->extranjeros, 2, '.', '') }}">
                                                        <label class="form-check-label" for="ticket_{{ $ticket->id }}">
                                                            {{ $ticket->titulo }}
                                                            <span class="seccion-mexico hidden">Bs. {{ number_format($ticket->nacionales, 2, '.', '') }}</span>
                                                            <span class="seccion-otros hidden">Bs. {{ number_format($ticket->extranjeros, 2, '.', '') }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tourhoteles" role="tabpanel">
                                            @php $contadorDia = 1; @endphp
                                            @foreach($hotelesSeleccionados as $hotelIds)
                                                @php
                                                    $habitacionSeleccionada = collect($habitacion_id)->firstWhere('dia', $contadorDia);
                                                @endphp
                                            
                                                <div class="row g-3">
                                                    <div class="col-md-12 form-check">
                                                        <label class="form-label" for="noche_{{ $contadorDia }}">
                                                            Día {{ $contadorDia }}
                                                        </label>
                                            
                                                        @foreach($hoteles as $hotel)
                                                            @if(in_array($hotel->id, $hotelIds))
                                                                <div class="form-check">
                                                                    <input class="form-check-input" style="display: none;" type="checkbox" value="{{ $hotel->id }}" id="hotel_{{ $hotel->id }}_{{ $contadorDia }}" />
                                                                    <label class="form-check-label" for="hotel_{{ $hotel->id }}_{{ $contadorDia }}">
                                                                        {{ $hotel->titulo }}
                                                                    </label>
                                            
                                                                    @foreach($habitaciones->where('hotel_id', $hotel->id) as $habitacion)
                                                                        <div class="form-check form_habi{{ $habitacion->id }}{{ $contadorDia }}">
                                                                            <input class="form-check-input habitacion-checkbox" type="radio"
                                                                                name="habitacion_dia_{{ $contadorDia }}"
                                                                                id="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $contadorDia }}"
                                                                                value="{{ $habitacion->id }}"
                                                                                data-name="{{ $habitacion->titulo }}"
                                                                                data-hnac="{{ number_format($habitacion->nacionales, 2, '.', '') }}"
                                                                                data-hext="{{ number_format($habitacion->extranjeros, 2, '.', '') }}"
                                                                                data-tit="{{ $hotel->titulo }}"
                                                                                data-dia="{{ $contadorDia }}"
                                                                                @if($habitacionSeleccionada && $habitacionSeleccionada['id'] == $habitacion->id) checked @endif
                                                                            />
                                                                            <label class="form-check-label" for="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $contadorDia }}">
                                                                                {{ $habitacion->titulo }}
                                                                                <span class="seccion-mexico hidden">Bs. {{ number_format($habitacion->nacionales, 2, '.', '') }}</span>
                                                                                <span class="seccion-otros hidden">Bs. {{ number_format($habitacion->extranjeros, 2, '.', '') }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            
                                                @php $contadorDia++; @endphp
                                            @endforeach
                                        </div>
                                        
                                        <div class="tab-pane fade" id="touraccesorios" role="tabpanel">
                                            <div class="col-md-12">
                                           
                                                @foreach($accesorios as $accesorio)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="accesorio_id[]" @if($rescli->accesorios) @foreach($accesorio_id as $acc) @if($acc["id"] == $accesorio->id) checked @endif @endforeach @endif value="{{ $accesorio->id }}" id="accesorio_{{ $accesorio->id }}" 
                                                            data-aname="{{ $accesorio->titulo }}"
                                                            data-aprecio="{{ number_format($accesorio->venta, 2, '.', '') }}" />
                                                        
                                                        <label class="form-check-label" for="accesorio_{{ $accesorio->id }}">
                                                            {{ $accesorio->titulo }} <span>{{ 'Bs. '.number_format($accesorio->venta, 2, '.', '') }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                           
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tourservicios" role="tabpanel">
                                            <div class="col-md-12">
                                           
                                                @foreach($turistas as $turista)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="servicio_id[]" @if($rescli->servicios) @foreach($servicio_id as $ser) @if($ser["id"] == $turista->id) checked @endif @endforeach @endif value="{{ $turista->id }}" id="turista_{{ $turista->id }}" 
                                                            data-sname="{{ $turista->titulo }}"
                                                            data-sprecio="{{ number_format($turista->venta, 2, '.', '') }}" />
                                                        
                                                        <label class="form-check-label" for="turista_{{ $turista->id }}">
                                                            {{ $turista->titulo }} <span>{{ 'Bs. '.number_format($turista->venta, 2, '.', '') }}</span>
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
                                                <button type="submit" class="btn btn-success continuar col-md-6">Actualizar <i class="fadeIn animated bx bx-arrow-to-right"></i></button>
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
                                    </dl>
                                    
                                    <dl class="row col-md-12" id="totpre" style="display: none;">
                                        <dt class="col-sm-5">Precio</dt>
                                        <dd class="col-sm-7 text-right" id="max_precio"></dd>
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

                                        <dt class="col-sm-9" id="hab_name"></dt>
                                        <dd class="col-sm-3 text-right" id="hab_pre"></dd>
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
        document.addEventListener("DOMContentLoaded", function() {
            const buttonMinus = document.getElementById("button-minus");
            const buttonPlus = document.getElementById("button-plus");
            const cantPerInput = document.getElementById("cantper");
            const preUni = parseFloat(document.getElementById("pre_uni").value);
            const preTot = parseFloat(document.getElementById("pre_tot").value);
            const maxPer = parseFloat(document.getElementById("max_per").value);
            const tourSbt = document.getElementById("tour_Sbt");
            const tourTotal = document.getElementById("tour_total");
            const tPrivadoCheckbox = document.getElementById("tprivado");
            const porPreSection = document.getElementById("porpre");
            const totPreSection = document.getElementById("totpre");
            const maxPrecio = document.getElementById("max_precio");
            const maxPersonas = document.getElementById("max_personas");
            const cantPersDisplay = document.getElementById("cant_pers");
            const fechaLimiteInput = document.getElementById("fecha_limite");

            const createdAt = document.getElementById("created_at").value;
            const horLim = parseInt(document.getElementById("hor_lim").value, 10);
            const nacionalidadSelect = document.getElementById("nacionalidad");

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

            const checkboxesTickets = document.querySelectorAll("input[type='checkbox'][id^='ticket_']");
            const checkboxesAccesorios = document.querySelectorAll("input[type='checkbox'][id^='accesorio_']");
            const checkboxesServicios = document.querySelectorAll("input[type='checkbox'][id^='turista_']");
            const checkboxesHabitaciones = document.querySelectorAll("input[type='radio'][id^='form_habi_']");

            let totalTickets = 0;
            let totalAccesorios = 0;
            let totalServicios = 0;
            let totalHabitaciones = 0;
            // Selecciona todos los checkboxes de tickets al cambiar el checkbox de "Seleccionar todos"
            document.getElementById('select_all_tickets').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.ticket-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCheckboxTotal();
            });            

            // Selecciona todos los checkboxes de habitaciones al cambiar el checkbox de "Seleccionar todos"
            const habitacionCheckboxes = document.querySelectorAll(".habitacion-checkbox");
            habitacionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const name = this.name;
                    if (this.checked) {
                        habitacionCheckboxes.forEach(otherCheckbox => {
                            if (otherCheckbox !== this && otherCheckbox.name === name) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }
                });
            });             

            // Función para manejar el cambio en el select de nacionalidad
            function handleNacionalidadChange() {
                const selectedValue = nacionalidadSelect.value;
                const seccionesMexico = document.querySelectorAll(".seccion-mexico");
                const seccionesOtros = document.querySelectorAll(".seccion-otros");

                seccionesMexico.forEach(seccion => {
                    seccion.classList.toggle("hidden", selectedValue !== "BO");
                });
                seccionesOtros.forEach(seccion => {
                    seccion.classList.toggle("hidden", selectedValue === "BO");
                });

                // Recalcula el total de tickets al cambiar la nacionalidad
                updateCheckboxTotal();
                updateHabitacionTotal();     // ✅ ¡necesitamos esto!
            }

            nacionalidadSelect.addEventListener("change", handleNacionalidadChange);

            // Función para actualizar el total de los tickets seleccionados
            function updateCheckboxTotal() {
                totalTickets = 0;
                let names = "";
                let prices = "";

                checkboxesTickets.forEach(checkbox => {
                    if (checkbox.checked) {
                        const price = parseFloat(nacionalidadSelect.value === "BO" ? checkbox.dataset.nac : checkbox.dataset.ext) || 0;
                        totalTickets += price;

                        names += `${checkbox.dataset.name}<br>`;
                        prices += `Bs. ${price.toFixed(2)}<br>`;
                    }
                });

                if (totalTickets > 0) {
                    ticketsCont.style.display = "inline-flex";
                    ticName.innerHTML = names;
                    ticPre.innerHTML = prices;
                } else {
                    ticketsCont.style.display = "none";
                }

                updateTotal(); // Llama a updateTotal() para actualizar el subtotal
            }

            // Función para actualizar el total de accesorios seleccionados
            function updateAccessoryTotal() {
                totalAccesorios = 0;
                let accessoryNames = "";
                let accessoryPrices = "";

                checkboxesAccesorios.forEach(checkbox => {
                    if (checkbox.checked) {
                        const price = parseFloat(checkbox.dataset.aprecio) || 0;
                        totalAccesorios += price;

                        accessoryNames += `${checkbox.dataset.aname}<br>`;
                        accessoryPrices += `Bs. ${price.toFixed(2)}<br>`;
                    }
                });

                if (totalAccesorios > 0) {
                    accesoriosCont.style.display = "inline-flex";
                    accName.innerHTML = accessoryNames;
                    accPre.innerHTML = accessoryPrices;
                } else {
                    accesoriosCont.style.display = "none";
                }

                updateTotal();
            }

            // Función para actualizar el total de servicios seleccionados
            function updateServicioTotal() {
                totalServicios = 0;
                let servicioNames = "";
                let servicioPrices = "";

                checkboxesServicios.forEach(checkbox => {
                    if (checkbox.checked) {
                        const price = parseFloat(checkbox.dataset.sprecio) || 0;
                        totalServicios += price;

                        servicioNames += `${checkbox.dataset.sname}<br>`;
                        servicioPrices += `Bs. ${price.toFixed(2)}<br>`;
                    }
                });

                if (totalServicios > 0) {
                    serviciosCont.style.display = "inline-flex";
                    serName.innerHTML = servicioNames;
                    serPre.innerHTML = servicioPrices;
                } else {
                    serviciosCont.style.display = "none";
                }

                updateTotal(); // Llama a updateTotal() para actualizar el subtotal
            }

            // Función para actualizar el total de habitaciones seleccionadas
            function updateHabitacionTotal() {
                totalHabitaciones = 0;
                let names = "";
                let prices = "";

                checkboxesHabitaciones.forEach(checkbox => {
                    if (checkbox.checked) {
                        const hotelName = checkbox.dataset.tit; // Asegúrate de usar el dataset.tit
                        const roomName = checkbox.dataset.name;
                        const price = parseFloat(nacionalidadSelect.value === "BO" ? checkbox.dataset.hnac : checkbox.dataset.hext) || 0;

                        totalHabitaciones += price;

                        names += `${hotelName}: ${roomName}<br>`;
                        prices += `Bs. ${price.toFixed(2)}<br>`;
                    }
                });

                if (totalHabitaciones > 0) {
                    habitacionesCont.style.display = "inline-flex";
                    habName.innerHTML = names;
                    habPre.innerHTML = prices;
                } else {
                    habitacionesCont.style.display = "none";
                }

                updateTotal(); // Asegúrate de actualizar el total
            }

            // Función para calcular y actualizar el total acumulado en tourSbt
            function updateTotal() {
                const cantidad = parseInt(cantPerInput.value) || 0;
                const subtotal = cantidad * preUni;
                const totalSum = subtotal + totalTickets + totalAccesorios + totalServicios + totalHabitaciones; // Incluye totalHabitaciones

                tourSbt.innerText = `Bs. ${totalSum.toFixed(2)}`;
                tourTotal.value = `${totalSum.toFixed(2)}`;
            }

            // Eventos para los checkboxes de tickets y accesorios
            checkboxesTickets.forEach(checkbox => checkbox.addEventListener("change", updateCheckboxTotal));
            checkboxesAccesorios.forEach(checkbox => checkbox.addEventListener("change", updateAccessoryTotal));
            checkboxesServicios.forEach(checkbox => checkbox.addEventListener("change", updateServicioTotal));
            checkboxesHabitaciones.forEach(checkbox => checkbox.addEventListener("change", updateHabitacionTotal));

            // Límite de fechas basado en createdAt y horLim
            const createdAtDate = new Date(createdAt);
            const fechaDisponible = new Date(createdAtDate);
            fechaDisponible.setHours(fechaDisponible.getHours() + horLim);

            // Configura la fecha mínima como la fecha disponible (días y horas añadidos)
            fechaLimiteInput.min = fechaDisponible.toISOString().split("T")[0];

            // Si la fecha mínima es mayor que la fecha actual, restringe la selección
            const currentDate = new Date();
            if (currentDate > fechaDisponible) {
                fechaLimiteInput.value = fechaDisponible.toISOString().split("T")[0];
            }

            // Actualiza el subtotal en base a la cantidad seleccionada
            function updateSubtotal() {
                const cantidad = parseInt(cantPerInput.value) || 0;
                const subtotal = cantidad * preUni;
                const totalSum = subtotal + totalTickets + totalAccesorios + totalServicios + totalHabitaciones;

                tourSbt.innerText = `Bs. ${totalSum.toFixed(2)}`;
                tourTotal.value = `${totalSum.toFixed(2)}`;
                cantPersDisplay.innerText = `${cantidad} ${cantidad === 1 ? 'persona' : 'personas'}`;
            }

            // Eventos de los botones de cantidad
            buttonPlus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value) || 1;
                if (cantidad < maxPer) {
                    cantidad++;
                    cantPerInput.value = cantidad;
                    updateSubtotal();
                }
            });

            buttonMinus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value) || 1;
                if (cantidad > 1) {
                    cantidad--;
                    cantPerInput.value = cantidad;
                    updateSubtotal();
                }
            });

            // Modo privado
            tPrivadoCheckbox.addEventListener("change", function() {
                if (tPrivadoCheckbox.checked) {
                    buttonMinus.disabled = true;
                    buttonPlus.disabled = true;
                    porPreSection.style.display = "none";
                    totPreSection.style.display = "inline-flex";
                    maxPrecio.innerText = 'Bs. ' + preTot.toFixed(2);
                    maxPersonas.innerText = maxPer.toFixed(0) + ' personas';
                    tourSbt.innerText = 'Bs. ' + preTot.toFixed(2);
                    tourTotal.value = preTot.toFixed(2);
                } else {
                    buttonMinus.disabled = false;
                    buttonPlus.disabled = false;
                    porPreSection.style.display = "inline-flex";
                    totPreSection.style.display = "none";
                    updateSubtotal();
                }
            });

            function updateSelectedItems() {
                // Tickets seleccionados
                const selectedTickets = Array.from(checkboxesTickets)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.name,
                        price: parseFloat(nacionalidadSelect.value === "BO" ? checkbox.dataset.nac : checkbox.dataset.ext)
                    }));
                document.getElementById("tickets_seleccionados").value = JSON.stringify(selectedTickets);

                // Habitaciones seleccionadas con día
                const selectedRooms = Array.from(checkboxesHabitaciones)
                .filter(radio => radio.checked)
                .map(radio => ({
                    id: radio.value,
                    name: radio.dataset.name,
                    price: parseFloat(nacionalidadSelect.value === "BO" ? radio.dataset.hnac : radio.dataset.hext),
                    dia: parseInt(radio.dataset.dia) // 👈 Esta línea asegura que el día se guarda bien
                }));
                document.getElementById("habitaciones_seleccionadas").value = JSON.stringify(selectedRooms);

                // Accesorios seleccionados
                const selectedAccessories = Array.from(checkboxesAccesorios)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.aname,
                        price: parseFloat(checkbox.dataset.aprecio)
                    }));
                document.getElementById("accesorios_seleccionados").value = JSON.stringify(selectedAccessories);

                // Servicios seleccionados
                const selectedServices = Array.from(checkboxesServicios)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.sname,
                        price: parseFloat(checkbox.dataset.sprecio)
                    }));
                document.getElementById("servicios_seleccionados").value = JSON.stringify(selectedServices);
            }

            // Llama a updateSelectedItems() cada vez que haya un cambio
            checkboxesTickets.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesAccesorios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesServicios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesHabitaciones.forEach(radio => radio.addEventListener("change", updateSelectedItems));

            // Actualiza los valores al cargar la página
            document.addEventListener("DOMContentLoaded", updateSelectedItems);

            handleNacionalidadChange();
            updateSelectedItems();
            updateCheckboxTotal();
            updateAccessoryTotal();
            updateServicioTotal();
            updateHabitacionTotal(); // <- aquí

        });
    </script>

    <script>
        $(document).ready(function () {
            // Aplicar Select2 a los selectores ya generados
            $('#alergias').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: 'Seleccionar',
                closeOnSelect: false,
            });

            $('#alimentacion').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: 'Seleccionar',
                closeOnSelect: false,
            });
        });
    </script>

    <script>
        // File Upload
        function ekUpload() {
            function Init() {
                console.log("Upload Initialised");

                const fileSelect = document.getElementById('file-upload');

                if (fileSelect) {
                    fileSelect.addEventListener('change', fileSelectHandler, false);
                } else {
                    console.error("Elemento #file-upload no encontrado.");
                }
            }

            function fileSelectHandler(e) {
                const file = e.target.files[0];
                if (!file) return;

                const previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = ''; // Limpiar contenido previo

                const fileName = file.name.toLowerCase();
                const isImage = /\.(gif|jpg|jpeg|png)$/i.test(fileName);
                const isPDF = /\.pdf$/i.test(fileName);

                if (isImage) {
                    // Previsualización de imagen centrada
                    const imgElement = document.createElement('img');
                    imgElement.src = URL.createObjectURL(file);
                    imgElement.style.maxWidth = '300px';
                    imgElement.style.height = 'auto';
                    imgElement.style.display = 'block';
                    imgElement.style.margin = 'auto'; // Centrar la imagen
                    previewContainer.appendChild(imgElement);
                } else if (isPDF) {
                    // Mostrar solo el nombre del archivo y botón de descarga
                    const fileNameText = document.createElement('p');
                    fileNameText.innerHTML = `<strong>Archivo seleccionado:</strong> ${file.name}`;
                    previewContainer.appendChild(fileNameText);
                } else {
                    alert('Por favor selecciona un archivo válido (imagen o PDF).');
                }
            }

            // Inicializar solo si el input de archivo existe
            if (document.getElementById('file-upload')) {
                Init();
            }
        }

        // Ejecutar la función al cargar la página
        ekUpload();
    </script>
@endsection
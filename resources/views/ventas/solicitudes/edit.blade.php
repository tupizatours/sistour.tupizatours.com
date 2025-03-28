@extends('layouts.app')

@section('template_title')
    Ver reserva
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
    </style>
@endsection

@section('content')
    <div class="main-body">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-uppercase">{{ $reserva->codigo }}</h4>

                        <h6 class="card-infos">
                            Información del tour <b>{{ $reserva->tour->titulo }}</b>
                        </h6>

                        <h6 class="card-infos">
                            Precio del tour <b>{{ 'Bs. '.number_format($reserva->pre_per, 2, '.', '') }}</b>
                        </h6>

                        @php
                            $originalDate = $reserva->created_at;
                            $newDate = date("d-m-Y", strtotime($originalDate));
                        @endphp

                        <h6 class="card-infos">
                            Fecha de solicitud <b>{{\Carbon\Carbon::parse($reserva->created_at)->format('d-m-Y')}}</b>
                        </h6>
                        @php
                            $originalDate = $reserva->fecha;
                            $fechaDate = date("d-m-Y", strtotime($originalDate));
                        @endphp
                        <h6 class="card-infos">
                            Fecha del tour <b>{{ $fechaDate }}</b>
                        </h6>                        

                        <dl class="row mt-4">
                            <dt class="col-sm-4">Turista</dt>
                            <dt class="col-sm-4">Email</dt>
                            <dt class="col-sm-4">Nacionalidad</dt>

                            <dd class="col-sm-4">
                                {{ $reserva->turistas->first()->nombres.' '.$reserva->turistas->first()->apellidos }}
                            </dd>
                            <dd class="col-sm-4">{{ $reserva->turistas->first()->correo }}</dd>
                            <dd class="col-sm-4">{{ $reserva->turistas->first()->nacionalidad }}</dd>
                        </dl>

                        <dl class="row mt-4">
                            <dt class="col-sm-4">Identificación</dt>
                            <dt class="col-sm-4">Celular</dt>
                            <dt class="col-sm-4"></dt>

                            <dd class="col-sm-4">{{ $reserva->turistas->first()->documento }}</dd>
                            <dd class="col-sm-4">{{ $reserva->turistas->first()->celular }}</dd>
                            <dd class="col-sm-4"></dd>
                        </dl>

                        <dl class="row mt-4">
                            <dt class="col-sm-4">alergias</dt>
                            <dt class="col-sm-4">Alimentación</dt>
                            <dt class="col-sm-4"></dt>

                            @php
                                $alergia_id = json_decode($reserva->turistas->first()->alergias);
                                $alimentacion_id = json_decode($reserva->turistas->first()->alimentacion);
                            @endphp

                            <dd class="col-sm-4">
                            @if($alergia_id && is_array($alergia_id))
                                @foreach($alergia_id as $key => $value)
                                    @foreach($alergias as $alergia)
                                        @if($value == $alergia->id)
                                            <span class="badge bg-primary">{{ $alergia->titulo }}</span>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif    
                            </dd>

                            <dd class="col-sm-4">
                            @if($alimentacion_id && is_array($alimentacion_id))    
                                @foreach($alimentacion_id as $key => $value)
                                    @foreach($alimentos as $alimento)
                                        @if($value == $alimento->id)
                                            <span class="badge bg-primary">{{ $alimento->titulo }}</span>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif    
                            </dd>

                            <dd class="col-sm-4"></dd>
                        </dl>

                        @php
                            $ticket_id = is_string($reserva->turistas->first()->tickets) ? json_decode($reserva->turistas->first()->tickets, true) : $reserva->turistas->first()->tickets;
                            $habitacion_id = is_string($reserva->turistas->first()->habitaciones) ? json_decode($reserva->turistas->first()->habitaciones, true) : $reserva->turistas->first()->habitaciones;
                            $accesorio_id = is_string($reserva->turistas->first()->accesorios) ? json_decode($reserva->turistas->first()->accesorios, true) : $reserva->turistas->first()->accesorios;
                            $servicio_id = is_string($reserva->turistas->first()->servicios) ? json_decode($reserva->turistas->first()->servicios, true) : $reserva->turistas->first()->servicios;
                        @endphp

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Hotel</dt>
                            @if($habitacion_id && is_array($habitacion_id))
                            @foreach($habitacion_id as $habitacion)
                                @foreach($habitaciones as $habit)
                                    @if($habit->id == $habitacion['id'])
                                        <dd class="col-sm-9 mb-0">{{ $habit->hotel->titulo.' - '.$habitacion['name'] }}</dd>
                                        <dt class="col-sm-3">{{ 'Bs. '.number_format($habitacion['price'], 2) }}</dt>
                                    @endif
                                @endforeach
                            @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Tickets</dt>
                            @if($ticket_id && is_array($ticket_id))
                            @foreach ($ticket_id as $ticket)
                                <dd class="col-sm-9 mb-0">{{ $ticket['name'] }}</dd>
                                <dt class="col-sm-3">{{ 'Bs. '.number_format($ticket['price'], 2) }}</dt>
                            @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Accesorios</dt>
                            @if($accesorio_id && is_array($accesorio_id))
                            @foreach ($accesorio_id as $accesorio)
                                <dd class="col-sm-9 mb-0">{{ $accesorio['name'] }}</dd>
                                <dt class="col-sm-3">{{ 'Bs. '.number_format($accesorio['price'], 2) }}</dt>
                            @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Servicios</dt>
                            @if($servicio_id && is_array($servicio_id))
                            @foreach ($servicio_id as $servicio)
                                <dd class="col-sm-9 mb-0">{{ $servicio['name'] }}</dd>
                                <dt class="col-sm-3">{{ 'Bs. '.number_format($servicio['price'], 2) }}</dt>
                            @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Nota adicional</dt>
                            <dd class="col-sm-12 mb-0">{{ $reserva->turistas->first()->nota }}</dd>
                        </dl>
                    </div>
                </div>		
            </div>

            <div class="col-lg-3">
                <!-- PASAPORTE -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">PASAPORTE</h5>
                        <button class="btn btn-link text-dark" data-bs-toggle="collapse" data-bs-target="#collapsePasaporte" 
                                aria-expanded="false" aria-controls="collapsePasaporte">
                            <i class="bx bx-chevron-right"></i> <!-- Flecha derecha por defecto -->
                        </button>
                    </div>
                    <div id="collapsePasaporte" class="collapse">
                        <div class="card-body text-center">
                            @if($reserva->turistas->first()->file)
                                @php
                                    $fileExtension = pathinfo($reserva->turistas->first()->file, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ url('public/files_documentos/' . $reserva->turistas->first()->file) }}" 
                                        class="img-fluid img-thumbnail"
                                        alt="Pasaporte" data-bs-toggle="modal" data-bs-target="#modalPasaporte">
                                @elseif($fileExtension === 'pdf')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPasaporte">
                                        Ver Pasaporte (PDF)
                                    </button>
                                @endif
                            @else
                                <p class="text-muted">No se ha adjuntado un pasaporte.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- MODAL PASAPORTE -->
                <div class="modal fade" id="modalPasaporte" tabindex="-1" aria-labelledby="modalPasaporteLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPasaporteLabel">Pasaporte</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ url('public/files_documentos/' . $reserva->turistas->first()->file) }}" 
                                        class="img-fluid" alt="Pasaporte">
                                @elseif($fileExtension === 'pdf')
                                    <iframe src="{{ url('public/files_documentos/' . $reserva->turistas->first()->file) }}" 
                                            width="100%" height="500px"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMPROBANTE DE PAGO -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">COMPROBANTE DE PAGO</h5>
                        <button class="btn btn-link text-dark" data-bs-toggle="collapse" data-bs-target="#collapseComprobante"
                                aria-expanded="false" aria-controls="collapseComprobante">
                            <i class="bx bx-chevron-right"></i> <!-- Flecha derecha por defecto -->
                        </button>
                    </div>
                    <div id="collapseComprobante" class="collapse">
                        <div class="card-body text-center">
                            @if(!empty($reserva->pago))
                                @php
                                    $pagoExtension = $reserva->pago ? pathinfo($reserva->pago, PATHINFO_EXTENSION) : null;
                                @endphp

                                @if(in_array(strtolower($pagoExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('public/files_pagos/' . $reserva->pago) }}" 
                                        class="img-fluid img-thumbnail"
                                        alt="Comprobante de pago" data-bs-toggle="modal" data-bs-target="#modalComprobante">
                                @elseif($pagoExtension === 'pdf')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalComprobante">
                                        Ver Comprobante (PDF)
                                    </button>
                                @endif
                            @else
                                <p class="text-muted">No se ha adjuntado un comprobante de pago.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- MODAL COMPROBANTE -->
                @if(!empty($reserva->pago) && $pagoExtension)
                    <div class="modal fade" id="modalComprobante" tabindex="-1" aria-labelledby="modalComprobanteLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalComprobanteLabel">Comprobante de Pago</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body text-center">
                                    @if(in_array(strtolower($pagoExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset('public/files_pagos/' . $reserva->pago) }}" 
                                            class="img-fluid" alt="Comprobante de pago">
                                    @elseif($pagoExtension === 'pdf')
                                        <iframe src="{{ asset('public/files_pagos/' . $reserva->pago) }}" 
                                                width="100%" height="500px"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('venpagos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id" />
                            <input type="hidden" value="{{ $reserva->turistas->first()->id }}" id="rescli_id" name="rescli_id" />
                            <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" name="user_id" />
                            <input type="hidden" value="directo" id="forma" name="forma" />

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label"><b>PENDIENTE POR PAGAR</b></label>
                                </div>

                                @php
                                    $prePer = floatval($reserva->pre_per ?? 0);

                                    $canPer = intval($reserva->can_per ?? 0);
                                    $total = floatval($reserva->total ?? 0);

                                    $pag_tot = $total - (($canPer > 1 ? ($canPer - 1) : 0) * $prePer);
                                @endphp

                                <div class="col-md-4">
                                    <label class="form-label text-right"><b>{{ 'Bs. '.number_format($pag_tot, 2, '.', '') }}</b></label>
                                </div>
                                
                                @if($reserva->turistas->first()->pago)
                                    <div class="pago_cont col-md-12">
                                        <img src="{{ asset('files_pagos') }}/{{ $reserva->turistas->first()->pago }}" class="img-fluid" alt="...">
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <label for="monto" class="form-label"><b>MONTO</b> <span>*</span></label>
                                    <input type="number" class="form-control" id="monto" name="monto" required />
                                </div>

                                <div class="col-md-12">
                                    <label for="metodo" class="form-label"><b>MÉTODO DE PAGO</b> <span>*</span></label>
                                    <select type="select" class="form-control" id="metodo" name="metodo" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                                        <option value="Transferencia bancaria">Transferencia bancaria</option>
                                        <option value="QR">QR</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="tour_id" class="form-label"><b>Agregar al tour</b></label>
                                    <select type="select" class="form-control" id="tour_id" name="tour_id">
                                        <option value="">Seleccionar</option>
                                        <option value="">Crear orden</option>
                                        @foreach($reservasDisponibles as $reserva)
                                            <option value="{{ $reserva->id }}">{{ $reserva->codigo }} ({{ $reserva->can_per }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary continuar col-md-12">Realizar pago</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS PARA FLECHAS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.btn[data-bs-toggle="collapse"]').forEach(button => {
                button.addEventListener("click", function () {
                    let icon = this.querySelector("i");
                    if (this.getAttribute("aria-expanded") === "true") {
                        icon.classList.replace("bx-chevron-down", "bx-chevron-right"); // Flecha a la derecha
                    } else {
                        icon.classList.replace("bx-chevron-right", "bx-chevron-down"); // Flecha hacia abajo
                    }
                });
            });
        });
    </script>
@endsection

@section('footer_scripts')
    
@endsection
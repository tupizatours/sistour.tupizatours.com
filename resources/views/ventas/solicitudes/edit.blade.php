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
                            Fecha de solicitud <b>{{ $newDate }}</b>
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
                <div class="card">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-12">PASAPORTE</dt>
                        </dl>

                        @if($reserva->turistas->first()->file)
                            <div class="row">
                                <div class="pago_cont">
                                    <img src="{{ url('files_documentos/'.$reserva->turistas->first()->file) }}" class="img-fluid" alt="Pasaporte">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
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
                                    $pag_tot = ($reserva->total - (($reserva->can_per - 1) * $reserva->pre_per));
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
@endsection

@section('footer_scripts')
    
@endsection
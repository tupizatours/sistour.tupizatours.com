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
                        <h4 class="card-title mb-4 text-uppercase">{{ $rescli->reserva->codigo }}</h4>

                        <h6 class="card-infos">
                            @foreach($reservas as $reserva)
                                @if($reserva->id == $rescli->reserva_id)
                                    Información del tour: <b>{{ $reserva->tour->titulo }}</b>
                                @endif
                            @endforeach
                        </h6>

                        <h6 class="card-infos">
                            Precio del tour: <b>{{ 'Bs. '.number_format($reserva->pre_per, 2, '.', '') }}</b>
                        </h6>

                        @php
                            $originalDate = $reserva->created_at;
                            $newDate = date("Y-m-d", strtotime($originalDate));
                        @endphp

                        <h6 class="card-infos">
                            Fecha de solicitud: <b>{{ $newDate }}</b>
                        </h6>

                        <dl class="row mt-4">
                            <dt class="col-sm-4">Turista</dt>
                            <dt class="col-sm-4">Email</dt>
                            <dt class="col-sm-4">Nacionalidad</dt>

                            <dd class="col-sm-4">
                                {{ $rescli->nombres.' '.$rescli->apellidos }}
                            </dd>
                            <dd class="col-sm-4">{{ $rescli->correo }}</dd>
                            <dd class="col-sm-4">{{ $rescli->nacionalidad }}</dd>
                        </dl>

                        <dl class="row mt-4">
                            <dt class="col-sm-4">Identificación</dt>
                            <dt class="col-sm-4">Celular</dt>
                            <dt class="col-sm-4"></dt>

                            <dd class="col-sm-4">{{ $rescli->documento }}</dd>
                            <dd class="col-sm-4">{{ $rescli->celular }}</dd>
                            <dd class="col-sm-4"></dd>
                        </dl>

                        <dl class="row mt-4">
                            <dt class="col-sm-4">alergias</dt>
                            <dt class="col-sm-4">Alimentación</dt>
                            <dt class="col-sm-4"></dt>

                            @php
                                $alergia_id = json_decode($rescli->alergias);
                                $alimentacion_id = json_decode($rescli->alimentacion);
                            @endphp

                            <dd class="col-sm-4">
                                @if($rescli->alergias && is_array($rescli->alergias))
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
                                @if($rescli->alimentacion && is_array($rescli->alimentacion))
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
                            $ticket_id = is_string($rescli->tickets) ? json_decode($rescli->tickets, true) : $rescli->tickets;
                            $habitacion_id = is_string($rescli->habitaciones) ? json_decode($rescli->habitaciones, true) : $rescli->habitaciones;
                            $accesorio_id = is_string($rescli->accesorios) ? json_decode($rescli->accesorios, true) : $rescli->accesorios;
                            $servicio_id = is_string($rescli->servicios) ? json_decode($rescli->servicios, true) : $rescli->servicios;
                        @endphp

                        <dl class="row mt-4 col-md-6">
                            <dt class="col-sm-12">Hotel</dt>

                            @if($rescli->habitaciones)
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

                        <dl class="row mt-4 col-md-6">
                            <dt class="col-sm-12">Tickets</dt>

                            @if($rescli->tickets)
                                @foreach ($ticket_id as $ticket)
                                    <dd class="col-sm-9 mb-0">{{ $ticket['name'] }}</dd>
                                    <dt class="col-sm-3">{{ 'Bs. '.number_format($ticket['price'], 2) }}</dt>
                                @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-6">
                            <dt class="col-sm-12">Accesorios</dt>

                            @if($rescli->accesorios)
                                @foreach ($accesorio_id as $accesorio)
                                    <dd class="col-sm-9 mb-0">{{ $accesorio['name'] }}</dd>
                                    <dt class="col-sm-3">{{ 'Bs. '.number_format($accesorio['price'], 2) }}</dt>
                                @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-6">
                            <dt class="col-sm-12">Servicios</dt>

                            @if($rescli->servicios)
                                @foreach ($servicio_id as $servicio)
                                    <dd class="col-sm-9 mb-0">{{ $servicio['name'] }}</dd>
                                    <dt class="col-sm-3">{{ 'Bs. '.number_format($servicio['price'], 2) }}</dt>
                                @endforeach
                            @endif
                        </dl>

                        <dl class="row mt-4 col-md-12">
                            <dt class="col-sm-12">Nota adicional</dt>
                            <dd class="col-sm-12 mb-0">{{ $rescli->nota }}</dd>
                        </dl>
                    </div>
                </div>		
            </div>

            @php $tot_cal = 0; $restante = 0; @endphp

            @if($rescli->esPrincipal == "1")
                @php
                    $pag_tot = $rescli->pre_per * 2;
                    $tot_cal = $rescli->total - $pag_tot; 
                    $restante = ($rescli->total - $pag_tot) - $tot_cal;
                @endphp
            @else
                @php
                    $tot_cal = $rescli->total;
                    $restante = $rescli->total - $sumaMonto;
                @endphp
            @endif

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('venpagos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" value="{{ $rescli->reserva_id }}" id="reserva_id" name="reserva_id">
                            <input type="hidden" value="{{ $rescli->id }}" id="rescli_id" name="rescli_id">
                            <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" name="user_id">

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label"><b>TOTAL DEL TOUR</b></label>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-right">
                                        <b>
                                            @if($rescli->esPrincipal == "1")
                                                @php
                                                    $pag_tot = $rescli->pre_per * 2;
                                                @endphp

                                                {{ 'Bs. '.number_format($rescli->total - $pag_tot, 2, '.', '') }}
                                            @else
                                                {{ 'Bs. '.number_format($rescli->total, 2, '.', '') }}
                                            @endif
                                        </b>
                                    </label>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label"><b>PENDIENTE POR PAGAR</b></label>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-right">
                                        <b>
                                            @if($rescli->esPrincipal == "1")
                                                @php
                                                    $pag_tot = $rescli->pre_per * 2;
                                                @endphp

                                                {{ 'Bs. '.number_format(($rescli->total - $pag_tot) - $sumaMonto, 2, '.', '') }}
                                            @else
                                                {{ 'Bs. '.number_format($rescli->total - $sumaMonto, 2, '.', '') }}
                                            @endif
                                        </b>
                                    </label>
                                </div>
                                
                                @if($rescli->pago)
                                    <div class="pago_cont col-md-12">
                                        <img src="{{ asset('files_pagos') }}/{{ $rescli->pago }}" class="img-fluid" alt="...">
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <label for="monto" class="form-label"><b>MONTO</b> <span>*</span></label>
                                    <input type="number" class="form-control" id="monto" name="monto" required />
                                </div>

                                <div class="col-md-12" id="diferencia-container" style="display: none;">
                                    <label for="diferencia" class="form-label text-danger"><b>CAMBIO</b></label>
                                    <input type="text" class="form-control" id="diferencia" name="diferencia" readonly />
                                    @if($restante == 0)
                                        <span class="text-danger"><i>Su reserva ya ha sido cancelado</i></span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="metodo" class="form-label"><b>MÉTODO DE PAGO</b> <span>*</span></label>
                                    <select type="select" class="form-control" id="metodo" name="metodo" required>
                                        <option value="">Seleccionar</option>
                                        @foreach($cobros as $cobro)
                                            <option value="{{ $cobro->titulo }}" data-tipo="{{ $cobro->tipo }}" data-comision="{{ $cobro->comision }}">
                                                {{ $cobro->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="conversion" class="form-label"><b>CONVERSIÓN</b></label>
                                    <input type="text" class="form-control" id="conversion" name="conversion" readonly />
                                </div>

                                <div class="col-md-12">
                                    <label for="comision" class="form-label"><b>COMISIÓN</b></label>
                                    <input type="text" class="form-control" id="comision" name="comision" readonly />
                                </div>

                                <div class="col-md-12">
                                    <label for="total" class="form-label"><b>TOTAL</b></label>
                                    <input type="text" class="form-control" id="total" name="total" readonly />
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary continuar col-md-12">Realizar pago</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-12">PASAPORTE</dt>
                        </dl>

                        <div class="row">
                            <div class="pago_cont">
                                @if($rescli->file)
                                    <img src="{{ asset('files_documentos') }}/{{ $rescli->file }}" class="img-fluid" alt="...">
                                @endif         
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table">
                                <thead class="">
                                    <tr>
                                        <th>Monto</th>
                                        <th>Fecha de pago</th>
                                        <th>Método de pago</th>
                                        <th>Conversión</th>
                                        <th>Operador</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($pagos as $pago)
                                        @if($rescli->id == $pago->rescli_id && $pago->estatus == 1)
                                            @php
                                                $originalDate = $pago->created_at;
                                                $newDate = date("d-m-Y", strtotime($originalDate));
                                            @endphp

                                            <tr>
                                                <td>{{ 'Bs. '.number_format($pago->monto, 2, '.', '') }}</td>
                                                <td>{{ $newDate }}</td>
                                                <td>{{ $pago->metodo }}</td>
                                                <td>{{ 'Bs. '.number_format($pago->conversion, 2, '.', '') }}</td>
                                                <td>{{ $pago->user->first_name.' '.$pago->user->last_name }}</td>
                                                
                                                <td>
                                                    <div class="d-flex order-actions">
                                                        <form action="{{ route('estatus.update', $pago->id) }}" class="ms-1" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $pago->id }}">
                                                                <i class="bx bxs-trash"></i>
                                                            </button>

                                                            <input type="hidden" value="2" id="estatus" name="estatus" />
                                                            <input type="hidden" value="pagos" id="pagina" name="pagina" />

                                                            @include('ventas.resclis.predelete')
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
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const montoInput = document.getElementById("monto");
            const metodoSelect = document.getElementById("metodo");
            const conversionInput = document.getElementById("conversion");
            const comisionInput = document.getElementById("comision");
            const totalInput = document.getElementById("total");
            const diferenciaContainer = document.getElementById("diferencia-container");
            const diferenciaInput = document.getElementById("diferencia");
            const pendientePago = parseFloat({{ $tot_cal - $sumaMonto }}); // Calcula pendiente desde backend

            function calcular() {
                const monto = parseFloat(montoInput.value) || 0;
                const selectedOption = metodoSelect.options[metodoSelect.selectedIndex];
                const tipoCambio = parseFloat(selectedOption.getAttribute('data-tipo')) || 1;
                const comisionPorcentaje = parseFloat(selectedOption.getAttribute('data-comision')) || 0;

                // Calcular la conversión usando el tipo de cambio
                let conversion = monto * tipoCambio;

                // Calcular la comisión como un porcentaje del monto
                const comision = (monto * comisionPorcentaje) / 100;
                comisionInput.value = comision.toFixed(2); // Actualizar el campo de comisión

                // Ajustar la conversión si hay diferencia
                if (monto > pendientePago) {
                    const diferencia = monto - pendientePago;
                    diferenciaInput.value = diferencia.toFixed(2);
                    diferenciaContainer.style.display = "block"; // Mostrar el contenedor de diferencia

                    // Ajustar la conversión para reflejar el monto restante
                    conversion = pendientePago * tipoCambio;
                } else {
                    diferenciaInput.value = "";
                    diferenciaContainer.style.display = "none"; // Ocultar el contenedor de diferencia
                }

                // Actualizar el campo de conversión
                conversionInput.value = conversion.toFixed(2);

                // Calcular el total incluyendo la comisión
                const total = conversion + comision;
                totalInput.value = total.toFixed(2); // Actualizar el campo total
            }

            // Ejecutar el cálculo cuando se cambia el monto o el método de pago
            montoInput.addEventListener("input", calcular);
            metodoSelect.addEventListener("change", calcular);
        });
    </script>
@endsection
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
                            $newDate = date("d-m-Y", strtotime($originalDate));
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

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('venpagos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" value="{{ $rescli->reserva_id }}" id="reserva_id" name="reserva_id">
                            <input type="hidden" value="{{ $rescli->id }}" id="rescli_id" name="rescli_id">
                            <input type="hidden" value="{{ Auth::user()->id }}" id="user_id" name="user_id">
                            <input type="hidden" name="pagina" value="resclis">


                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label"><b>TOTAL DEL TOUR</b></label>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label text-right">
                                        <b>{{ 'Bs. '.number_format($rescli->total, 2, '.', ',') }}</b>
                                    </label>
                                </div>
                            
                                <div class="col-md-8">
                                    <label class="form-label"><b>PENDIENTE POR PAGAR</b></label>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label text-right">
                                        <b>{{ 'Bs. '.number_format($rescli->total_pendiente, 2, '.', ',') }}</b>
                                    </label>
                                </div>

                                <input type="hidden" id="totalPendiente" value="{{ $rescli->total_pendiente }}">


                                <div class="col-md-12">
                                    <label for="metodo" class="form-label"><b>MÉTODO DE PAGO</b> <span>*</span></label>
                                    <select class="form-control" id="metodo" name="metodo" required>
                                        <option value="">Seleccionar</option>
                                        @foreach($cobros as $cobro)
                                            <option value="{{ $cobro->titulo }}" 
                                                data-tipo="{{ $cobro->tipo }}" 
                                                data-comision="{{ $cobro->comision }}">
                                                {{ $cobro->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="monto" class="form-label"><b>MONTO</b> <span>*</span></label>
                                    <input type="number" class="form-control" id="entrada" name="entrada" min="0.01" step="0.0001" required />
                                    <input type="hidden" class="form-control" id="monto" name="monto" />
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="conversion" class="form-label"><b>TASA DE CONVERSIÓN</b></label>
                                    <input type="text" class="form-control" id="conversion" name="conversion" readonly disabled/>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="comision" class="form-label"><b>COMISIÓN</b></label>
                                    <input type="text" class="form-control" id="comision" name="comision" readonly disabled/>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="total" class="form-label"><b>TOTAL A PAGAR</b></label>
                                    <input type="text" class="form-control" id="total" name="total" readonly />
                                </div>

                                <div class="col-md-12">
                                    <label for="total" class="form-label"><b>Vuelto Bs.</b></label>
                                    <input type="text" class="form-control" id="vuelto" name="vuelto" readonly />
                                </div>
                              
                                <div class="col-md-12">
                                    <button type="submit" id="btnPagar" class="btn btn-primary continuar col-md-12" disabled>Realizar pago</button>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>

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
                            @if($rescli->file)
                                @php
                                    $fileExtension = pathinfo($rescli->file, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('public/files_documentos/' . $rescli->file) }}" class="img-fluid img-thumbnail"
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
                                    <img src="{{ asset('public/files_documentos/' . $rescli->file) }}" class="img-fluid" alt="Pasaporte">
                                @elseif($fileExtension === 'pdf')
                                    <iframe src="{{ asset('public/files_documentos/' . $rescli->file) }}" width="100%" height="500px"></iframe>
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
                                    <img src="{{ asset('public/files_pagos/' . $reserva->pago) }}" class="img-fluid img-thumbnail"
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
                                    <img src="{{ asset('public/files_pagos/' . $reserva->pago) }}" class="img-fluid" alt="Comprobante de pago">
                                @elseif($pagoExtension === 'pdf')
                                    <iframe src="{{ asset('public/files_pagos/' . $reserva->pago) }}" width="100%" height="500px"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
                                
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
                                                <td>
                                                    @if($pago->metodo == 'Dolar' || $pago->metodo == 'Paypal')
                                                        {{ '$ '.number_format($pago->monto, 2, '.', '') }} <!-- Mostrar en USD -->
                                                    @else
                                                        {{ 'Bs. '.number_format($pago->monto, 2, '.', '') }} <!-- Mostrar en Bs -->
                                                    @endif
                                                </td>                                                <td>{{ $newDate }}</td>
                                                <td>{{ $pago->metodo }}</td>
                                                <td>
                                                    @if($pago->metodo == 'Dolar' || $pago->metodo == 'Paypal')
                                                        {{ 'Bs. '.number_format($pago->conversion, 2, '.', '') }} <!-- Mostrar conversión en Bs -->
                                                    @else
                                                        {{ '-'}} <!-- Si es en Bs, no necesita conversión -->
                                                    @endif
                                                </td>
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
            const entradaInput = document.getElementById("entrada");
            const montoInput = document.getElementById("monto"); // oculto, se envía al backend
            const metodoSelect = document.getElementById("metodo");
            const totalPendiente = parseFloat(document.getElementById("totalPendiente").value) || 0;
            const conversionInput = document.getElementById("conversion");
            const comisionInput = document.getElementById("comision");
            const totalInput = document.getElementById("total");
            const vueltoInput = document.getElementById("vuelto");
            const btnPagar = document.getElementById("btnPagar");

            let userEditedEntrada = false;

            function calcular(autoEntrada = false) {
                const selectedOption = metodoSelect.options[metodoSelect.selectedIndex];

                if (!selectedOption || selectedOption.value === "") {
                    conversionInput.value = "0.00";
                    comisionInput.value = "0.00";
                    totalInput.value = "0.00";
                    vueltoInput.value = "0.00";
                    montoInput.value = "";
                    entradaInput.value = "";
                    btnPagar.disabled = true;
                    return;
                }

                const tipoCambio = parseFloat(selectedOption.getAttribute('data-tipo')) || 1;
                const comisionFija = parseFloat(selectedOption.getAttribute('data-comision')) || 0;

                let entrada = parseFloat(entradaInput.value) || 0;

                // ✅ Auto-sugerir valor si es cambio de método y el usuario no escribió aún
                if (autoEntrada && !userEditedEntrada) {
                    if (selectedOption.value === "Paypal" || selectedOption.value === "Dolar") {
                        entrada = (totalPendiente / tipoCambio);
                    } else {
                        entrada = totalPendiente;
                    }
                    entradaInput.value = entrada.toFixed(4);
                }

                let montoConvertido = entrada * tipoCambio;
                let vuelto = 0;

                if (montoConvertido > totalPendiente) {
                    vuelto = montoConvertido - totalPendiente;
                    montoConvertido = totalPendiente;
                    entrada = montoConvertido / tipoCambio;
                    entradaInput.value = entrada.toFixed(4);
                }

                montoInput.value = entrada.toFixed(4); // Valor limpio para backend
                const totalPago = montoConvertido + comisionFija;

                conversionInput.value = tipoCambio.toFixed(2);
                comisionInput.value = comisionFija.toFixed(2);
                totalInput.value = totalPago.toFixed(2);
                vueltoInput.value = vuelto > 0 ? vuelto.toFixed(2) : "0.00";

                btnPagar.disabled = entrada <= 0 || selectedOption.value === "";
            }

            metodoSelect.addEventListener("change", function () {
                userEditedEntrada = false;
                calcular(true); // true para autocompletar entrada sugerida
            });

            entradaInput.addEventListener("input", function () {
                userEditedEntrada = true;
                calcular();
            });

            const bolivianosOption = [...metodoSelect.options].find(option => option.value === "Bolivianos");
            if (bolivianosOption) {
                bolivianosOption.selected = true;
            }

            calcular(true); // Llamar con autoEntrada al iniciar
        });

        </script>

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
@extends('layouts.app')

@section('template_title')
    Agregar reserva
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
    </style>
@endsection

@section('content')
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
    
    <form action="{{ route('venreservas.store') }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
        @csrf
            @foreach($tours as $tour)
                @if($tour->id == $_GET['tour_id'])
                    @php
                        // Decodificar los arrays JSON de tickets, accesorios, turistas y hoteles
                        $ticket_ids = json_decode($tour->tickets, true) ?? [];
                        $accesorio_ids = json_decode($tour->accesorios, true) ?? [];
                        $turista_ids = json_decode($tour->turistas, true) ?? [];
                        $hotel_ids = json_decode($tour->hoteles, true) ?? []; 
            
                        // Asegurarnos de que no haya subarreglos en $hotel_ids
                        if (is_array($hotel_ids) && count($hotel_ids) > 0 && is_array($hotel_ids[0])) {
                            // Si es un arreglo de arreglos, lo aplanamos
                            $hotel_ids = array_merge(...$hotel_ids);
                        }
            
                        // Filtrar los tickets, accesorios, turistas y hoteles
                        $tickets = $tickets->whereIn('id', $ticket_ids);
                        $accesorios = $accesorios->whereIn('id', $accesorio_ids);
                        $turistas = $turistas->whereIn('id', $turista_ids);
                        $hoteles = $hoteles->whereIn('id', $hotel_ids); // Aplicamos el filtro a los hoteles
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-2"></div>

                        <div class="col-md-5">
                            <div class="card">
                                <div class="card border-primary mb-0">
                                    <div class="card-body pt-5 pb-5 p-4 fase" id="primera_fase">
                                        @php
                                            $originalDate = $tour->created_at;
                                            $newDate = date("m/d/Y", strtotime($originalDate));
                                        @endphp

                                        <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}" />
                                        <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}" />
                                        <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $tour->pre_tot }}" />
                                        <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $tour->pre_uni }}" />
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
                                            <a href="javascript:;" class="btn btn-primary continuar col-md-12" data-next="segunda_fase">Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i></a>
                                        </div>
                                    </div>

                                    <div class="card-body pt-5 pb-5 p-4 fase" id="segunda_fase" style="display: none;">
                                        <!-- Formulario para datos del cliente -->
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
                                            <!-- Continuar con el formulario... -->
                                        </div>
                                    </div>

                                    <div class="card-body pt-5 pb-5 p-4 fase" id="tercera_fase" style="display: none;">
                                        <ul class="nav nav-tabs nav-primary" role="tablist">
                                            <!-- Tabs para tickets, hoteles, accesorios, servicios -->
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#tourtickets" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-title">Tickets</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- Más tabs -->
                                        </ul>

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
                                                            <input class="form-check-input ticket-checkbox" type="checkbox" name="ticket_id[]" value="{{ $ticket->id }}" id="ticket_{{ $ticket->id }}"
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
                                            <!-- Más contenido de tabs -->
                                        </div>
                                    </div>

                                    <div class="card-body pt-5 pb-5 p-4 fase" id="cuarta_fase" style="display: none;">
                                        <ul class="nav nav-tabs nav-primary" role="tablist">
                                            <!-- Métodos de pago -->
                                        </ul>

                                        <div class="tab-content py-3">
                                            <div class="tab-pane fade show active" id="credito" role="tabpanel">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-0">
                                                            <tbody>
                                                                @foreach($links as $link)
                                                                    @if($link->estatus == "1")
                                                                        <tr>
                                                                            <td>{{ $link->nombre }}</td>
                                                                            <td>{{ $link->descripcion }}</td>
                                                                            <td>
                                                                                <a href="{{ $link->url }}" target="_BLANK" class="btn btn-primary btn-sm radius-30 px-4 col-md-12">
                                                                                    Pagar ahora
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Más métodos de pago -->
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="javascript:;" class="btn btn-danger regresar col-md-6" data-prev="tercera_fase"><i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar</a>
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
                                            <dd class="col-sm-7 text-right" id="cant_pers"></dd>
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

            const horLimInput = document.getElementById("hor_lim");
            const fechaLimiteInput = document.getElementById("fecha_limite");

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
                        const price = parseFloat(nacionalidadSelect.value === "BO" ? checkbox.dataset.hnac : checkbox.dataset.hext) || 0;
                        totalHabitaciones += price;

                        names += `${checkbox.dataset.name}<br>`;
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

                updateTotal();
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

            // Límite de fechas basado en horLim
            if (horLimInput && fechaLimiteInput) {
                const horas = parseInt(horLimInput.value, 10); // Convertimos a número
                const ahora = new Date();

                // Sumamos las horas a la fecha actual
                ahora.setHours(ahora.getHours() + horas);

                // Convertimos la fecha a formato ISO para el input date
                const fechaCalculada = ahora.toISOString().split("T")[0];

                // Establecemos el atributo "min" y el valor inicial en el input fecha_limite
                fechaLimiteInput.min = fechaCalculada;
                fechaLimiteInput.value = fechaCalculada;

                console.log(`Fecha mínima y valor inicial establecido en: ${fechaCalculada}`);
            }

            // Actualiza el subtotal en base a la cantidad seleccionada
            function updateSubtotal() {
                const cantidad = parseInt(cantPerInput.value) || 0;
                const subtotal = cantidad * preUni;
                
                tourSbt.innerText = `Bs. ${subtotal.toFixed(2)}`;
                
                // Guardar solo el valor base en pre_tot
                document.getElementById("pre_tot").value = subtotal.toFixed(2);

                // El total final sí puede sumar los adicionales
                tourTotal.value = `${(subtotal + totalTickets + totalAccesorios + totalServicios + totalHabitaciones).toFixed(2)}`;

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

            // Llamar a la función para actualizar los items seleccionados
            function updateSelectedItems() {
                const selectedTickets = Array.from(checkboxesTickets)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.name,
                        price: parseFloat(nacionalidadSelect.value === "BO" ? checkbox.dataset.nac : checkbox.dataset.ext)
                    }));
                document.getElementById("tickets_seleccionados").value = JSON.stringify(selectedTickets);

                const selectedRooms = Array.from(checkboxesHabitaciones)
                    .filter(radio => radio.checked)
                    .map(radio => ({
                        id: radio.value,
                        name: radio.dataset.name,
                        price: parseFloat(nacionalidadSelect.value === "BO" ? radio.dataset.hnac : radio.dataset.hext),
                        dia: parseInt(radio.dataset.dia)
                    }));
                document.getElementById("habitaciones_seleccionadas").value = JSON.stringify(selectedRooms);

                const selectedAccessories = Array.from(checkboxesAccesorios)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.aname,
                        price: parseFloat(checkbox.dataset.aprecio)
                    }));
                document.getElementById("accesorios_seleccionados").value = JSON.stringify(selectedAccessories);

                const selectedServices = Array.from(checkboxesServicios)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => ({
                        id: checkbox.value,
                        name: checkbox.dataset.sname,
                        price: parseFloat(checkbox.dataset.sprecio)
                    }));
                document.getElementById("servicios_seleccionados").value = JSON.stringify(selectedServices);
            }

            checkboxesTickets.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesAccesorios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesServicios.forEach(checkbox => checkbox.addEventListener("change", updateSelectedItems));
            checkboxesHabitaciones.forEach(radio => radio.addEventListener("change", updateSelectedItems));

            // Actualiza los valores al cargar la página
            document.addEventListener("DOMContentLoaded", updateSelectedItems);

            handleNacionalidadChange();
            updateCheckboxTotal();
            updateAccessoryTotal();
            updateServicioTotal();
        });
    </script>

    <script>
        $(document).ready(function () {
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
        function ekUpload(){
            function Init() {
                console.log("Upload Initialised");

                var fileSelect    = document.getElementById('file-upload'),
                    fileDrag      = document.getElementById('file-drag'),
                    submitButton  = document.getElementById('submit-button');

                fileSelect.addEventListener('change', fileSelectHandler, false);

                var xhr = new XMLHttpRequest();
                if (xhr.upload) {
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
                var files = e.target.files || e.dataTransfer.files;
                fileDragHover(e);
                for (var i = 0, f; f = files[i]; i++) {
                    parseFile(f);
                    uploadFile(f);
                }
            }

            function output(msg) {
                var m = document.getElementById('messages');
                m.innerHTML = msg;
            }

            function parseFile(file) {
                var fileName = file.name.toLowerCase();
                var isImage = /\.(gif|jpg|jpeg|png)$/i.test(fileName);
                var isPDF = /\.pdf$/i.test(fileName);

                if (isImage) {
                    document.getElementById('file-image').classList.remove("hidden");
                    document.getElementById('file-image').src = URL.createObjectURL(file);
                    document.getElementById('pdf-preview').classList.add("hidden");
                    document.getElementById('pdf-upload').textContent = 'Selecciona el archivo a cargar';
                } else if (isPDF) {
                    document.getElementById('pdf-preview').classList.remove("hidden");
                    document.getElementById('pdf-preview').src = URL.createObjectURL(file);
                    document.getElementById('file-image').classList.add("hidden");
                    document.getElementById('pdf-upload').textContent = fileName;
                } else {
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
                    fileSizeLimit = 1024;

                if (xhr.upload) {
                    if (file.size <= fileSizeLimit * 1024 * 1024) {
                        pBar.style.display = 'inline';
                        xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
                        xhr.upload.addEventListener('progress', updateFileProgress, false);

                        xhr.onreadystatechange = function(e) {
                            if (xhr.readyState == 4) {
                                // progress.className = (xhr.status == 200 ? "success" : "failure");
                            }
                        };

                        xhr.open('POST', document.getElementById('file-upload-form').action, true);
                        xhr.setRequestHeader('X-File-Name', file.name);
                        xhr.setRequestHeader('X-File-Size', file.size);
                        xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                        xhr.send(file);
                    } else {
                        output('Por favor sube un archivo más pequeño (< ' + fileSizeLimit + ' MB).');
                    }
                }
            }

            if (window.File && window.FileList && window.FileReader) {
                Init();
            } else {
                document.getElementById('file-drag').style.display = 'none';
            }
        }
        ekUpload();
    </script>
@endsection

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

@extends('layouts.app')

@section('content')
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />

    <form action="{{ route('venreservas.store') }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
        @csrf
        @foreach($tours as $tour)
            @if($tour->id == $_GET['tour_id'])
                @php
                    // Decodificar las IDs de los diferentes elementos
                    $ticket_ids = json_decode($tour->tickets, true) ?? [];
                    $accesorio_ids = json_decode($tour->accesorios, true) ?? [];
                    $turista_ids = json_decode($tour->turistas, true) ?? [];
                    $hotel_ids = array_merge(...json_decode($tour->hoteles, true) ?? []);
                    
                    // Filtrar los modelos con las IDs seleccionadas
                    $tickets = $tickets->whereIn('id', $ticket_ids);
                    $accesorios = $accesorios->whereIn('id', $accesorio_ids);
                    $turistas = $turistas->whereIn('id', $turista_ids);
                    $hoteles = $hoteles->whereIn('id', $hotel_ids)->with('habitaciones');
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
                            </div>
                        </div>
                    </div>

                    <!-- Segunda fase: Información del cliente -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card border-primary mb-0">
                                <div class="card-body pt-5 pb-5 p-4 fase" id="segunda_fase" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="nombres" class="form-label">Nombres <span>*</span></label>
                                            <input type="text" class="form-control" id="nombres" name="nombres" required />
                                        </div>
                                        <!-- Otros campos -->
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="javascript:regresar2();" class="btn btn-danger regresar2 col-md-6" data-prev="primera_fase"><i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar</a>
                                        <a href="javascript:continuar2();" class="btn btn-primary continuar2 col-md-6" data-next="tercera_fase">Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i></a>
                                    </div>
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
            // Función de manejo de la cantidad de personas y precio
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

            // Modo privado
            tPrivadoCheckbox.addEventListener("change", function() {
                if (tPrivadoCheckbox.checked) {
                    buttonMinus.disabled = true;
                    buttonPlus.disabled = true;
                    porPreSection.style.display = "none";
                    totPreSection.style.display = "inline-flex";
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

            // Actualiza el total al cambiar la cantidad de personas
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

            // Actualiza el subtotal con el precio
            function updateSubtotal() {
                const cantidad = parseInt(cantPerInput.value) || 0;
                const subtotal = cantidad * preUni;
                tourSbt.innerText = `Bs. ${subtotal.toFixed(2)}`;
                tourTotal.value = `${(subtotal + totalTickets + totalAccesorios + totalServicios + totalHabitaciones).toFixed(2)}`;
            }
        });
    </script>
@endsection

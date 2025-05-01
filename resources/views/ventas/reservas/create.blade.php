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
    <form action="{{ route('reservas.store') }}" method="POST" id="file-upload-form" enctype="multipart/form-data">
        @csrf

        {{-- Datos necesarios --}}
        <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}">
        <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}">
        <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $tour->pre_tot }}">
        <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $tour->pre_uni }}">
        <input type="hidden" id="tour_id" name="tour_id" value="{{ $tour->id }}">
        <input type="hidden" id="estatus" name="estatus" value="1">

        <div class="row">
            <div class="col-md-7">
                <x-reserva.fases.primer-fase :tour="$tour" />
                <x-reserva.fases.segunda-fase :countries="$countries" :alergias="$alergias" :alimentos="$alimentos" />
                <x-reserva.fases.tercera-fase
                :tour="$tour"
                :tickets="$tickets"
                :hoteles="$hoteles"
                :habitaciones="$habitaciones"
                :accesorios="$accesorios"
                :turistas="$turistas"
                />                
                <x-reserva.fases.cuarta-fase :links="$links" :onlines="$onlines" :qrs="$qrs" />
            </div>

            <div class="col-md-5">
                <x-reserva.resumen-final :tour="$tour" />
            </div>
        </div>
    </form>
@endsection


@section('footer_scripts')
    <script>
       document.addEventListener("DOMContentLoaded", function () {
            const $ = (id) => document.getElementById(id);

            // Inputs generales
            const cantPerInput = $("cantper");
            const preUni = parseFloat($("pre_uni").value);
            const preTot = parseFloat($("pre_tot").value);
            const maxPer = parseFloat($("max_per").value);
            const horLim = parseInt($("hor_lim").value);
            const tourTotal = $("tour_total");

            // Secciones y botones
            const porPre = $("porpre"), totPre = $("totpre");
            const maxPrecio = $("max_precio"), maxPersonas = $("max_personas");
            const cantPers = $("cant_pers");
            const tPrivado = $("tprivado");
            const btnMinus = $("button-minus"), btnPlus = $("button-plus");

            // Fecha límite
            const fechaLimiteInput = $("fecha_limite");
            const setFechaMinima = () => {
                const ahora = new Date();
                ahora.setHours(ahora.getHours() + horLim);
                const fecha = ahora.toISOString().split("T")[0];
                fechaLimiteInput.min = fecha;
                fechaLimiteInput.value = fecha;
            };

            // Nacionalidad
            const nacionalidad = $("nacionalidad");
            nacionalidad?.addEventListener("change", () => {
                document.querySelectorAll(".seccion-mexico").forEach(e => e.classList.toggle("hidden", nacionalidad.value !== "BO"));
                document.querySelectorAll(".seccion-otros").forEach(e => e.classList.toggle("hidden", nacionalidad.value === "BO"));
                updateAllTotals();
            });

            // Totales
            let totals = {
                tickets: 0,
                accesorios: 0,
                servicios: 0,
                habitaciones: 0
            };

            const selectors = {
                tickets: { check: "[id^='ticket_']", cont: "tickets_cont", name: "tic_name", pre: "tic_pre", attr: ["name", "nac", "ext"] },
                accesorios: { check: "[id^='accesorio_']", cont: "accesorios_cont", name: "acc_name", pre: "acc_pre", attr: ["aname", "aprecio"] },
                servicios: { check: "[id^='turista_']", cont: "servicios_cont", name: "ser_name", pre: "ser_pre", attr: ["sname", "sprecio"] },
                habitaciones: { check: "input[type='radio'][id^='form_habi_']", cont: "habitaciones_cont", name: "hab_name", pre: "hab_pre", attr: ["name", "hnac", "hext", "dia"] },
            };

            const updateGroupTotal = (type) => {
                const group = selectors[type];
                const items = document.querySelectorAll(group.check);
                let total = 0;
                let names = "", prices = "";
                items.forEach(el => {
                    if (el.checked) {
                        const price = parseFloat(
                            type === "tickets" || type === "habitaciones"
                                ? nacionalidad.value === "BO" ? el.dataset[group.attr[1]] : el.dataset[group.attr[2]]
                                : el.dataset[group.attr[1]]
                        ) || 0;
                        total += price;
                        names += `${el.dataset[group.attr[0]]}<br>`;
                        prices += `Bs. ${price.toFixed(2)}<br>`;
                    }
                });
                totals[type] = total;
                const cont = $(group.cont);
                if (total > 0) {
                    cont.style.display = "inline-flex";
                    $(group.name).innerHTML = names;
                    $(group.pre).innerHTML = prices;
                } else {
                    cont.style.display = "none";
                }
            };

            const updateAllTotals = () => {
                Object.keys(selectors).forEach(updateGroupTotal);
                const base = parseInt(cantPerInput.value) || 1;
                const subtotal = base * preUni;
                const total = subtotal + Object.values(totals).reduce((a, b) => a + b, 0);
                tourSbt.innerText = `Bs. ${total.toFixed(2)}`;
                tourTotal.value = total.toFixed(2);
                cantPers.innerText = `${base} ${base === 1 ? "persona" : "personas"}`;
            };

            // Eventos de cambio
            Object.keys(selectors).forEach(type => {
                document.querySelectorAll(selectors[type].check).forEach(el => {
                    el.addEventListener("change", () => {
                        updateGroupTotal(type);
                        saveSelections();
                    });
                });
            });

            // Guardar selecciones como JSON
            const saveSelections = () => {
                const mapToJson = (selector, builderFn) =>
                    JSON.stringify(
                        Array.from(document.querySelectorAll(selector))
                            .filter(el => el.checked)
                            .map(builderFn)
                    );

                $("tickets_seleccionados").value = mapToJson(selectors.tickets.check, el => ({
                    id: el.value, name: el.dataset.name, price: parseFloat(nacionalidad.value === "BO" ? el.dataset.nac : el.dataset.ext)
                }));

                $("accesorios_seleccionados").value = mapToJson(selectors.accesorios.check, el => ({
                    id: el.value, name: el.dataset.aname, price: parseFloat(el.dataset.aprecio)
                }));

                $("servicios_seleccionados").value = mapToJson(selectors.servicios.check, el => ({
                    id: el.value, name: el.dataset.sname, price: parseFloat(el.dataset.sprecio)
                }));

                $("habitaciones_seleccionadas").value = mapToJson(selectors.habitaciones.check, el => ({
                    id: el.value, name: el.dataset.name, price: parseFloat(nacionalidad.value === "BO" ? el.dataset.hnac : el.dataset.hext), dia: parseInt(el.dataset.dia)
                }));
            };

            // Cantidad de personas
            btnPlus?.addEventListener("click", () => {
                let value = parseInt(cantPerInput.value) || 1;
                if (value < maxPer) cantPerInput.value = ++value;
                updateAllTotals();
            });

            btnMinus?.addEventListener("click", () => {
                let value = parseInt(cantPerInput.value) || 1;
                if (value > 1) cantPerInput.value = --value;
                updateAllTotals();
            });

            // Tour privado
            tPrivado?.addEventListener("change", () => {
                const isPrivate = tPrivado.checked;
                btnPlus.disabled = btnMinus.disabled = isPrivate;
                porPre.style.display = isPrivate ? "none" : "inline-flex";
                totPre.style.display = isPrivate ? "inline-flex" : "none";
                if (isPrivate) {
                    maxPrecio.innerText = `Bs. ${preTot.toFixed(2)}`;
                    maxPersonas.innerText = `${maxPer} personas`;
                    tourSbt.innerText = `Bs. ${preTot.toFixed(2)}`;
                    tourTotal.value = preTot.toFixed(2);
                } else {
                    updateAllTotals();
                }
            });

            // Navegación entre fases
            document.querySelectorAll(".continuar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const next = btn.dataset.next;
                    btn.closest(".fase").style.display = "none";
                    $(next).style.display = "block";
                });
            });

            document.querySelectorAll(".regresar").forEach(btn => {
                btn.addEventListener("click", () => {
                    const prev = btn.dataset.prev;
                    btn.closest(".fase").style.display = "none";
                    $(prev).style.display = "block";
                });
            });

            // Validación de fase 2
            window.continuar2 = function () {
                const campos = document.querySelectorAll("#segunda_fase [required]");
                const vacios = Array.from(campos).some(input => !input.value.trim());
                if (vacios) {
                    alert("Por favor llene los campos obligatorios *");
                    return;
                }
                $("segunda_fase").style.display = "none";
                $("tercera_fase").style.display = "block";
            };

            window.regresar2 = function () {
                $("segunda_fase").style.display = "none";
                $("primera_fase").style.display = "block";
            };

            // Iniciar procesos
            setFechaMinima();
            handleNacionalidadChange();
            updateAllTotals();
            saveSelections();
        });

    </script>
@endsection

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

        .hidden {
            display: none;
        }
        .tab-pane .form-check-label {
            width: 100%;
        }
        .tab-pane .form-check-label span {
            float: right;
        }
        .uploader {
            #file-drag {
                background: #f9f9f9;
                border: 2px dashed #ccc;
                padding: 2rem;
                text-align: center;
                border-radius: 10px;
                transition: 0.3s ease-in-out;

                &.hover {
                    border-color: #007bff;
                    background: #eef7ff;
                }
            }

            #preview-container {
                margin-bottom: 1rem;

                img#file-image {
                    max-width: 160px;
                    max-height: 160px;
                    object-fit: cover;
                    display: block;
                    margin: 0 auto;
                    border-radius: 6px;

                    &.hidden {
                        display: none;
                    }
                }

                iframe#pdf-preview {
                    width: 100%;
                    height: 300px;
                    border: none;

                    &.hidden {
                        display: none;
                    }
                }
            }

            input[type="file"] {
                display: none;
            }

            #file-info {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .progress {
                width: 100%;
                max-width: 180px;
                height: 8px;
                border-radius: 4px;
                margin: 1rem auto;
                display: block;
            }
        }


    </style>
@endsection

@section('content')
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
    <div class="row">
        <div class="col-md-7">

            <div class="card">
                <div class="card border-primary mb-0">
                    <form action="{{ route('venreservas.store') }}" method="POST" id="file-upload-form" enctype="multipart/form-data">
                        @csrf

                        {{-- Datos necesarios --}}
                        <input type="hidden" id="hor_lim" name="hor_lim" value="{{ $tour->hor_lim }}">
                        <input type="hidden" id="max_per" name="max_per" value="{{ $tour->max_per }}">
                        <input type="hidden" id="pre_tot" name="pre_tot" value="{{ $tour->pre_tot }}">
                        <input type="hidden" id="pre_uni" name="pre_uni" value="{{ $tour->pre_uni }}">
                        <input type="hidden" id="tour_id" name="tour_id" value="{{ $tour->id }}">
                        <input type="hidden" id="estatus" name="estatus" value="1">

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
                    
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <x-reserva.resumen-final :tour="$tour" />
        </div>
    </div>

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
            const tourSbt = $("tour_Sbt");

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
                        updateAllTotals(); // <-- asegúrate de que esté presente aquí
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
    <script>
        $(document).ready(function () {
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
        document.addEventListener("DOMContentLoaded", function () {
            const $ = id => document.getElementById(id);

            const fileInput = $("file-upload");
            const fileDrag = $("file-drag");
            const fileImage = $("file-image");
            const pdfPreview = $("pdf-preview");
            const pdfLabel = $("pdf-upload");
            const fileProgress = $("file-progress");
            const fileUploadBtn = $("file-upload-btn");

            if (!fileInput || !fileDrag) return;

            const handleHover = (e) => {
                e.preventDefault();
                e.stopPropagation();
                fileDrag.classList.toggle("hover", e.type === 'dragover');
            };

            const previewFile = (file) => {
                const fileName = file.name.toLowerCase();
                const isImage = /\.(gif|jpe?g|png)$/i.test(fileName);
                const isPDF = /\.pdf$/i.test(fileName);

                fileImage.classList.add("hidden");
                pdfPreview.classList.add("hidden");

                if (isImage) {
                    fileImage.src = URL.createObjectURL(file);
                    fileImage.classList.remove("hidden");
                    pdfLabel.textContent = file.name;
                } else if (isPDF) {
                    pdfPreview.src = URL.createObjectURL(file);
                    pdfPreview.classList.remove("hidden");
                    pdfLabel.textContent = file.name;
                } else {
                    pdfLabel.textContent = "Archivo no soportado";
                    alert("Selecciona una imagen o PDF válido.");
                }
            };

            const handleFileSelect = (e) => {
                const files = e.target.files || e.dataTransfer.files;
                handleHover(e);
                [...files].forEach(previewFile);
            };

            ["dragover", "dragleave", "drop"].forEach(event =>
                fileDrag.addEventListener(event, handleHover, false)
            );

            fileInput.addEventListener("change", handleFileSelect, false);
            fileDrag.addEventListener("drop", handleFileSelect, false);

            // botón que dispara la selección
            fileUploadBtn.addEventListener("click", () => {
                fileInput.click();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const selectAllTickets = document.getElementById('select_all_tickets');
            const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    
            if (selectAllTickets) {
                selectAllTickets.addEventListener('change', function () {
                    ticketCheckboxes.forEach(cb => cb.checked = this.checked);
                    
                    // Disparar evento manualmente para que se actualice el total
                    ticketCheckboxes.forEach(cb => cb.dispatchEvent(new Event('change')));
                });
            }
        });
    </script>    
@endsection

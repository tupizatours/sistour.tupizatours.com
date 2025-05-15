<div class="card-body pt-5 pb-5 p-4 fase" id="tercera_fase" style="display: none;">
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
        {{-- Tickets --}}
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

        {{-- Hoteles --}}
        <div class="tab-pane fade" id="tourhoteles" role="tabpanel">
            @php
                $hotelesSeleccionados = json_decode($tour->hoteles, true) ?? [];
            @endphp
            @foreach($hotelesSeleccionados as $dia => $hotelIds)
                <div class="row g-3">
                    <div class="col-md-12 form-check">
                        <label class="form-label" for="noche_{{ $dia }}">
                            Día {{ $dia }}
                        </label>

                        @foreach ($hoteles as $hotel)
                            @if(in_array($hotel->id, $hotelIds))
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $hotel->id }}" id="hotel_{{ $hotel->id }}_{{ $dia }}" />
                                    <label class="form-check-label" for="hotel_{{ $hotel->id }}_{{ $dia }}">
                                        {{ $hotel->titulo }}
                                    </label>

                                    @foreach($habitaciones->where('hotel_id', $hotel->id) as $habitacion)
                                        <div class="form-check form_habi{{ $habitacion->id }}{{ $dia }}">
                                            <input class="form-check-input" type="radio"
                                                value="{{ $habitacion->id }}"
                                                id="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $dia }}"
                                                name="habitacion_dia_{{ $dia }}"
                                                data-name="{{ $habitacion->titulo }}"
                                                data-hnac="{{ number_format($habitacion->nacionales, 2, '.', '') }}"
                                                data-hext="{{ number_format($habitacion->extranjeros, 2, '.', '') }}"
                                                data-dia="{{ $dia }}" />
                                            <label class="form-check-label" for="form_habi_{{ $hotel->id }}_{{ $habitacion->id }}_dia{{ $dia }}">
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
            @endforeach
        </div>

        {{-- Accesorios --}}
        <div class="tab-pane fade" id="touraccesorios" role="tabpanel">
            <div class="col-md-12">
                @foreach($accesorios as $accesorio)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="accesorio_id[]" value="{{ $accesorio->id }}" id="accesorio_{{ $accesorio->id }}"
                            data-aname="{{ $accesorio->titulo }}"
                            data-aprecio="{{ number_format($accesorio->venta, 2, '.', '') }}">
                        <label class="form-check-label" for="accesorio_{{ $accesorio->id }}">
                            {{ $accesorio->titulo }} <span>Bs. {{ number_format($accesorio->venta, 2, '.', '') }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Servicios --}}
        <div class="tab-pane fade" id="tourservicios" role="tabpanel">
            <div class="col-md-12">
                @foreach($turistas as $turista)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="servicio_id[]" value="{{ $turista->id }}" id="turista_{{ $turista->id }}"
                            data-sname="{{ $turista->titulo }}"
                            data-sprecio="{{ number_format($turista->venta, 2, '.', '') }}">
                        <label class="form-check-label" for="turista_{{ $turista->id }}">
                            {{ $turista->titulo }} <span>Bs. {{ number_format($turista->venta, 2, '.', '') }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-center gap-2">
                <a href="javascript:;" class="btn btn-danger regresar col-md-6" data-prev="segunda_fase">
                    <i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar
                </a>
                <a href="javascript:;" class="btn btn-primary continuar col-md-6" data-next="cuarta_fase">
                    Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i>
                </a>
            </div>
        </div>
    </div>

    <input type="hidden" name="tickets_seleccionados" id="tickets_seleccionados" />
    <input type="hidden" name="habitaciones_seleccionadas" id="habitaciones_seleccionadas" />
    <input type="hidden" name="accesorios_seleccionados" id="accesorios_seleccionados" />
    <input type="hidden" name="servicios_seleccionados" id="servicios_seleccionados" />
    <input type="hidden" name="tour_total" id="tour_total" />
</div>

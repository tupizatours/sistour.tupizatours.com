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

            {{-- Tickets --}}
            <dl class="col-md-12 row tickets_cont" id="tickets_cont" style="display: none;">
                <dt class="col-sm-12">
                    <span class="btn btn-inverse-success mb-3 col-md-12">Tickets</span>
                </dt>
                <dt class="col-sm-5" id="tic_name"></dt>
                <dd class="col-sm-7 text-right" id="tic_pre"></dd>
            </dl>

            {{-- Habitaciones --}}
            <dl class="col-md-12 row habitaciones_cont" id="habitaciones_cont" style="display: none;">
                <dt class="col-sm-12">
                    <span class="btn btn-inverse-success mb-3 col-md-12">Habitaciones</span>
                </dt>
                <dt class="col-sm-9" id="hab_name"></dt>
                <dd class="col-sm-3 text-right" id="hab_pre"></dd>
            </dl>

            {{-- Accesorios --}}
            <dl class="col-md-12 row accesorios_cont" id="accesorios_cont" style="display: none;">
                <dt class="col-sm-12">
                    <span class="btn btn-inverse-success mb-3 col-md-12">Accesorios</span>
                </dt>
                <dt class="col-sm-5" id="acc_name"></dt>
                <dd class="col-sm-7 text-right" id="acc_pre"></dd>
            </dl>

            {{-- Servicios --}}
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

            {{-- Inputs ocultos para el backend --}}
            <input type="hidden" name="tickets_seleccionados" id="tickets_seleccionados" value="">
            <input type="hidden" name="habitaciones_seleccionadas" id="habitaciones_seleccionadas" value="">
            <input type="hidden" name="accesorios_seleccionados" id="accesorios_seleccionados" value="">
            <input type="hidden" name="servicios_seleccionados" id="servicios_seleccionados" value="">
            <input type="hidden" name="tour_total" id="tour_total" value="">
        </div>
    </div>
</div>

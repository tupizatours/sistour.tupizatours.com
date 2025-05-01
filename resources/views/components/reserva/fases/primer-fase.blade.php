<div class="card-body pt-5 pb-5 p-4 fase" id="primera_fase">
    <h5 class="card-title text-black text-center"><b>{{ $tour->titulo }}</b></h5>

    <dl class="row">
        <dt class="col-sm-3">Precio</dt>
        <dd class="col-sm-9 text-right">{{ 'Bs. ' . number_format($tour->pre_uni, 2, '.', '') }}</dd>
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

    <div class="d-flex justify-content-center gap-2">
        <a href="javascript:;" class="btn btn-primary continuar col-md-12" data-next="segunda_fase">
            Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i>
        </a>
    </div>
</div>

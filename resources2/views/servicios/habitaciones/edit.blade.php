<div class="modal fade" id="ModalEdit{{ $habitacion->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">EDITAR HABITACIÓN</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <input type="hidden" id="hotel_id" name="hotel_id" value="{{ $habitacion->hotel_id }}">

                    <div class="form-group mb-2 mt-2 col-md-12">
                        <label class="mb-2">Titulo</label>
                        <input class="form-control form-control-solid" id="titulo" name="titulo" type="text" required value="{{ $habitacion->titulo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio de costo</label>
                        <input class="form-control form-control-solid" id="costo" name="costo" type="number" required value="{{ $habitacion->costo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio de costo extranjeros</label>
                        <input class="form-control form-control-solid" id="cos_ext" name="cos_ext" type="number" required value="{{ $habitacion->cos_ext }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio nacionales</label>
                        <input class="form-control form-control-solid" id="nacionales" name="nacionales" type="number" required value="{{ $habitacion->nacionales }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio extranjeros</label>
                        <input class="form-control form-control-solid" id="extranjeros" name="extranjeros" type="number" required value="{{ $habitacion->extranjeros }}" />
                    </div>

                    <input id="estatus" name="estatus" type="hidden" value="{{ $habitacion->estatus }}" />
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="button" class="btn btn-dark col-md-12 font-14" data-bs-dismiss="modal">CANCELAR</button>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="submit" class="btn btn-success col-md-12 font-14">ACTUALIZAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEdit{{ $bicicleta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">EDITAR BICICLETA</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Propietario</label>
                        <select class="form-control form-control-solid" id="propietario_id" name="propietario_id" type="select" required>
                            <option value="{{ $bicicleta->propietario->id }}">{{ $bicicleta->propietario->nombre.' '.$bicicleta->propietario->apellido }}</option>
                            <option value="">Seleccionar</option>
                            @foreach($propietarios as $propietario)
                                <option value="{{ $propietario->id }}">{{ $propietario->nombre.' '.$propietario->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Nombre</label>
                        <input class="form-control form-control-solid" id="nombre" name="nombre" type="text" value="{{ $bicicleta->nombre }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio de costo</label>
                        <input class="form-control form-control-solid" id="costo" name="costo" type="number" value="{{ $bicicleta->costo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Precio de venta</label>
                        <input class="form-control form-control-solid" id="cos_ext" name="venta" type="number" value="{{ $bicicleta->venta }}" />
                    </div>

                    <input id="estatus" name="estatus" type="hidden" value="{{ $bicicleta->estatus }}" />
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
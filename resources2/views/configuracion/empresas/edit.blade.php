<div class="modal fade" id="ModalEdit{{ $empresa->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success pt-3 py-4 pb-3">
                <h6 class="modal-title text-white">EDITAR EMPRESA</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white p-4">
                <div class="row g-3 pt-3 pb-2 col-md-12">
                <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Nombre/Razón Social</label>
                        <input class="form-control form-control-solid" id="nombre" name="nombre" type="text" required value="{{ $empresa->nombre }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Dirección</label>
                        <input class="form-control form-control-solid" id="direccion" name="direccion" type="text" required value="{{ $empresa->direccion }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Teléfono</label>
                        <input class="form-control form-control-solid" id="telefono" name="telefono" type="text" required value="{{ $empresa->telefono }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Correo electrónico</label>
                        <input class="form-control form-control-solid" id="correo" name="correo" type="email" required value="{{ $empresa->correo }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Sitio web</label>
                        <input class="form-control form-control-solid" id="web" name="web" type="text" required value="{{ $empresa->web }}" />
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <label class="mb-2">Número de NIT</label>
                        <input class="form-control form-control-solid" id="nit" name="nit" type="text" required value="{{ $empresa->nit }}" />
                    </div>

                    <input class="form-control form-control-solid" id="estatus" name="estatus" type="hidden" value="{{ $empresa->estatus }}" />
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
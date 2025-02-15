<div class="modal fade ModalPreDelete" id="ModalPreDelete{{ $turista->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent pt-3 py-4 pb-3">
                <h6 class="modal-title">Eliminar servicio de turista</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-white pt-2 pb-2 p-4">
                ¿Estas seguro que deseas eliminar este registro?
            </div>

            <div class="modal-footer bg-light pt-2 pb-2 p-4">
                <div class="row g-3 col-md-6 m-0">
                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="button" class="btn btn-default col-md-12 font-14" data-bs-dismiss="modal">CANCELAR</button>
                    </div>

                    <div class="form-group mb-2 mt-2 col-md-6">
                        <button type="submit" class="btn btn-danger col-md-12 font-14">ACEPTAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body pt-5 pb-5 p-4 fase" id="segunda_fase" style="display: none;">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="nombres" class="form-label">Nombres <span>*</span></label>
            <input type="text" class="form-control" id="nombres" name="nombres" required />
        </div>

        <div class="col-md-6">
            <label for="apellidos" class="form-label">Apellidos <span>*</span></label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required />
        </div>

        <div class="col-md-6">
            <label for="edad" class="form-label">Edad <span>*</span></label>
            <input type="number" class="form-control" id="edad" name="edad" required />
        </div>

        <div class="col-md-6">
            <label for="nacionalidad" class="form-label">Nacionalidad <span>*</span></label>
            <select class="form-select" id="nacionalidad" name="nacionalidad" required>
                <option value="">Seleccionar</option>
                @foreach($countries as $countrie)
                    <option value="{{ $countrie->iso }}">{{ $countrie->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="documento" class="form-label">Número de documento <span>*</span></label>
            <input type="number" class="form-control" id="documento" name="documento" required />
        </div>

        <div class="col-md-6">
            <label for="celular" class="form-label">Celular <span>*</span></label>
            <input type="number" class="form-control" id="celular" name="celular" required />
        </div>

        <div class="col-md-6">
            <label for="sexo" class="form-label">Sexo <span>*</span></label>
            <select class="form-select" id="sexo" name="sexo" required>
                <option value="">Seleccionar</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email <span>*</span></label>
            <input type="email" class="form-control" id="email" name="email" required />
        </div>

        <div class="col-md-12">
            <label for="alergias" class="form-label">Alergias</label>
            <select class="form-select" id="alergias" name="alergias[]" multiple data-placeholder="Seleccionar">
                @foreach($alergias as $alergia)
                    <option value="{{ $alergia->id }}">{{ $alergia->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label for="alimentacion" class="form-label">Tipo alimentación</label>
            <select class="form-select" id="alimentacion" name="alimentacion[]" multiple data-placeholder="Seleccionar">
                @foreach($alimentos as $alimento)
                    <option value="{{ $alimento->id }}">{{ $alimento->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <label for="nota" class="form-label">Nota adicional</label>
            <input type="text" class="form-control" id="nota" name="nota" />
        </div>

        <div class="col-md-12">
            <label for="file-upload" class="form-label">
                Es importante subir una imagen o PDF del documento de identidad para su seguridad y la nuestra. <strong>(campo requerido *)</strong>
            </label>
            <input class="form-control form-control-solid" id="file-upload" name="file" type="file" accept=".pdf, .doc, .docx, image/*" required />

            <label for="file-upload" id="file-drag">
                <img id="file-image" src="#" alt="Preview" class="hidden">
                <iframe id="pdf-preview" style="display: none;" class="hidden" width="100%" height="500px"></iframe>

                <div id="start">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <div id="pdf-upload">Selecciona el archivo a cargar</div>
                    <div id="notimage" class="hidden">Selecciona una imagen</div>
                    <span id="file-upload-btn" class="btn btn-primary">Selecciona un archivo</span>
                </div>

                <div id="response" class="hidden">
                    <div id="messages"></div>

                    <progress class="progress" id="file-progress" value="0">
                        <span>0</span>%
                    </progress>
                </div>
            </label>
        </div>

        <div class="col-md-12">
            <div class="d-flex justify-content-center gap-2">
                <a href="javascript:regresar2();" class="btn btn-danger regresar2 col-md-6" data-prev="primera_fase">
                    <i class="fadeIn animated bx bx-arrow-to-left"></i>Regresar
                </a>
                <a href="javascript:continuar2();" class="btn btn-primary continuar2 col-md-6" data-next="tercera_fase">
                    Continuar <i class="fadeIn animated bx bx-arrow-to-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@extends('layouts.tienda')

@section('template_title')
    Editar reserva
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
        max-width: 600px;

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
    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-5">
            <form action="{{ route('reservas.update', $reserva->id) }}" class="uploader" method="POST" id="file-upload-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" value="file_email" id="pagina" name="pagina" />

                <div class="card">
                    <div class="card border-primary mb-0">
                        <div class="card-body pt-5 pb-5 p-4 fase" id="cuarta_fase">
                            <dl class="row">
                                <dt class="col-sm-12">Opciones de pago / Payment options</dt>
                            </dl>

                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#credito" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">Tarjeta de crédito</div>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#transferencia" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">Transferencia bancaria</div>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#qr" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title">QR bancario</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="credito" role="tabpanel">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    @foreach($links as $link)
                                                        @if($link->estatus == "1")
                                                            <tr>
                                                                <td>{{ $link->nombre }}</td>
                                                                <td>{{ $link->descripcion }}</td>
                                                                <td>
                                                                    <a href="{{ $link->url }}" target="_BLANK" class="btn btn-primary btn-sm radius-30 px-4 col-md-12">
                                                                        Pagar ahora
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="transferencia" role="tabpanel">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    @foreach($onlines as $online)
                                                        @if($online->estatus == "1")
                                                            <tr>
                                                                <td>{{ $online->nombre }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="qr" role="tabpanel">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    @foreach($qrs as $qr)
                                                        @if($qr->estatus == "1")
                                                            <tr>
                                                                <td>
                                                                    <img src="{{ asset('panelqrs') }}/{{ $qr->file }}" alt="" width="200" height="200">
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="alimentacion" class="form-label">Es importante subir una imagen del documento de identidad para su seguridad y la nuestra.</label>
                                    <input class="form-control form-control-solid" id="file-upload" name="file" type="file" accept=".pdf, .doc, .docx, image/*" required />

                                    <label for="file-upload" id="file-drag">
                                        <img id="file-image" src="#" alt="Preview" class="hidden">
                                        <iframe id="pdf-preview" style="display: none;" class="hidden" width="100%" height="500px"></iframe>
                                        
                                        <div id="start">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            <div>Select a file or drag here</div>
                                            <div id="notimage" class="hidden">Please select an image</div>
                                            <span id="file-upload-btn" class="btn btn-primary">Select a file</span>
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
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="submit" class="btn btn-primary continuar col-md-12">Enviar <i class="fadeIn animated bx bx-arrow-to-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card border-primary mb-0">
                    <div class="card-body p-4">
                        <dl class="row col-md-12 m-0">
							<dt class="col-sm-5 m-0">Total</dt>
							<dd class="col-sm-7 text-right m-0">
                                {{ 'Bs. '.number_format($reserva->subtotal, 2, '.', '') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        // File Upload
        // 
        function ekUpload(){
            function Init() {

                console.log("Upload Initialised");

                var fileSelect    = document.getElementById('file-upload'),
                    fileDrag      = document.getElementById('file-drag'),
                    submitButton  = document.getElementById('submit-button');

                fileSelect.addEventListener('change', fileSelectHandler, false);

                // Is XHR2 available?
                var xhr = new XMLHttpRequest();
                if (xhr.upload) {
                // File Drop
                fileDrag.addEventListener('dragover', fileDragHover, false);
                fileDrag.addEventListener('dragleave', fileDragHover, false);
                fileDrag.addEventListener('drop', fileSelectHandler, false);
                }
            }

            function fileDragHover(e) {
                var fileDrag = document.getElementById('file-drag');

                e.stopPropagation();
                e.preventDefault();

                fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
            }

            function fileSelectHandler(e) {
                // Fetch FileList object
                var files = e.target.files || e.dataTransfer.files;

                // Cancel event and hover styling
                fileDragHover(e);

                // Process all File objects
                for (var i = 0, f; f = files[i]; i++) {
                parseFile(f);
                uploadFile(f);
                }
            }

            // Output
            function output(msg) {
                // Response
                var m = document.getElementById('messages');
                m.innerHTML = msg;
            }

            function parseFile(file) {
                console.log(file.name);
                output('<strong>' + encodeURI(file.name) + '</strong>');

                var fileName = file.name.toLowerCase();
                var isImage = /\.(gif|jpg|png|jpeg|pdf|doc|docx)$/i.test(fileName);
                var isPDF = /\.pdf$/i.test(fileName);

                if (isImage) {
                    document.getElementById('start').classList.add("hidden");
                    document.getElementById('response').classList.remove("hidden");
                    document.getElementById('notimage').classList.add("hidden");

                    // Mostrar imagen
                    document.getElementById('file-image').classList.remove("hidden");
                    document.getElementById('file-image').src = URL.createObjectURL(file);
                    document.getElementById('pdf-preview').classList.add("hidden");

                } else if (isPDF) {
                    document.getElementById('start').classList.add("hidden");
                    document.getElementById('response').classList.remove("hidden");
                    document.getElementById('notimage').classList.add("hidden");

                    // Mostrar PDF en iframe
                    document.getElementById('pdf-preview').classList.remove("hidden");
                    document.getElementById('pdf-preview').src = URL.createObjectURL(file);
                    document.getElementById('file-image').classList.add("hidden");

                } else {
                    document.getElementById('file-image').classList.add("hidden");
                    document.getElementById('pdf-preview').classList.add("hidden");
                    document.getElementById('notimage').classList.remove("hidden");
                    document.getElementById('start').classList.remove("hidden");
                    document.getElementById('response').classList.add("hidden");
                    document.getElementById("file-upload-form").reset();
                }
            }

            function setProgressMaxValue(e) {
                var pBar = document.getElementById('file-progress');

                if (e.lengthComputable) {
                pBar.max = e.total;
                }
            }

            function updateFileProgress(e) {
                var pBar = document.getElementById('file-progress');

                if (e.lengthComputable) {
                pBar.value = e.loaded;
                }
            }

            function uploadFile(file) {

                var xhr = new XMLHttpRequest(),
                fileInput = document.getElementById('class-roster-file'),
                pBar = document.getElementById('file-progress'),
                fileSizeLimit = 1024; // In MB
                if (xhr.upload) {
                // Check if file is less than x MB
                if (file.size <= fileSizeLimit * 1024 * 1024) {
                    // Progress bar
                    pBar.style.display = 'inline';
                    xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
                    xhr.upload.addEventListener('progress', updateFileProgress, false);

                    // File received / failed
                    xhr.onreadystatechange = function(e) {
                    if (xhr.readyState == 4) {
                        // Everything is good!

                        // progress.className = (xhr.status == 200 ? "success" : "failure");
                        // document.location.reload(true);
                    }
                    };

                    // Start upload
                    xhr.open('POST', document.getElementById('file-upload-form').action, true);
                    xhr.setRequestHeader('X-File-Name', file.name);
                    xhr.setRequestHeader('X-File-Size', file.size);
                    xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                    xhr.send(file);
                } else {
                    output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
                }
                }
            }

            // Check for the various File API support.
            if (window.File && window.FileList && window.FileReader) {
                Init();
            } else {
                document.getElementById('file-drag').style.display = 'none';
            }
        }
        ekUpload();
    </script>
@endsection
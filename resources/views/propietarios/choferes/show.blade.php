@extends('layouts.app')

@section('template_title')
    Ver chofer
@endsection

@section('estilos')
    <style>
        
    </style>
@endsection

@section('content')
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('prochoferes.update', $chofer->id) }}" class="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="d-flex flex-column align-items-center text-center">
                                @if($chofer->file)
                                    <img src="{{ asset('panelchoferes/'.$chofer->file) }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="200">
                                @else
                                    <img src="{{ asset('assets/imagenes/img_default.jpg') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="200">
                                @endif

                                <div class="mt-3">
                                    <h5 class="title_dir">{{ $chofer->nombre }}</h5>
                                    <p class="text-secondary mb-1">{{ $chofer->apellido }}</p>
                                    <p class="text-muted font-size-sm"></p>

                                    <div class="row g-3 pt-3 pb-2 col-md-12">
                                        <div class="form-group mb-2 mt-2 col-md-8">
                                            <input type="file" id="file" name="file" required class="form-control form-control-solid">
                                            <input id="foto" name="foto" type="hidden" value="2" />
                                        </div>
                                        
                                        <div class="form-group mb-2 mt-2 col-md-4">
                                            <button type="submit" class="col-md-12 btn btn-primary">Guardar foto</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-30 text-white">
                                <i class="bx bxs-check-circle"></i>
                            </div>

                            <div class="ms-3">
                                <h6 class="mb-0 text-white font-14">Configuración - {{ $message }}</h6>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-30 text-white">
                                <i class="bx bxs-check-circle"></i>
                            </div>

                            <div class="ms-3">
                                <h6 class="mb-0 text-white font-14">Configuración - {{ $message }}</h6>
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('prochoferes.update', $chofer->id) }}" class="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3 pt-3 pb-2 col-md-12">
                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Nombre</label>
                                    <input class="form-control form-control-solid" id="nombre" name="nombre" type="text" required value="{{ $chofer->nombre }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Apellido</label>
                                    <input class="form-control form-control-solid" id="apellido" name="apellido" type="text" required value="{{ $chofer->apellido }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Cédula de identidad</label>
                                    <input class="form-control form-control-solid" id="cedula" name="cedula" type="number" required value="{{ $chofer->cedula }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Licencia de conducir</label>
                                    <input class="form-control form-control-solid" id="licencia" name="licencia" type="text" required value="{{ $chofer->licencia }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Correo electrónico</label>
                                    <input class="form-control form-control-solid" id="correo" name="correo" type="email" required value="{{ $chofer->correo }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Celular</label>
                                    <input class="form-control form-control-solid" id="celular" name="celular" type="number" required value="{{ $chofer->celular }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Número de cuenta</label>
                                    <input class="form-control form-control-solid" id="cuenta" name="cuenta" type="number" required value="{{ $chofer->cuenta }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Banco</label>
                                    <select class="form-control form-control-solid" id="bancos_id" name="bancos_id" type="select" required>
                                        @if($chofer->bancos_id)
                                            <option value="{{ $chofer->banco->id }}">{{ $chofer->banco->titulo }}</option>
                                        @endif
                                        <option value="">Seleccionar</option>
                                        @foreach($bancos as $banco)
                                            <option value="{{ $banco->id }}">{{ $banco->titulo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Referencia</label>
                                    <input class="form-control form-control-solid" id="referencia" name="referencia" type="text" value="{{ $chofer->referencia }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Celular de referencia</label>
                                    <input class="form-control form-control-solid" id="celref" name="celref" type="number" value="{{ $chofer->celref }}" />
                                </div>

                                <div class="form-group mb-2 mt-2 col-md-6">
                                    <label class="mb-2">Observaciones</label>
                                    <input class="form-control form-control-solid" id="observaciones" name="observaciones" type="text" value="{{ $chofer->observaciones }}" />
                                </div>

                                <input id="estatus" name="estatus" type="hidden" value="{{ $chofer->estatus }}" />
                                <input id="foto" name="foto" type="hidden" value="1" />
                            
                                <div class="form-group mb-2 mt-2 col-md-12">
                                    <button type="submit" class="btn btn-success col-md-12 font-14">ACTUALIZAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>		
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    
@endsection
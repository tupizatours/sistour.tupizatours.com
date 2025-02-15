@extends('layouts.app')

@section('template_title')
    Guias
@endsection

@section('estilos')
    <style>
        
    </style>
@endsection

@section('content')
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
        <div class="card-header bg-primary pt-3 pb-3">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 title_page text-white">LISTADO DE GUIAS</h6>
                </div>

                
                <div class="ms-auto">
                    <div class="d-flex">
                        <a href="{{ URL::to('prestatario/guias/eliminados') }}" class="btn btn-dark mt-2 mt-lg-0 font-13 btn">
                            ELIMINADOS
                        </a>
                    
                        <form action="{{ route('proguias.store') }}" class="ms-1" method="POST" enctype="multipart/form-data">
                            @csrf

                            <button type="button" class="btn btn-primary mt-2 mt-lg-0 font-13 btn" data-bs-toggle="modal" data-bs-target="#ModalCreate">
                                <i class="bx bxs-plus-square"></i>NUEVA GUIA
                            </button>

                            @include('propietarios.guias.create')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table">
                    <thead class="">
                        <tr>
                            <th>Nombres</th>
                            <th>Tipo</th>
                            <th>Tarjeta de acreditación</th>
                            <th>Celular</th>
                            <th>Idiomas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($guias as $guia)
                            @if($guia->estatus == "1")
                                @php
                                    $originalDate = $guia->created_at;
                                    $newDate = date("d/m/Y", strtotime($originalDate));
                                @endphp

                                <tr>
                                    <td>
                                        <a href="{{ URL::to('prestatario/guias/' . $guia->id) }}">
                                            {{ $guia->nombre.' '.$guia->apellido }}
                                        </a>
                                    </td>

                                    <td>{{ $guia->tipo }}</td>
                                    <td>{{ $guia->acreditacion }}</td>
                                    <td>{{ $guia->celular }}</td>

                                    <td>
                                        @php
                                            $idiomas_id = json_decode($guia->idiomas_id);
                                        @endphp

                                        @foreach($idiomas_id as $key => $value)
                                            @foreach($idiomas as $idioma)
                                                @if($value == $idioma->id)
                                                    <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                        {{ $idioma->titulo }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex order-actions">
                                            <form action="{{ route('proguias.update', $guia->id) }}" class="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
        
                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $guia->id }}">
                                                    <i class="bx bxs-edit"></i>
                                                </button>

                                                @include('propietarios.guias.edit')
                                            </form>

                                            <form action="{{ route('estatus.update', $guia->id) }}" class="ms-1" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $guia->id }}">
                                                    <i class="bx bxs-trash"></i>
                                                </button>

                                                <input type="hidden" value="2" id="estatus" name="estatus" />
                                                <input type="hidden" value="guias" id="pagina" name="pagina" />

                                                @include('propietarios.guias.predelete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>

    <script>
        $(document).ready(function() {
            $.fn.dataTable.moment('DD/MM/YYYY');
            
            var table = $('#example2').DataTable( {
				lengthChange: true,
                order: [0, 'desc'],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );

        $(document).ready(function () {
            // Aplicar Select2 a los selectores ya generados
            $('.idiomas').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: 'Seleccionar',
                closeOnSelect: false,
            });
        });
    </script>
@endsection
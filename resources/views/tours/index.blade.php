@extends('layouts.app')

@section('template_title')
    Tours
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
                    <h6 class="mb-0 text-white font-14">{{ $message }}</h6>
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
                    <h6 class="mb-0 text-white font-14">{{ $message }}</h6>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary pt-3 pb-3">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 title_page text-white">LISTADO DE TOURS</h6>
                </div>

                <div class="ms-auto">
                    <div class="d-flex">
                        <a href="{{ URL::to('tours/eliminados') }}" class="btn btn-dark mt-2 mt-lg-0 font-13 btn">
                            ELIMINADOS
                        </a>
                    
                        <form action="{{ route('tours.store') }}" class="ms-1" method="POST" enctype="multipart/form-data">
                            @csrf

                            <button type="button" class="btn btn-primary mt-2 mt-lg-0 font-13 btn" data-bs-toggle="modal" data-bs-target="#ModalCreate">
                                <i class="bx bxs-plus-square"></i>NUEVO TOUR
                            </button>

                            @include('tours.create')
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
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Duración</th>
                            <th>Precio unitario</th>
                            <th>Precio total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tours as $tour)
                            @if($tour->estatus == "1")
                                @php
                                    $originalDate = $tour->created_at;
                                    $newDate = date("d/m/Y", strtotime($originalDate));
                                @endphp

                                <tr>
                                    <td>{{ $tour->titulo }}</td>
                                    <td>{{ $tour->descripcion }}</td>
                                    <td>{{ $tour->categoria->titulo }}</td>
                                    <td>{{ $tour->duracion.' dias' }}</td>
                                    <td>{{ 'Bs. '.number_format($tour->pre_uni, 2, '.', '') }}</td>
                                    <td>{{ 'Bs. '.number_format($tour->pre_tot, 2, '.', '') }}</td>
                                    
                                    <td>
                                        <div class="d-flex order-actions">
                                            <form action="{{ route('tours.update', $tour->id) }}" class="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
        
                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $tour->id }}">
                                                    <i class="bx bxs-edit"></i>
                                                </button>

                                                @include('tours.edit')
                                            </form>

                                            <form action="{{ route('estatus.update', $tour->id) }}" class="ms-1" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $tour->id }}">
                                                    <i class="bx bxs-trash"></i>
                                                </button>

                                                <input type="hidden" value="2" id="estatus" name="estatus" />
                                                <input type="hidden" value="tours" id="pagina" name="pagina" />

                                                @include('tours.predelete')
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
    </script>

    <script>
        $(document).ready(function () {
            // Aplicar Select2 a los selectores ya generados
            $('.hotel-select').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: 'Seleccionar',
                closeOnSelect: false,
            });
            
            // Evento cuando cambie el tipo de tour
            $('#tipo').on('change', function () {
                let tipoSeleccionado = $(this).val();

                if (tipoSeleccionado) {
                    // Petición AJAX para obtener los hoteles del tipo seleccionado
                    $.ajax({
                        url: "{{ url('tours/filtrar') }}",
                        type: 'GET',
                        data: { tipo: tipoSeleccionado },
                        success: function (data) {
                            // Limpiar los select de hoteles existentes
                            $('.hotel-select').empty();

                            // Añadir las opciones de hoteles filtrados
                            data.forEach(function (hotel) {
                                $('.hotel-select').append(
                                    `<option value="${hotel.id}">${hotel.titulo}</option>`
                                );
                            });
                            
                            // Reinicializar Select2 en los select actuales
                            $('.hotel-select').select2({
                                theme: "bootstrap-5",
                                width: '100%', // Aseguramos que sea 100% del contenedor
                                placeholder: 'Seleccionar',
                                closeOnSelect: false,
                            });
                        },
                        error: function () {
                            alert('Error al obtener los hoteles.');
                        },
                    });
                }
            });

            // Evento para generar los campos de hoteles según la cantidad de noches
            $('#noches').on('input', function () {
                let cantidadNoches = $(this).val();
                let hotelesContainer = $('#hoteles_cont');

                hotelesContainer.empty(); // Limpiar campos previos

                // Generar select dinámico para cada noche
                for (let i = 1; i <= cantidadNoches; i++) {
                    hotelesContainer.append(`
                        <div class="form-group mb-2 mt-2 col-md-6">
                            <label for="hoteles_${i}">Hotel día ${i}</label>
                            <select class="form-select hotel-select" name="hoteles[${i}][]" required data-placeholder="Seleccionar" multiple>
                            </select>

                            <input type="hidden" value="Día ${i}" name="dias[]" />
                        </div>
                    `);
                }

                // Aplicar Select2 a los nuevos selectores dinámicos
                $('.hotel-select').select2({
                    theme: "bootstrap-5",
                    width: '100%', // Aseguramos que sea 100% del contenedor
                    placeholder: 'Seleccionar',
                    closeOnSelect: false,
                });
            });
        });
    </script>
@endsection
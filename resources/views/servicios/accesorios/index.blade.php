@extends('layouts.app')

@section('template_title')
    Accesorios
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
                    <h6 class="mb-0 text-white font-14">Servicios - {{ $message }}</h6>
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
                    <h6 class="mb-0 text-white font-14">Servicios - {{ $message }}</h6>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary pt-3 pb-3">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 title_page text-white">LISTADO DE ACCESORIOS</h6>
                </div>

                
                <div class="ms-auto">
                    <div class="d-flex">
                        <a href="{{ URL::to('service/accesorios/eliminados') }}" class="btn btn-dark mt-2 mt-lg-0 font-13 btn">
                            ELIMINADOS
                        </a>
                    
                        <form action="{{ route('servaccesorios.store') }}" class="ms-1" method="POST" enctype="multipart/form-data">
                            @csrf

                            <button type="button" class="btn btn-primary mt-2 mt-lg-0 font-13 btn" data-bs-toggle="modal" data-bs-target="#ModalCreate">
                                <i class="bx bxs-plus-square"></i>NUEVO ACCESORIO
                            </button>

                            @include('servicios.accesorios.create')
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
                            <th>Precio de costo</th>
                            <th>Precio de venta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($accesorios as $accesorio)
                            @if($accesorio->estatus == "1")
                                @php
                                    $originalDate = $accesorio->created_at;
                                    $newDate = date("d/m/Y", strtotime($originalDate));
                                @endphp

                                <tr>
                                    <td>{{ $accesorio->titulo }}</td>
                                    <td>{{ 'Bs. '.number_format($accesorio->costo, 2, '.', '') }}</td>
                                    <td>{{ 'Bs. '.number_format($accesorio->venta, 2, '.', '') }}</td>
                                    
                                    <td>
                                        <div class="d-flex order-actions">
                                            <form action="{{ route('servaccesorios.update', $accesorio->id) }}" class="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
        
                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $accesorio->id }}">
                                                    <i class="bx bxs-edit"></i>
                                                </button>

                                                @include('servicios.accesorios.edit')
                                            </form>

                                            <form action="{{ route('estatus.update', $accesorio->id) }}" class="ms-1" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $accesorio->id }}">
                                                    <i class="bx bxs-trash"></i>
                                                </button>

                                                <input type="hidden" value="2" id="estatus" name="estatus" />
                                                <input type="hidden" value="accesorios" id="pagina" name="pagina" />

                                                @include('servicios.accesorios.predelete')
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
                order: [3, 'desc'],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
    </script>
@endsection
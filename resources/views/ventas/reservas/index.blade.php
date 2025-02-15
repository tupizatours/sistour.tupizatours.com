@extends('layouts.app')

@section('template_title')
    Reservas
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
                    <h6 class="mb-0 text-white font-14">Ventas - {{ $message }}</h6>
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
                    <h6 class="mb-0 text-white font-14">Ventas - {{ $message }}</h6>
                </div>
            </div>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary pt-3 pb-3">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 title_page text-white">LISTADO DE RESERVAS</h6>

                </div>
                
                <div class="ms-auto">
                    <div class="d-flex">
                        <form action="{{ url('ventas/reservas/create') }}" class="" method="GET" enctype="multipart/form-data">
                            <button type="button" class="btn btn-primary mt-2 mt-lg-0 font-13 btn" data-bs-toggle="modal" data-bs-target="#ModalAdd">
                                <i class="bx bxs-plus-square"></i>CREAR RESERVA
                            </button>

                            @include('ventas.reservas.add')
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
                            <th>Nombre del tour</th>
                            <th>Cantidad de turistas</th>
                            <th>Fecha</th>
                            <th>Código del tour creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($reservas as $reserva)
                            @if($reserva->estatus == "1" && $reserva->estado == "2")
                                @php
                                    $originalDate = $reserva->created_at;
                                    $newDate = date("Y-m-d", strtotime($originalDate));
                                @endphp

                                <tr>
                                    <td>{{ $reserva->tour->titulo }}</td>
                                    <td>{{ $reserva->can_per.' turistas' }}</td>
                                    <td>{{ $reserva->fecha }}</td>
                                    <td class="text-uppercase">{{ $reserva->codigo }}</td>
                                    
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="{{ URL::to('ventas/reservas/' . $reserva->id . '/edit') }}" class="">
                                                <i class="bx bx-comment-add"></i>
                                            </a>

                                            <a href="{{ URL::to('ventas/reservas/' . $reserva->id) }}" class="ms-1">
                                                <i class="bx bxs-edit"></i>
                                            </a>
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
@endsection
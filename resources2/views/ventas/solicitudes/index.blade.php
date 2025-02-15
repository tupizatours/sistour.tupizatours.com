@extends('layouts.app')

@section('template_title')
    Solicitudes
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
                    <h6 class="mb-0 title_page text-white">LISTADO DE SOLICITUDES</h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table">
                    <thead class="">
                        <tr>
                            <th>Codigo</th>
                            <th>Tour</th>
                            <th>Fecha de Salida</th>
                            <th>Nombres y apellidos</th>
                            <th>País</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                            <th>Personas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($reservas as $reserva)
                            @if($reserva->estatus == "1" && $reserva->estado == "1")
                                @php
                                    $originalDate = $reserva->created_at;
                                    $newDate = date("Y-m-d", strtotime($originalDate));
                                @endphp

                                @if($reserva->turistas->first()->esPrincipal == "1")
                                    <tr>
                                        <td>{{ $reserva->codigo }}</td>
                                        <td>{{ $reserva->tour->titulo }}</td>
                                        <td>{{ $reserva->fecha }}</td>

                                        <td>
                                            {{ $reserva->turistas->first()->nombres.' '.$reserva->turistas->first()->apellidos }}
                                        </td>

                                        <td>{{ $reserva->turistas->first()->nacionalidad }}</td>
                                        <td>{{ $reserva->turistas->first()->edad }}</td>
                                        <td>{{ $reserva->turistas->first()->sexo }}</td>
                                        
                                        <td>
                                            @if($reserva->can_per == 1)
                                                {{ $reserva->can_per.' persona' }}
                                            @else
                                                {{ $reserva->can_per.' personas' }}
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex order-actions">
                                                <a href="{{ URL::to('ventas/solicitudes/' . $reserva->id . '/edit') }}" class="">
                                                    <i class="bx bxs-edit"></i>
                                                </a>

                                                <form action="{{ route('estatus.update', $reserva->id) }}" class="ms-1" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $reserva->id }}">
                                                        <i class="bx bxs-trash"></i>
                                                    </button>

                                                    <input type="hidden" value="2" id="estatus" name="estatus" />
                                                    <input type="hidden" value="solicitudes" id="pagina" name="pagina" />

                                                    @include('ventas.solicitudes.predelete')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
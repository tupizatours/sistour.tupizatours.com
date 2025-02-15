@extends('layouts.app')

@section('template_title')
    Tickets eliminados
@endsection

@section('estilos')
    <style>
        
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-danger pt-3 pb-3">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 title_page text-white">LISTADO DE TICKETS ELIMINADOS</h6>
                </div>

                <div class="ms-auto">
                    <a href="{{ URL::to('service/tickets') }}" class="btn btn-dark mt-2 mt-lg-0 font-13 btn">
                        <i class="bx bx-arrow-back"></i>REGRESAR
                    </a>
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
                            <th>Precio de costo extranjeros</th>
                            <th>Precio nacionales</th>
                            <th>Precio extranjeros</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tickets as $ticket)
                            @if($ticket->estatus == "2")
                                @php
                                    $originalDate = $ticket->created_at;
                                    $newDate = date("d/m/Y", strtotime($originalDate));
                                @endphp

                                <tr>
                                    <td>{{ $ticket->titulo }}</td>
                                    <td>{{ 'Bs. '.number_format($ticket->costo, 2, '.', '') }}</td>
                                    <td>{{ 'Bs. '.number_format($ticket->cos_ext, 2, '.', '') }}</td>
                                    <td>{{ 'Bs. '.number_format($ticket->nacionales, 2, '.', '') }}</td>
                                    <td>{{ 'Bs. '.number_format($ticket->extranjeros, 2, '.', '') }}</td>
                                    
                                    <td>
                                        <div class="d-flex order-actions">
                                            <form action="{{ route('estatus.update', $ticket->id) }}" class="ms-1" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalRestaurar{{ $ticket->id }}">
                                                    <i class="bx bx-refresh"></i>
                                                </button>

                                                <input type="hidden" value="1" id="estatus" name="estatus" />
                                                <input type="hidden" value="tickets" id="pagina" name="pagina" />

                                                @include('servicios.tickets.restaurar')
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
                order: [6, 'desc'],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
    </script>
@endsection
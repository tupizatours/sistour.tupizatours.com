@extends('layouts.app')

@section('template_title')
    Usuarios
@endsection

@section('estilos')
    
@endsection

@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
                    <h6 class="mb-0 title_page text-white">LISTA DE USUARIOS</h6>
                </div>

                <div class="ms-auto">
                    <form action="{{ route('users.store') }}" class="ms-1" method="POST">
                        @csrf

                        <button type="button" class="btn btn-primary mt-2 mt-lg-0 font-13 btn" data-bs-toggle="modal" data-bs-target="#ModalCreate">
                            <i class="bx bxs-plus-square"></i>CREAR USUARIO
                        </button>

                        @include('usersmanagement.create')
                    </form>
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Correo</th>
                            <th>Nombres</th>
                            <th>Tipo de usuario</th>
                            <th>Registrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                            @php
                                $originalDate = $user->created_at;
                                $newDate = date("d/m/Y", strtotime($originalDate));
                            @endphp

                            <tr>
                                <td>#00{{ $user->id }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->first_name.' '.$user->last_name }}</td>

                                <td>
                                    @foreach ($user->roles as $user_role)
                                        @if ($user_role->name == 'User')
                                            @php $badgeClass = 'primary'; $badgeName = 'Usuario'; @endphp
                                        @elseif ($user_role->name == 'Admin')
                                            @php $badgeClass = 'success'; $badgeName = 'Administrador'; @endphp
                                        @elseif ($user_role->name == 'Profesor')
                                            @php $badgeClass = 'danger'; $badgeName = 'Profesor'; @endphp
                                        @else
                                            @php $badgeClass = 'default'; $badgeName = 'Usuario'; @endphp
                                        @endif

                                        <div class="badge rounded-pill text-{{ $badgeClass }} bg-light-{{ $badgeClass }} p-2 text-uppercase px-3">
                                            <i class="bx bxs-circle me-1"></i>{{ $badgeName }}
                                        </div>
                                    @endforeach
                                </td>

                                <td>{{ $newDate }}</td>
                                
                                <td>
                                    <div class="d-flex order-actions">
                                        <form action="{{ route('users.update', $user->id) }}" class="ms-1" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
    
                                            <button type="button" class="btn boton-eliminar ms-1" data-bs-toggle="modal" data-bs-target="#ModalEdit{{ $user->id }}">
                                                <i class="bx bxs-edit"></i>
                                            </button>

                                            @include('usersmanagement.edit')
                                        </form>
                                    </div>
                                </td>
                            </tr>
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
                order: [4, 'desc'],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
    </script>
@endsection

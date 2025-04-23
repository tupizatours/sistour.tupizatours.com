@extends('layouts.app')

@section('template_title')
    Ver reserva
@endsection

@section('estilos')
    <style>
        h4.title_dir {
            font-size: 18px;
            font-weight: 700;
            color: rgb(63, 66, 87);
        }
        .bg-moradito {
            border: 1px dashed rgb(98, 95, 241);
        }
        .card-infos, dt {
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .form_cantidad {
            max-width: 60px;
        }
        .input-spinner .btn-white {
            width: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="main-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-transparent pt-3 pb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 title_page text-uppercase">
                                    <b>{{ 'RESERVA: '.$reserva->tour->titulo }}</b>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <?php
                        use App\Models\Venta\Pago;
                    ?>
                    
                    <div class="card-body">
                        <h6 class="card-title mb-4 text-uppercase">
                            <b>Salida del tour: {{\Carbon\Carbon::parse($reserva->fecha)->format('d-m-Y')}}</b>
                        </h6>

                        <div class="row mt-4">
                            <dl class="col-md-2">
                                <dt class="col-sm-12">Código de reserva</dt>
                                <dd class="col-sm-12">{{ $reserva->codigo }}</dd>
                            </dl>

                            <dl class="col-md-2">
                                <dt class="col-sm-12">Nombre del tour</dt>
                                <dd class="col-sm-12">{{ $reserva->tour->titulo }}</dd>
                            </dl>

                            <dl class="col-md-2">
                                <dt class="col-sm-12">Capacidad Min/Max</dt>
                                <dd class="col-sm-12">
                                    {{ $reserva->tour->min_per.'/' }}
                                    <span id="aumen_pers">{{ $reserva->can_pri }}</span>
                                </dd>
                            </dl>

                            <!-- Total de la reserva -->
                            <dl class="col-md-2">
                                <dt class="col-sm-12">Total Reserva</dt>
                                <dd class="col-sm-12" id="totalReserva" data-total="{{ $reserva->total }}">
                                    {{ 'Bs. '.number_format($reserva->total, 2, '.', ',') }}
                                </dd>
                            </dl>

                            <!-- Total de la pagado -->
                            <dl class="col-md-2">
                                <dt class="col-sm-12">Total Pagado</dt>
                                @php $tot_dir = 0; @endphp
                                @foreach($resclis as $rescli)
                                    @if($rescli->estatus == "1")
                                        @php
                                            $sumaMonto = Pago::where('rescli_id', $rescli->id)
                                                            ->where('estatus', 1)
                                                            ->sum('conversion');
                                            $tot_dir += $sumaMonto;
                                        @endphp
                                    @endif
                                @endforeach
                                <dd class="col-sm-12" id="totalPagado" data-pagado="{{ $tot_dir }}">
                                    {{ 'Bs. '.number_format($tot_dir, 2, '.', ',') }}
                                </dd>
                            </dl>

                            <dl class="col-md-2">
                                <form action="{{ route('venreservas.update', $reserva->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                
                                    <dt class="col-sm-12">Capacidad</dt>
                                    <dd class="col-sm-12">
                                        <div class="input-group input-spinner">
                                            <button class="btn btn-white" type="button" id="button-minus"> - </button>
                                                <input type="text" id="cantper" name="cantper" class="form-control form_cantidad text-center" value="{{ $reserva->can_pri }}" readonly />
                                            <button class="btn btn-white" type="button" id="button-plus"> + </button>

                                            <button type="submit" class="btn btn-success">
                                                Actualizar
                                            </button>
                                        </div>
                                    </dd>

                                    <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id" />
                                    <input type="hidden" value="reservas" id="reservas" name="reservas" />
                                </form>
                            </dl>
                        </div>

                        <div class="row">
                            <dl class="col-md-2">
                                <a href="{{ URL::to('ventas/reservas/' . $reserva->id . '/edit') }}"
                                    class="btn btn-primary col-md-12"
                                        @if($reserva->can_pri <= $reserva->turistas->count()) 
                                            disabled 
                                        @endif>
                                    Agregar Turista
                                </a>
                            </dl>

                            <dl class="col-md-2">
                                <form action="{{ route('desges.store') }}" method="POST" enctype="multipart/form-data" id="form-despachar">
                                    @csrf
                                    <input type="hidden" value="{{ $reserva->id }}" id="reserva_id" name="reserva_id">
                                
                                    <button type="button" class="btn btn-success col-md-12" id="btn-despachar">Despachar</button>
                                </form>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-transparent pt-3 pb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 title_page">LISTADO DE TURISTAS</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table">
                                <thead class="">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombres y apellidos</th>
                                        <th>País</th>
                                        <th>Edad</th>
                                        <th>Sexo</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Total</th>
                                        <th>Pagado</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($resclis as $rescli)
                                        @if($rescli->estatus == "1")
                                            @php
                                                $originalDate = $rescli->created_at;
                                                $newDate = date("d-m-Y", strtotime($originalDate));
                                    
                                                // ✅ Obtener la sumatoria de pagos con estatus activo
                                                $sumaMonto = Pago::where('rescli_id', $rescli->id)
                                                                ->where('estatus', 1) // Solo pagos activos
                                                                ->sum('conversion');
                                    
                                                // ✅ Calcular el saldo pendiente asegurando que no sea negativo
                                                $saldoPendiente = max(($rescli->total - $sumaMonto), 0);
                                            @endphp
                                    
                                            <tr>
                                                <td class="text-uppercase">
                                                    @if($rescli->estado == 1)
                                                        {{ $reserva->codigo }}
                                                    @elseif($rescli->estado == 2)
                                                        {{ $rescli->codigo }}
                                                    @endif
                                                </td>
                                                
                                                <td>{{ $rescli->nombres.' '.$rescli->apellidos }}</td>
                                                <td>{{ $rescli->nacionalidad }}</td>
                                                <td>@if($rescli->edad) {{ $rescli->edad }} @endif</td>
                                                <td>{{ $rescli->sexo }}</td>
                                                <td>{{ $rescli->celular }}</td>
                                                <td>{{ $rescli->correo }}</td>
                                                
                                                <td>{{ 'Bs. '.number_format($rescli->total, 2, '.', ',') }}</td>
                                    
                                                <!-- ✅ Mostrar Pagado: Solo suma pagos activos -->
                                                <td>{{ 'Bs. '.number_format($sumaMonto, 2, '.', ',') }}</td>
                                                
                                                <!-- ✅ Mostrar Saldo: Asegurar que no sea negativo -->
                                                <td>{{ 'Bs. '.number_format($saldoPendiente, 2, '.', ',') }}</td>
                                                
                                                <td>
                                                    <div class="d-flex order-actions">
                                                        @if($rescli->esPrincipal == "1")
                                                        @else
                                                            <a href="#" 
                                                                class="copiar-enlace" 
                                                                data-link="{{ URL::to('ventas/resclis/user/' . $rescli->id) }}" 
                                                                title="Copiar enlace">
                                                                <i class="bx bxs-user"></i>
                                                            </a>
                                                        @endif
                                    
                                                        <a href="{{ URL::to('ventas/resclis/' . $rescli->id . '/edit') }}">
                                                            <i class="bx bxs-edit"></i>
                                                        </a>
                                    
                                                        <a href="{{ URL::to('ventas/resclis/' . $rescli->id) }}" class="ms-1">
                                                            <i class="fadeIn animated bx bx-dollar-circle"></i>
                                                        </a>
                                    
                                                        <form action="{{ route('estatus.update', $reserva->id) }}" class="ms-1" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                    
                                                            <button type="button" class="btn boton-eliminar" data-bs-toggle="modal" data-bs-target="#ModalPreDelete{{ $reserva->id }}">
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>	
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttonMinus = document.getElementById("button-minus");
            const buttonPlus = document.getElementById("button-plus");
            const cantPerInput = document.getElementById("cantper");
            
            // Valores mínimo (fijado en el máximo de capacidad) y el límite superior
            const minPer = {{ $reserva->tour->max_per }};
            const maxPer = 10; // Puedes ajustar este valor según tu necesidad

            buttonPlus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value);
                if (cantidad < maxPer) {
                    cantidad++;
                    cantPerInput.value = cantidad;
                }
            });

            buttonMinus.addEventListener("click", function() {
                let cantidad = parseInt(cantPerInput.value);
                if (cantidad > minPer) {
                    cantidad--;
                    cantPerInput.value = cantidad;
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.copiar-enlace').forEach(function (enlace) {
                enlace.addEventListener('click', function (e) {
                    e.preventDefault(); // Evita navegación
        
                    const url = this.getAttribute('data-link');
        
                    if (!navigator.clipboard) {
                        alert("Tu navegador no soporta copiar automáticamente.");
                        return;
                    }
        
                    navigator.clipboard.writeText(url).then(() => {
                        alert("🔗 Enlace copiado al portapapeles:\n" + url);
                    }).catch(err => {
                        console.error("Error al copiar:", err);
                        alert("❌ Ocurrió un error al copiar el enlace.");
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btnDespachar = document.getElementById('btn-despachar');
            const form = document.getElementById('form-despachar');

            btnDespachar.addEventListener('click', function () {
                const totalPagado = parseFloat(document.getElementById('totalPagado').dataset.pagado || 0);
                const totalReserva = parseFloat(document.getElementById('totalReserva').dataset.total || 0);

                if (Math.abs(totalPagado - totalReserva) < 0.01) {
                    // Confirmación opcional
                    if (confirm("¿Deseas despachar esta reserva?")) {
                        form.submit();
                    }
                } else {
                    alert('⚠️ El total pagado no coincide con el total de la reserva. No se puede despachar.');
                }
            });
        });
    </script>  
@endsection
<ul class="metismenu" id="menu">
    <li class="{{ (request()->is('/home*')) ? 'mm-active' : '' }}">
        <a href="{{ url('home') }}">
            <div class="parent-icon">
                <i class="bx bx-home-alt"></i>
            </div>

            <div class="menu-title">Escritorio</div>
        </a>
    </li>

    <li class="{{ (request()->is('ventas*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon">
                <i class="fadeIn animated bx bx-calendar-week"></i>
            </div>

            <div class="menu-title">Ventas</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('ventas/solicitudes') }}">
                    <i class="bx bx-radio-circle"></i>
                    Solicitudes
                </a>
            </li>

            <li>
                <a href="{{ url('ventas/reservas') }}">
                    <i class="bx bx-radio-circle"></i>
                    Reservas
                </a>
            </li>

            <li>
                <a href="{{ url('ventas/vips') }}">
                    <i class="bx bx-radio-circle"></i>
                    Tour VIP
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('tours*')) ? 'mm-active' : '' }} {{ (request()->is('tour*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="bx bx-map-alt"></i>
            </div>

            <div class="menu-title">Tours</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('tour/categorias') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Categorías
                </a>
            </li>

            <li>
                <a href="{{ url('tours') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Tours
                </a>
            </li>

            <li>
                <a href="{{ url('tour/vip') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Tours VIP
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('despachos*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon">
                <i class="fadeIn animated bx bx-grid-alt"></i>
            </div>

            <div class="menu-title">Despachos</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('despachos/gestiones') }}">
                    <i class="bx bx-radio-circle"></i>
                    Gestionar
                </a>
            </li>

            <li>
                <a href="{{ url('despachos/transitos') }}">
                    <i class="bx bx-radio-circle"></i>
                    En tránsito
                </a>
            </li>

            <li>
                <a href="{{ url('despachos/finalizados') }}">
                    <i class="bx bx-radio-circle"></i>
                    Finalizado
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('clientes*')) ? 'mm-active' : '' }}">
        <a href="{{ url('clientes') }}">
            <div class="parent-icon">
                <i class="bx bx-user-circle"></i>
            </div>

            <div class="menu-title">Clientes</div>
        </a>
    </li>

    <li class="{{ (request()->is('prestatario*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="fadeIn animated bx bx-layer"></i>
            </div>

            <div class="menu-title">Prestatarios</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('prestatario/guias') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Guías
                </a>
            </li>

            <li>
                <a href="{{ url('prestatario/traductores') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Traductores
                </a>
            </li>

            <li>
                <a href="{{ url('prestatario/cocineros') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Cocineras/os
                </a>
            </li>

            <li>
                <a href="{{ url('prestatario/choferes') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Choferes
                </a>
            </li>

            <li>
                <a href="{{ url('propietarios') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Propietarios
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('servicios*')) ? 'mm-active' : '' }} {{ (request()->is('service*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="bx bx-grid-alt"></i>
            </div>

            <div class="menu-title">Servicios</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('service/tickets') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Tickets
                </a>
            </li>

            <li>
                <a href="{{ url('service/hoteles') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Hoteles
                </a>
            </li>

            <li>
                <a href="{{ url('service/accesorios') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Alquiler de accesorios
                </a>
            </li>

            <li>
                <a href="{{ url('service/turistas') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Servicios de turistas
                </a>
            </li>

            <li>
                <a href="{{ url('servicios') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Servicios de tours
                </a>
            </li>

            <li>
                <a href="{{ url('service/vagonetas') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Vagonetas
                </a>
            </li>

            <li>
                <a href="{{ url('service/caballos') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Caballos
                </a>
            </li>

            <li>
                <a href="{{ url('service/bicicletas') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Bicicletas
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('users*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="lni lni-users"></i>
            </div>

            <div class="menu-title">Equipo</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('users') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Miembros
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('caja*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="bx bxs-wallet"></i>
            </div>

            <div class="menu-title">Caja</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('caja/ingresos') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Ingresos
                </a>
            </li>

            <li>
                <a href="{{ url('caja/gastos') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Gastos
                </a>
            </li>

            <li>
                <a href="{{ url('caja/categorias') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Categorías
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ (request()->is('reportes*')) ? 'mm-active' : '' }}">
        <a href="{{ url('reportes') }}">
            <div class="parent-icon">
                <i class="bx bx-line-chart"></i>
            </div>

            <div class="menu-title">Reportes</div>
        </a>
    </li>

    <li class="{{ (request()->is('configuracion*')) ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow" aria-expanded="true">
            <div class="parent-icon">
                <i class="bx bx-cog bx-spin"></i>
            </div>

            <div class="menu-title">Configuración</div>
        </a>

        <ul>
            <li>
                <a href="{{ url('configuracion') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    General
                </a>
            </li>

            <li>
                <a href="{{ url('configuraciones/cobros') }}" aria-expanded="true">
                    <i class="bx bx-radio-circle"></i>
                    Tipo de cobros
                </a>
            </li>

            <li>
                <a href="javascript:;" class="has-arrow" aria-expanded="true">
                    <i class="bx bxs-cart"></i>
                    Ventas y reservas
                </a>

                <ul>
                    <li>
                        <a href="{{ url('configuraciones/empresas') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Empresas
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/monedas') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Monedas
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/impuestos') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Impuestos
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/bancos') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Bancos
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:;" class="has-arrow" aria-expanded="true">
                    <i class="bx bx-grid-alt"></i>
                    Configuración
                </a>

                <ul>
                    <li>
                        <a href="{{ url('configuraciones/idiomas') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Idiomas
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/alergias') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Alergias
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/alimentacion') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Tipos de alimentacion
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:;" class="has-arrow" aria-expanded="true">
                    <i class="bx bx-credit-card"></i>
                    Pagos online
                </a>

                <ul>
                    <li>
                        <a href="{{ url('configuraciones/links') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Links de pagos
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/onlines') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            Pagos en línea
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('configuraciones/qrs') }}" aria-expanded="true">
                            <i class="bx bx-radio-circle"></i>
                            QR
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
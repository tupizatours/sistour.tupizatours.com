<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@hasSection('template_title')@yield('template_title') | @endif Tupiza Tours</title>
        <meta name="description" content="">
        <meta name="author" content="Daniel Arturo Mayurí Lévano">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link href="{{ asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
        <link href="{{ asset('public/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
        <!-- loader-->
        <link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet"/>
        <script src="{{ asset('public/assets/js/pace.min.js') }}"></script>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="{{ asset('public/assets/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet">
        <!-- Theme Style CSS -->
        <link rel="stylesheet" href="{{ asset('public/assets/css/dark-theme.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/assets/css/semi-dark.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/assets/css/header-colors.css') }}"/>

        <link href="{{ asset('public/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

        @yield('estilos')
        
        @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
            <style>
                .user-avatar-nav {
                    background: url(http://i1.wp.com/c1940652.r52.cf0.rackcdn.com/51ce28d0fb4f442061000000/Screen-Shot-2013-06-28-at-5.22.23-PM.png) 50% 50% no-repeat;
                    background-size: auto 100%;
                }
            </style>
        @endif

        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        @yield('head')

        <style>
            .sidebar-wrapper .metismenu ul ul a {
                padding: 6px 15px 6px 20px;
            }
            .des_form {
                pointer-events: none;
                appearance: none;
            }
            .active .bs-stepper-circle {
                background-color: #008cff !important;
            }
            .sidebar-wrapper .metismenu .mm-active>a,
            .sidebar-wrapper .metismenu a:active,
            .sidebar-wrapper .metismenu a:focus,
            .sidebar-wrapper .metismenu a:hover {
                color: #fff !important;
                background-color: #008cff  !important;
            }
            .logo-text {
                font-size: 30px;
            }
            .modal-title, .alert h6 {
                text-transform: uppercase;
            }
            .select2-container--bootstrap-5 .select2-selection--multiple .select2-search {
                display: none;
            }
            .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
                margin-bottom: 0;
                font-size: 11px;
            }
            table td {
                vertical-align: middle;
            }
            .boton-eliminar {
                border: 1px solid #eeecec;
                background: #f1f1f1;
                padding: 0;
                width: 34px;
                height: 34px;
                text-align: center;
            }
            .boton-eliminar i {
                color: #2b2a2a;
                width: 100%;
            }
            @media (min-width: 992px) {
                .ModalPreDelete .modal-lg, .ModalPreDelete .modal-xl {
                    --bs-modal-width: 530px;
                }
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="sidebar-wrapper" data-simplebar="true">
                <div class="sidebar-header">
                    <div>
                        <img src="{{ asset('public/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                    </div>

                    <div>
                        <h5 class="logo-text">TOURS</h5>
                    </div>

                    <div class="toggle-icon ms-auto">
                        <i class='bx bx-arrow-back'></i>
                    </div>
                </div>
                
                @include('partials.nav')
            </div>
            
            @include('partials.header')
            
            <div class="page-wrapper">
                <div class="page-content">
                    <main>
                        @yield('content')
                    </main>
                </div>
            </div>
            
            @include('partials.footer')
        </div>

        <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/chartjs/js/chart.js') }}"></script>
        <script src="{{ asset('public/assets/js/index.js') }}"></script>
        
        <script src="{{ asset('public/assets/js/app.js') }}"></script>

        <script src="{{ asset('public/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{ asset('public/assets/plugins/select2/js/select2-custom.js?v=9876') }}"></script>

        <script>
            new PerfectScrollbar(".app-container")
        </script>
        
        @yield('footer_scripts')
    </body>
</html>

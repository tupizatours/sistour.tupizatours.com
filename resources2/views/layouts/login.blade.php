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

        <link href="{{ asset('public/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
        <!-- loader-->
        <link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('public/assets/js/pace.min.js') }}"></script>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="{{ asset('public/assets/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet">

        @yield('template_linked_fonts')
        @yield('template_linked_css')
        @yield('template_fastload_css')

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
            .btn-primary, .btn-primary:hover {
                background-color: #6690F4;
                border-color: #6690F4;
            }
            .text-primary, .text-primary:hover {
                color: #6690F4 !important;
            }
            .auth-img-cover-login {
                object-fit: cover;
                height: 100%;
                width: 100%;
            }
            .card-body {
                padding: 0;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <main class="">
                <div class="wrapper">
                    <div class="section-authentication-cover">
                        <div class="">
                            <div class="row g-0">
                                <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left justify-content-center d-none d-xl-flex">
                                    <div class="card shadow-none shadow-none rounded-0 mb-0">
                                        <div class="card-body">
                                            <img src="https://tours.oeo.com.bo/files/system/system_file654a9cc48cfa4-page-bg.png" class="img-fluid auth-img-cover-login" width="550" alt=""/>
                                        </div>
                                    </div>
                                </div>

                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
        
        <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
        
        <script src="{{ asset('public/assets/js/app.js') }}"></script>
    </body>
</html>

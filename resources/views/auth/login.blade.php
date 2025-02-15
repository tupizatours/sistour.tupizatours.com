@extends('layouts.login')

@section('template_title')
    Iniciar sesión
@endsection

@section('content')
    <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
            <div class="card-body p-sm-5">
                <div class="">
                    <div class="mb-3 text-center">
                        <img src="assets/images/logo-icon.png" width="60" alt="">
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="mb-0">Sistema de Tours</h5>
                        <p class="mb-0">Ingresa tus datos para ingresar al administrador</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('login') }}" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input id="email" type="email" class="form-label form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="example@user.com" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <label for="password" class="form-label">Contraseña</label>
                                
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" class="form-control border-end-0 form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required placeholder="Ingresa tu contraseña">
                                </div>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Recuerdame</label>
                                </div>
                            </div>

                            <div class="col-md-6 text-end">
                                <a class="text-primary" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="text-center ">
                                    <p class="mb-0">¿No tienes una cuenta? <a class="text-primary" href="{{ url('register') }}">Regístrarse</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

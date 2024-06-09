@extends('layouts.imports')

@section('content')
<style>
    body {
        background: url('assets/img/logo_side_image.jpg') no-repeat center center;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        font-family: 'Arial', sans-serif;
    }

    .login-card {
        max-width: 710px;
    }
</style>

<div class="container-fluid blur-background">
    <div class="card shadow-lg login-card">
        <div class="card-body-login">
            <img src="/assets/img/logo.png" alt="Logo" class="logo-login">
            <h5 class="card-title">Crea una cuenta</h5>
            <p class="card-subtitle">Ingrese sus datos personales para crear una cuenta.</p>

            @if($errors->has('email') || $errors->has('password'))
                <div class="alert alert-danger">
                    <strong>Algo salió mal.</strong> Por favor verifica tus datos e inténtalo de nuevo.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                @csrf
                <div class="row">

                    <div class="col-md-6 mb-3 text-start">
                        <label for="name" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text1"><i class="bi bi-person-fill"></i></span>
                            <input id="name" type="text"
                                class="form-control form-control1 @error('name') is-invalid @enderror" name="name"value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 text-start">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text1"><i class="bi bi-envelope-fill"></i></span>
                            <input id="email" type="email"
                                class="form-control form-control1 @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email"
                                pattern="^[\w\.-]+@itoaxaca\.edu\.mx$"
                                title="El correo electrónico debe finalizar con @itoaxaca.edu.mx">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3 text-start">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text1"><i class="bi bi-lock-fill"></i></span>
                            <input id="password" type="password"
                                class="form-control form-control1 @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">La contraseña debe tener 8 caracteres, una mayúscula, una
                                    minúscula y un número.
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 text-start">
                        <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text1"><i class="bi bi-lock-fill"></i></span>
                            <input id="password-confirm" type="password" class="form-control form-control1"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary1 w-100">Crear cuenta</button>
                <p class="small mt-3 mb-0">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                        class="text-primary">Iniciar sesión</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
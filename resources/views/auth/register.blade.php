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

    .blur-background {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 123, 255, 0.3);
        position: absolute;
        top: 0;
        left: 0;
    }

    .login-card {
        max-width: 700px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        border: none;
        overflow: hidden;
        margin: 20px;
    }

    .card-body {
        padding: 50px;
        text-align: center;
    }

    .input-group-text {
        background: transparent;
        border: none;
        color: #0f47ad;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid #0f47ad;
        border-radius: 10px;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #083175;
        background: rgba(255, 255, 255, 1);
        box-shadow: none;
    }

    .btn-primary {
        background-color: #0f47ad;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-size: 1rem;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(15, 71, 173, 0.3);
    }

    .btn-primary:hover {
        background-color: #083175;
        transform: scale(1.05);
    }

    .form-check-input:checked {
        background-color: #0f47ad;
        border-color: #0f47ad;
    }

    .logo {
    width: 25%; /* Tamaño más pequeño */
    margin-bottom: 20px;
  }


    .form-label {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .alert {
        text-align: left;
        margin-bottom: 20px;
        background: rgba(255, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
        padding: 15px;
    }

    .text-primary {
        color: #0f47ad;
        text-decoration: none;
    }

    .text-primary:hover {
        text-decoration: underline;
    }

    .card-title {
        color: #0f47ad;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
    }

    .card-subtitle {
        color: #083175;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
</style>

<div class="container-fluid blur-background">
    <div class="card shadow-lg login-card">
        <div class="card-body">
            <img src="/assets/img/logo.png" alt="Logo" class="logo">
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
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                                        <div class="col-md-6 mb-3 text-start">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                pattern="^[\w\.-]+@itoaxaca\.edu\.mx$" title="El correo electrónico debe terminar con @itoaxaca.edu.mx">
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
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                                @error('password')
                            <div class="invalid-feedback">La contraseña debe tener mínimo 8 caracteres,una mayúscula,una minúscula y un número.
</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 text-start">
                        <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
                <p class="small mt-3 mb-0">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                        class="text-primary">Iniciar sesión</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
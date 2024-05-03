@extends('layouts.imports')

@section('content')
<style>
    body {
        background: url('assets/img/fondo.jpg') no-repeat center center;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .blur-background {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 123, 255, 0.2);
        backdrop-filter: blur(15px); /* More pronounced blur effect */
    }

    .login-card {
        max-width: 900px;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05); /* Softer shadow */
        border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
        overflow: hidden;
    }

    .card-body {
        padding: 40px;
        transition: all 0.3s ease;
    }

    .input-group-text {
        background: transparent;
        border: none;
        color: #007bff;
    }

    .form-control {
        background: transparent;
        border: 2px solid #007bff;
        border-radius: 5px;
        padding: 10px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #0056b3;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,123,255,0.2);
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .logo {
        width: 30%;
    }
</style>
<div class="container-fluid blur-background">
    <section class="register d-flex flex-column align-items-center justify-content-center w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg login-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center mb-3">
                                <img src="/assets/img/logo.png" alt="Instituto Logo" class="logo">
                            </div>
                            
                            <h5 class="card-title text-center pb-2">Crea una cuenta</h5>
                            <p class="text-center small mb-3">Ingrese sus datos personales para crear una cuenta.</p>
                            
                            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                            </div>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                            </div>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            </div>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
                                <p class="text-center small mt-3">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-primary">Iniciar sesión</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
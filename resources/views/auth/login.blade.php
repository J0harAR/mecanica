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
  <div class="card shadow-lg login-card">
    <div class="row g-0">

      <!-- Column for the image -->
      <div class="col-md-5 d-flex align-items-center">
        <img src="/assets/img/logo_side_image.jpg" alt="Side Image" class="w-100" style="height: 100%; object-fit: cover;">
      </div>
      
      <!-- Column for the login form -->
      <div class="col-md-7">
        <div class="card-body">
          <div class="d-flex justify-content-center">
            <img src="/assets/img/logo.png" alt="Logo" class="logo">
          </div>

          <h5 class="card-title text-center">Iniciar sesión en tu cuenta</h5>
          <p class="text-center small">Ingresa tu correo electrónico y contraseña para acceder.</p>

          @if($errors->has('email') || $errors->has('password'))
            <div class="alert alert-danger">
              <strong>Algo salió mal.</strong> Por favor verifica tus datos e inténtalo de nuevo.
            </div>
          @endif

          <form class="needs-validation" method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3">
              <label for="password" the form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
              </div>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember">
              <label class="form-check-label" for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
            <p class="text-center small mt-3 mb-0">¿No tienes cuenta? <a href="{{route('register')}}" class="text-primary">Crear cuenta</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

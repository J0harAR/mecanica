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
    background: rgba(0, 123, 255, 0.3); /* Aumentar opacidad */
    position: absolute;
    top: 0;
    left: 0;
  }

  .login-card {
    max-width: 600px; /* Ancho ajustado */
    background: rgba(255, 255, 255, 0.9); /* Fondo con transparencia */
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    border: none;
    overflow: hidden;
    margin: 20px; /* Espaciado para pequeñas pantallas */
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
    display: block;
    margin-left: auto;
    margin-right: auto;
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
    font-size: 2rem; /* Tamaño de fuente más grande */
    font-weight: bold;
    margin-bottom: 5px; /* Separación del resto del contenido */
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2); /* Sombra de texto */
  }

  .card-subtitle {
    color: #083175;
    font-size: 1.1rem; /* Tamaño de fuente ajustado */
    margin-bottom: 10px; /* Espacio inferior */
  }
</style>

<div class="container-fluid blur-background">
  <div class="card shadow-lg login-card">
    <div class="card-body">
      <img src="/assets/img/logo.png" alt="Logo" class="logo">
      <h5 class="card-title">Iniciar sesión en tu cuenta</h5>
      <p class="card-subtitle">Ingresa tu correo electrónico y contraseña para acceder.</p>

      @if($errors->has('email') || $errors->has('password'))
        <div class="alert alert-danger">
          <strong>Algo salió mal.</strong> Por favor verifica tus datos e inténtalo de nuevo.
        </div>
      @endif

      <form class="needs-validation" method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <div class="mb-3 text-start">
          <label for="email" class="form-label">Correo electrónico</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
          </div>
        </div>

        <div class="mb-3 text-start">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        <p class="small mt-3 mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-primary">Crear cuenta</a></p>
      </form>
    </div>
  </div>
</div>

@endsection
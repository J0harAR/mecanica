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
</style>

<div class="container-fluid blur-background">
  <div class="card shadow-lg login-card">
    <div class="card-body-login">
      <img src="/assets/img/logo.png" alt="Logo" class="logo-login">
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
            <span class="input-group-text input-group-text1"><i class="bi bi-envelope-fill"></i></span>
            <input id="email" type="email" class="form-control form-control1" name="email" required autocomplete="email" autofocus>
          </div>
        </div>

        <div class="mb-3 text-start">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text input-group-text1"><i class="bi bi-lock-fill"></i></span>
            <input id="password" type="password" class="form-control form-control1" name="password" required autocomplete="current-password">
          </div>
        </div>

        <button type="submit" class="btn btn-primary1 w-100">Iniciar sesión</button>
        <p class="small mt-3 mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-primary">Crear cuenta</a></p>
      </form>
    </div>
  </div>
</div>

@endsection
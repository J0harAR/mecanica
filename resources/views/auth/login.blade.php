@extends('layouts.imports')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; background: url('assets/img/fondo.jpg') no-repeat center center; background-size: cover;">
  <section class="register d-flex flex-column align-items-center justify-content-center w-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6">

          <div class="card shadow-lg" style="border-radius: 25px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
            <div class="card-body p-4">

              <!-- Logo integrado con un efecto visual moderno -->
              <div class="d-flex justify-content-center py-3">
                <img src="/assets/img/logo.png" alt="Instituto Logo" class="logo w-50">
              </div><!-- End Logo -->

              <h5 class="card-title text-center pb-0 fs-4" style="color: #343a40;">Iniciar sesión en tu cuenta</h5>
              <p class="text-center small text-muted mb-4">Ingresa tu correo electrónico y contraseña para acceder.</p>

              @if($errors->has('email') || $errors->has('password'))
                <div class="alert alert-danger" role="alert">
                  <strong>Algo salió mal.</strong> Por favor verifica tus datos e inténtalo de nuevo.
                </div>
              @endif

              <form class="needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text" id="email-addon"><i class="bi bi-envelope-fill"></i></span>
                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
                    <div class="invalid-feedback">Campo obligatorio</div>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <div class="input-group">
                    <span class="input-group-text" id="password-addon"><i class="bi bi-lock-fill"></i></span>
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                    <div class="invalid-feedback">Campo obligatorio</div>

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
  </section>
</div>
@endsection



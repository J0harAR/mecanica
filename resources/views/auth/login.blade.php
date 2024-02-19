@extends('layouts.imports')

@section('content')
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center align-items-center py-4">
              <img src="/assets/img/logo.png" alt="" class="logo w-25 shadow-lg rounded-pill">
            </div><!-- End Logo -->

            <div class="card mb-3">
              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Iniciar sesión en tu cuenta</h5>
                  <p class="text-center small">Ingresa tu correo electrónico y contraseña para iniciar sesión</p>
                </div>

                @if($errors->has('email') || $errors->has('password'))
                  <p class="text-danger mb-0">Algo salió mal.</p>
                  <ul><li class="text-danger mb-0 small mt-3">Estas credenciales no coinciden con nuestros registros.</li></ul>
                  
                @endif

                <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="col-12 mb-3">
                    
                    <label for="email" class="form-label">Correo electrónico</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="email-addon"><i class="bi bi-envelope-fill"></i></span>
                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>
                  </div>

                  <div class="col-12 mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="password-addon"><i class="bi bi-lock-fill"></i></span>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    </div>
                  </div>

                  <div class="col-12 mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                      <label class="form-check-label" for="remember">
                        Recordarme
                      </label>
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
                  </div>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
@endsection

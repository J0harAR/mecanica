@extends('layouts.imports')

@section('content')


<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; background: url('/assets/img/fondo.jpg') no-repeat center center; background-size: cover;">

<section class="register d-flex flex-column align-items-center justify-content-center w-100">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="card shadow-lg" style="border-radius: 25px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
            <div class="card-body p-4">
               <div class="d-flex justify-content-center py-3">
                <img src="/assets/img/logo.png" alt="Instituto Logo" class="logo w-50">
              </div><!-- End Logo -->
              
              <h5 class="card-title text-center pb-0 fs-4" style="color: #343a40;">Crea una cuenta</h5>
              <p class="text-center small text-muted mb-4">Ingrese sus datos personales para crear una cuenta.</p>
              
              <form method="POST" action="{{ route('register') }}" class="row g-3 needs-validation" novalidate>
              @csrf
              <div class="col-12">
                <label for="name" class="form-label">Nombre</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                                    
                          
                                <div class="invalid-feedback">Campo obligatorio</div>
                               
                        
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Correo electronico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                                     
                            @error('email')
                              
                                <div class="invalid-feedback">El correo proporcionado ya pertenece a una cuenta</div>
                            @enderror
                            <div class="invalid-feedback">Introduzca un correo valido</div>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">                                         
                                @error('password')
                                <div class="invalid-feedback">Contraseña no valida</div>
                                @enderror
                                @if(!$errors->has('password'))
                                <div class="invalid-feedback">Campo obligatorio</div>
                                @endif
                        </div>

                        <div class="col-12">
                            <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
                <p class="text-center small mt-3 mb-0">¿Ya tienes una cuenta? <a href="{{route('login')}}" class="text-primary">Iniciar sesión</a></p>

                    </form>

                </div>
              </div>



            </div>
          </div>
        </div>

      </section>

    </div>
@endsection

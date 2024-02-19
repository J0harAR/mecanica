@extends('layouts.imports')

@section('content')


<div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                    <img src="/assets/img/logo.png" alt="" class="logo w-25 shadow-lg rounded-pill">
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Crea una cuenta</h5>
                    <p class="text-center small">Ingrese sus datos personales para crear una cuenta</p>
                  </div>

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

                        <div class="col-12">                
                            <button class="btn btn-primary w-100" type="submit">Crear Cuenta</button>
                        </div>

                        <div class="col-12">
                            <p class="small mb-0">¿Ya tienes una cuenta?<a href="{{route('login')}}">Iniciar Sesión</a></p>
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

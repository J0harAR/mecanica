@extends('layouts.imports')

@section('content')
<div class="container">

<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

        <div class="d-flex justify-content-center align-items-center py-4">
                  <img src="/assets/img/logo.png" alt=""  class="logo w-25 shadow-lg rounded-pill">
          </div><!-- End Logo -->

        <div class="card mb-3">

          <div class="card-body">

            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
              <p class="text-center small">Enter your email & password to login</p>
            </div>

            <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
            @csrf
              <div class="col-12">
                <label for="email" class="form-label">Correo electronico</label>
                <div class="input-group has-validation">
                  
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
              </div>

              <div class="col-12">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                
              </div>


              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Login</button>
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

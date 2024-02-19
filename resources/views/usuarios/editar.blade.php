@extends('layouts.app')

@section('content')
@can('editar-usuarios')
  

<style>
    .custom-card {
      max-width: 700px;
      margin: 0 auto;
      margin-top: 100px;
    }
  </style>




<div class="pagetitle">
      <h1>Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
          <li class="breadcrumb-item ">Usuarios</li>
          <li class="breadcrumb-item active">Crear</li>
        </ol>
      </nav>
</div>

<div class="card custom-card">
    <div class="card-body">
      <h5 class="card-title">Editar Usuario</h5>

      <!-- Vertical Form -->
      <form class="row g-3" action="{{route('usuarios.update',$user->id)}}" method="POST">
       @method('PATCH')
        @csrf
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Nombre completo </label>
                  <input type="text" class="form-control" name="name" value="{{$user->name}}" required novalidate>
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Email</label>
                  <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required novalidate>
                  @error('email')
                   <div class="invalid-feedback">El correo proporcionado ya pertenece a una cuenta</div>
                   @enderror

                </div>
                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Contraseña</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"   novalidate>
                  @error('password')
                  <div class="invalid-feedback">Contraseña no valida</div>
                  @enderror
                </div>
                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Confirmar contraseña</label>
                  <input type="password" class="form-control" name="confirm-password">
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label for="inputPassword4" class="form-label">Rol</label>
                      <select name="roles[]" class="form-select">
                        @foreach($roles as $role)
                          <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>

               
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Aceptar</button>
                 
                </div>
              </form><!-- Vertical Form -->

    </div>
  </div>


@endcan
@endsection
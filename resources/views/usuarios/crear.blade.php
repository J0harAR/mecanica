@extends('layouts.app')

@section('content')
@can('crear-usuarios')

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-user-plus me-1"></i>Registrar Usuario
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="/usuarios" class="text-decoration-none text-primary">
              <i class="fas fa-users me-1"></i>Usuarios
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-user-plus me-1"></i>Crear
          </li>
        </ol>
      </nav>
    </div>
    @can('ver-usuarios')
    <a href="{{ route('usuarios.index') }}" class="btn btn-tecnm">
      <i class="fas fa-users me-1"></i>Ver Usuarios
    </a>
    @endcan
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      window.setTimeout(function () {
        const successAlert = document.getElementById("success-alert");
        if (successAlert) successAlert.style.display = 'none';
      }, 3000);
    });
  </script>
  @endif

  <div class="card custom-card">
    <div class="card-body">
      <h5 class="card-title">Registrar usuario</h5>

      <!-- Vertical Form -->
      <form class="row g-3 needs-validation" action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="col-12">
          <label for="inputName4" class="form-label">Nombre completo 
            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese el nombre completo del usuario."></i>
          </label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" value="{{ old('name') }}">
          @error('name')
          <div class="invalid-feedback mt-2">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label for="inputEmail4" class="form-label">Email 
            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese el correo electrónico del usuario."></i>
          </label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ old('email') }}"
          pattern="^[\w\.-]+@itoaxaca\.edu\.mx$"
                                title="El correo electrónico debe finalizar con @itoaxaca.edu.mx">
                                  
          
          @error('email')
          <div class="invalid-feedback mt-2">El correo electrónico debe finalizar con @itoaxaca.edu.mx </div>
          @enderror
        </div>
        <div class="col-12">
          <label for="inputPassword4" class="form-label">Contraseña 
            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese una contraseña para el usuario."></i>
          </label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
          @error('password')
          <div class="invalid-feedback mt-2">Contraseña no válida</div>
          @enderror
        </div>
        <div class="col-12">
          <label for="confirm-password" class="form-label">Confirmar contraseña 
            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Confirme la contraseña ingresada."></i>
          </label>
          <input type="password" class="form-control" name="confirm-password" required>
        </div>
        <div class="col-12">
          <label for="roles" class="form-label">Rol 
            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccione el rol para el usuario."></i>
          </label>
          <select name="roles[]" class="form-select" id="roles">
            @foreach($roles as $role)
            <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
          </select>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Registrar
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan
@endsection
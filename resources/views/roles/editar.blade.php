@extends('layouts.app')

@section('content')
@can('editar-rol')

  <div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
    <h1 class="fw-bold mb-0 text-primary">
      <i class="fas fa-user-shield me-2"></i>Editar Rol
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
        <i class="fas fa-home me-1"></i>Dashboard
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="/roles" class="text-decoration-none text-primary">
        <i class="fas fa-user-shield me-1"></i>Roles
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        <i class="fas fa-edit me-1"></i>Editar
      </li>
      </ol>
    </nav>
    </div>
    @can('ver-roles')
    <a href="{{ route('usuarios.index') }}" class="btn btn-tecnm">
      <i class="fas fa-user-shield me-1"></i>Ver roles
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
    <h5 class="card-title">Editar Rol</h5>

    <!-- Vertical Form -->
    <form class="row g-3 needs-validation" action="{{ route('roles.update', $role->id) }}" method="POST">
      @method('PATCH')
      @csrf
      <div class="col-12">
      <label for="inputName4" class="form-label">Nombre del rol <i class="bi bi-info-circle ms-1"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese el nombre del rol."></i></label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
        value="{{ $role->name }}" required>
      @error('name')
    <div class="invalid-feedback mt-2">{{ $message }}</div>
  @enderror
      </div>

      <div class="col-12">
      <div class="form-group">
        <label for="inputPermissions" class="form-label">Permisos del rol <i class="bi bi-info-circle ms-1"
          data-bs-toggle="tooltip" data-bs-placement="top"
          title="Seleccione los permisos aplicables a este rol."></i></label>
        <br>
        @foreach ($permission as $value)
      <div class="form-check">
      <input type="checkbox" name="permission[]" value="{{ $value->name }}" class="form-check-input" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
      <label class="form-check-label">{{ $value->name }}</label>
      </div>
    @endforeach
        <small class="form-text text-muted">Seleccione uno o más permisos para este rol.</small>
      </div>
      </div>

      <div class="text-center">
      <button type="submit" class="btn btn-primary">Guardar
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>
      </div>
    </form>
    </div>
  </div>
  </div>
@endcan

@endsection
@extends('layouts.app')

@section('content')

@can('crear-rol')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-plus-circle me-1"></i>Crear Rol
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('roles.index') }}" class="text-decoration-none text-primary">
              <i class="fas fa-user-shield me-1"></i>Roles
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-plus-circle me-1"></i>Crear
          </li>
        </ol>
      </nav>
    </div>
    @can('ver-roles')
    <a href="{{ route('roles.index') }}" class="btn btn-tecnm">
      <i class="fas fa-user-shield me-1"></i>Ver roles
    </a>
    @endcan
  </div>

  <div class="card custom-card shadow-lg">
    <div class="card-body">
      <h5 class="card-title text-primary">Registrar Rol</h5>
      

      <form class="row g-3 needs-validation" action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="col-12 mb-3">
          <label for="inputName4" class="form-label">Nombre del rol <i class="bi bi-info-circle ms-1"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese el nombre del nuevo rol."></i></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required>
          @error('name')
          <div class="invalid-feedback mt-2">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12">
          <div class="form-group">
            <label class="form-label">Permisos del rol <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
              data-bs-placement="top" title="Seleccione los permisos aplicables a este rol."></i></label>

            @php
            $groupedPermissions = [];
            foreach ($permission as $perm) {
                $parts = explode('-', $perm->name);
                $prefix = $parts[0];
                if (!isset($groupedPermissions[$prefix])) {
                    $groupedPermissions[$prefix] = [];
                }
                $groupedPermissions[$prefix][] = $perm;
            }
            @endphp

            <!-- Tabs navigation -->
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
              @foreach ($groupedPermissions as $prefix => $perms)
              <li class="nav-item" role="presentation">
                <a class="nav-link @if ($loop->first) active @endif" id="tab-{{ $prefix }}" data-bs-toggle="tab" href="#category-{{ $prefix }}" role="tab" aria-controls="category-{{ $prefix }}" aria-selected="true">
                  {{ ucfirst($prefix) }}
                </a>
              </li>
              @endforeach
            </ul>

            <!-- Tabs content -->
            <div class="tab-content" id="myTabContent">
              @foreach ($groupedPermissions as $prefix => $perms)
              <div class="tab-pane fade @if ($loop->first) show active @endif" id="category-{{ $prefix }}" role="tabpanel" aria-labelledby="tab-{{ $prefix }}">
                @foreach ($perms as $perm)
                <div class="form-check form-check-inline mb-2">
                  <input type="checkbox" name="permission[]" value="{{ $perm->name }}" class="form-check-input @error('permission') is-invalid @enderror">
                  <label class="form-check-label">{{ $perm->name }}</label>
                </div>
                @endforeach
              </div>
              @endforeach
            </div>

            <small class="form-text text-muted">Seleccione uno o más permisos para este rol.</small>
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary">
            Registrar <i class="fas fa-save ms-1"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

@endsection
@extends('layouts.app')

@section('content')
@can('editar-rol')

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary text-uppercase text-center">Editar Rol</h1>
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
            <i class="fas fa-edit me-1"></i>Editar
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

  <div class="card custom-card shadow-sm border-0 mx-auto" style="max-width: 80%;">
    <div class="card-body">
      <h5 class="card-title text-center text-uppercase fw-bold">Editar Rol</h5>

      <form class="row g-3 needs-validation" action="{{ route('roles.update', $role->id) }}" method="POST">
        @method('PATCH')
        @csrf
        <div class="col-12 mb-4">
          <label for="inputName4" class="form-label">Nombre del rol <i class="bi bi-info-circle ms-1"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Ingrese el nombre del rol."></i></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
            value="{{ $role->name }}" required>
          @error('name')
          <div class="invalid-feedback mt-2">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">Permisos del rol <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccione los permisos aplicables a este rol."></i></label>

          @php
          $permissions = [
              'Usuarios' => ['ver-usuarios', 'crear-usuarios', 'editar-usuarios', 'borrar-usuarios'],
              'Rol' => ['ver-rol', 'crear-rol', 'editar-rol', 'borrar-rol'],
              'Alumnos' => ['ver-alumnos', 'crear-alumnos', 'editar-alumnos', 'borrar-alumnos'],
              'Asignaturas' => ['ver-asignaturas', 'crear-asignatura', 'editar-asignatura', 'borrar-asignatura'],
              'Docentes' => ['ver-docentes', 'crear-docente', 'ver-docente', 'editar-docente', 'borrar-docente', 'asignar-grupos-docente', 'eliminar-grupos-docente'],
              'Grupos' => ['ver-grupos', 'crear-grupo', 'borrar-grupo'],
              'Inventario' => ['ver-inventario', 'borrar-inventario'],
              'Herramientas' => ['ver-herramientas', 'crear-herramienta', 'editar-herramienta', 'borrar-herramienta'],
              'Insumos' => ['ver-insumos', 'crear-insumo', 'editar-insumo', 'borrar-insumo'],
              'Maquinarias' => ['ver-maquinarias', 'crear-maquinaria', 'editar-maquinaria', 'asignar-insumos-maquinaria', 'borrar-maquinaria'],
              'Mantenimientos' => ['ver-mantenimientos', 'crear-mantenimiento', 'borrar-mantenimiento'],
              'Periodos' => ['ver-periodos', 'crear-periodo', 'editar-periodo', 'borrar-periodo'],
              'Practicas' => ['ver-practicas', 'crear-practica', 'ver-practica', 'editar-practica', 'borrar-practica', 'completar-practica', 'crear-practica-alumno'],
              'Prestamos' => ['ver-prestamos', 'crear-prestamo', 'editar-prestamo', 'borrar-prestamo', 'finalizar-prestamo'],
              'Reportes' => ['generar_reporte_prestamo', 'generar_reporte_inventario', 'generar_reporte_herramientas', 'generar_reporte_maquinaria', 'generar_reporte_insumos', 'generar_reporte_practicas']
              'Lecturas'=>['ver-lecturas','crear-lectura']
          ];

          $rolePermissions = $role->permissions->pluck('name')->toArray();
          @endphp

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Categoría</th>
                  <th>Permisos</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($permissions as $entity => $actions)
                <tr>
                  <td class="fw-bold text-uppercase">{{ $entity }}</td>
                  <td>
                    <div class="form-check mb-2">
                      <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll{{ $entity }}" onclick="toggleSelectAll('{{ $entity }}')">
                      <label class="form-check-label" for="selectAll{{ $entity }}">Seleccionar Todo</label>
                    </div>
                    <div class="d-flex flex-column">
                      @foreach ($actions as $action)
                      <div class="form-check">
                        <input type="checkbox" name="permission[]" value="{{ $action }}" class="form-check-input {{ $entity }}-checkbox @error('permission') is-invalid @enderror" {{ in_array($action, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ ucfirst(str_replace('-', ' ', $action)) }}</label>
                      </div>
                      @endforeach
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <small class="form-text text-muted">Seleccione uno o más permisos para este rol.</small>
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

<script>
  function toggleSelectAll(entity) {
    var checkboxes = document.querySelectorAll('.' + entity + '-checkbox');
    var selectAll = document.getElementById('selectAll' + entity);
    checkboxes.forEach(checkbox => {
      checkbox.checked = selectAll.checked;
    });
  }
</script>

@endsection
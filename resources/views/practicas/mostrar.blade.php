@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="bi bi-journal me-1"></i> Prácticas
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('practicas.index')}}" class="text-decoration-none text-primary">
              <i class="bi bi-journal me-1"></i> Prácticas
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-eye"></i> Ver práctica
          </li>
        </ol>
      </nav>

      <div class="card-body">
        <form class="row g-3">
          <div class="col-12">
            <label for="codigo_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>No. Práctica</label>
            <input type="text" class="form-control" name="codigo_practica" value="{{ $practica->id_practica }}" required
              disabled>
          </div>

          <div class="col-12">
            <label for="nombre_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la
              Práctica</label>
            <input type="text" class="form-control" name="nombre_practica" required value="{{ $practica->nombre }}"
              disabled>
          </div>

          <div class="col-md-12">
            <label for="docente" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
            <select id="docente" class="form-select" required name="docente" disabled>
              <option disabled>Selecciona un docente</option>
              @foreach ($docentes as $docente)
        <option value="{{ $docente->rfc }}" {{ $practica->docente->rfc === $docente->rfc ? 'selected' : '' }}>
          {{ $docente->persona->nombre }}
        </option>
      @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="asignatura" class="form-label"><i class="fas fa-bullseye me-2"></i>Asignatura</label>
            <input type="text" class="form-control" name="asignatura" required
              value="{{ $practica->asignatura->nombre ?? '' }}" disabled>
          </div>

          <div class="col-12">
            <label for="objetivo" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
            <textarea class="form-control wide-input" name="objetivo" required
              disabled>{{ $practica->objetivo }}</textarea>
          </div>

          <div class="col-12">
            <label for="introduccion" class="form-label"><i class="fas fa-file-alt me-2"></i>Introducción</label>
            <textarea class="form-control wide-input" name="introduccion" required
              disabled>{{ $practica->introduccion }}</textarea>
          </div>

          <div class="col-12">
            <label for="fundamento" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
            <textarea class="form-control wide-input" name="fundamento" required
              disabled>{{ $practica->fundamento }}</textarea>
          </div>

          <div class="col-12">
            <label for="referencias" class="form-label"><i class="fas fa-bookmark me-2"></i>Referencias</label>
            <textarea class="form-control wide-input" name="referencias" required
              disabled>{{ $practica->referencias }}</textarea>
          </div>

          <div class="col-12">
            <label for="articulos" class="form-label"><i class="fas fa-clipboard-check me-2"></i>Artículos</label>
            @foreach ($practica->catalogo_articulos as $articulo)
        <input type="text" class="form-control mb-2" value="{{ $articulo->nombre }}" disabled>
      @endforeach
          </div>

          <div class="col-12">
            <label for="estatus" class="form-label"><i class="fas fa-clipboard-check me-2"></i>Estatus</label>
            <input type="text" class="form-control" name="estatus" required value="{{ $practica->estatus }}" disabled>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
  .wide-input {
    width: 100%;
    height: 100px;
  }
</style>
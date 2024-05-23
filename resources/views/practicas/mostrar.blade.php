@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
    <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-book "></i> Prácticas
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
        <i class="fas fa-home me-1" ></i> Prácticas
    </a>
    </li>
      <li class="breadcrumb-item active" aria-current="page">
        <i class="fas fa-book "></i> Ver práctica
        </li>
      </ol>
    </nav>
    
        <div class="card-body">
            <form class="row g-3">
                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-id-badge me-2"></i>No. Práctica</label>
                    <input type="text" class="form-control" name="codigo_practica" value="{{ $practica->id_practica }}" required disabled>
                </div>

                <div class="col-md-12">
                    <label for="tipo" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
                    <select id="tipo" class="form-select" required name="docente" disabled>
                        <option disabled>Selecciona un docente</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->rfc }}" {{ $practica->docente->rfc === $docente->rfc ? 'selected' : '' }}>
                                {{ $docente->persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la Práctica</label>
                    <input type="text" class="form-control" name="nombre_practica" required value="{{ $practica->nombre }}" disabled>
                </div>

                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
                    <input type="text" class="form-control" name="objetivo" required value="{{ $practica->objetivo }}" disabled> 
                </div>

                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-file-alt me-2"></i>Introducción</label>
                    <input type="text" class="form-control" name="introduccion" required value="{{ $practica->introduccion }}" disabled>
                </div>

                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
                    <input type="text" class="form-control" name="fundamento" required value="{{ $practica->fundamento }}" disabled>
                </div>

                <div class="col-12">
                    <label for="inputNanme4" class="form-label"><i class="fas fa-bookmark me-2"></i>Referencias</label>
                    <input type="text" class="form-control" name="referencias" required value="{{ $practica->referencias }}" disabled>
                </div>  

                <div class=" row-3">
                    <label for="articulos" class="form-label"><i class="fas fa-clipboard-check me-2"></i>Artículos</label>
                    @foreach ($practica->catalogo_articulos as $articulo)
                        <input type="text" class="form-control mb-2" value="{{ $articulo->nombre }}" disabled>
                    @endforeach
                </div>

                <div class="col-12">
                    <label for="inputEstatus" class="form-label"><i class="fas fa-clipboard-check me-2"></i>Estatus</label>
                    <input type="text" class="form-control" name="estatus" required value="{{ $practica->estatus }}" disabled>
                </div>  
            </form>
        </div>
    </div>
</div>
@endsection


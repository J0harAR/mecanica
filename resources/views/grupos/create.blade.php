@extends('layouts.app')

@section('content')
@can('crear-grupo')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users"></i> Registro de Grupo
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        @can('ver-grupos')                                                  
                        <a href="{{ route('grupos.index')}}" class="text-decoration-none text-primary">
                            <i class="fas fa-users me-1"></i> Administración de Grupos
                        </a>
                        @endcan
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-plus"></i> Registrar Grupo
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    @can('crear-grupo')
    <div class="card custom-card">
        <div class="card-body">
            <form class="row g-3 needs-validation" action="{{ route('grupos.store') }}" method="POST" novalidate>
                @csrf
                <div class="col-md-4">
                    <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i> Periodo</label>
                    <select class="form-select" id="floatingSelect" name="periodo" required>
                        <option selected disabled>Selecciona el periodo</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->clave }}">{{ $periodo->clave }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Seleccione un periodo
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i> Asignatura</label>
                    <select class="form-select" id="floatingSelect" name="asignatura" required>
                        <option selected disabled>Selecciona una asignatura</option>
                        @foreach ($asignaturas as $asignatura)
                            <option value="{{ $asignatura->clave }}">{{ $asignatura->clave }} - {{ $asignatura->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Seleccione una asignatura.
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="clave_grupo" class="form-label"><i class="fas fa-users me-2"></i> Grupo</label>
                    <input type="text" class="form-control" id="clave_grupo" name="clave_grupo" required>
                    <div class="invalid-feedback">
                        Ingrese la clave del grupo.
                    </div>
                </div>
                
                <div class="col-12 d-flex justify-content-end mt-4">
                    @can('ver-grupos')                                       
                    <a href="{{ route('grupos.index') }}" class="btn btn-light btn-sm text-black me-2">
                        <i class="fas fa-arrow-left"></i> Atrás
                    </a>
                    @endcan
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-check"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endcan
</div>
@endcan
@endsection
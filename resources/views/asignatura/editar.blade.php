@extends('layouts.app')

@section('content')
@can('editar-asignatura')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-book"></i> Editar Asignatura
                </h1>
                
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('asignatura.index') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-book me-1"></i> Asignaturas
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-edit me-1"></i> Editar Asignatura
                        </li>
                    </ol>
                </nav>
            </div>
            @can('crear-asignatura')
                <a href="{{ route('asignatura.index') }}" class="btn btn-primary shadow">
                    <i class="fas fa-book "></i> Asignaturas 
                </a>
            @endcan
        </div>

        <div class="card custom-card">
    <div class="card-body">
                @csrf
                <div class="col-md-8">
                    <label for="floatingSelect" class="form-label"></i>Editar Asignatura</div>
                <div class="card-body mt-2">
                    <form action="{{ route('asignatura.update', ['id' => $asignatura->clave]) }}" method="POST" class="row g-3 needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        <div class="col-md-7">
                            <label for="nombre" class="form-label"><i class="fas fa-book me-2"></i> Nombre Completo</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $asignatura->nombre }}" required>
                            <div class="invalid-feedback">
                                Ingrese el nombre completo de la asignatura.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="clave" class="form-label"><i class="fas fa-key me-2"></i> Clave de Asignatura</label>
                            <input type="text" name="clave" id="clave" class="form-control" value="{{ $asignatura->clave }}" required>
                            <div class="invalid-feedback">
                                Ingrese la clave de la asignatura.
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm shadow">
                                <i class="bi bi-check2"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan
@endsection
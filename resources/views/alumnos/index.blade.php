@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-user-graduate"></i> Alumnos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-graduate"></i>Alumnos
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fas fa-plus-circle me-1"></i> Agregar Alumno
            </button>
        </div>
    </div>

    <!-- Vertically centered Modal -->
    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Agregar Alumno</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('alumnos.store') }}" method="POST">
                        @csrf
                        <div class="col-md-12 mb-3">
                            <label for="no_control" class="form-label"><i class="bi bi-card-text me-2"></i>Número de Control</label>
                            <input type="text" class="form-control" id="no_control" name="no_control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-person me-2"></i>Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido_p" class="form-label"><i class="bi bi-person me-2"></i>Apellido Paterno</label>
                            <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido_m" class="form-label"><i class="bi bi-person me-2"></i>Apellido Materno</label>
                            <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="curp" class="form-label"><i class="bi bi-card-list me-2"></i>CURP</label>
                            <input type="text" class="form-control" id="curp" name="curp" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="grupos" class="form-label"><i class="bi bi-people me-2"></i>Grupo</label>
                            <select multiple class="form-control" id="grupos" name="grupos[]" required>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->clave }}">{{ $grupo->clave }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Vertically centered Modal -->

    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabla de alumnos -->
    <div class="card shadow-sm">
        <div class="card-body">
            @foreach($alumnosPorGrupo as $grupo => $alumnos)
                <h5 class="card-title1 text-primary1 mb-2 fw-bold ">{{ $grupo }}</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número de Control</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>CURP</th>
                            <th>Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumnos as $alumno)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $alumno->no_control }}</td>
                                <td>{{ $alumno->persona->nombre }}</td>
                                <td>{{ $alumno->persona->apellido_p }}</td>
                                <td>{{ $alumno->persona->apellido_m }}</td>
                                <td>{{ $alumno->persona->curp }}</td>
                                <td>{{ implode(', ', $alumno->grupos->pluck('clave')->toArray()) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
    <!-- End Tabla de alumnos -->
</div>
@endsection

@extends('layouts.app')

@section('content')
@can('asignar-grupos-docente')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0 text-primary">
            <i class="fas fa-filter me-1"></i> Filtrar y añadir asignaturas
        </h1>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('docentes.index') }}" class="text-decoration-none text-primary">
                    <i class="fas fa-chalkboard-teacher me-1"></i>Docentes
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-filter me-1"></i>Filtrar y asignar
            </li>
        </ol>
    </nav>

    @if (session('error'))
        <div class="alert alert-danger mt-4" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    @can('asignar-grupos-docente')
    
        <form action="{{ route('docentes.filtrar_asignaturas') }}" method="POST" class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="col-md-6">
                <label for="periodo" class="form-label"><i class="fas fa-calendar-alt me-2"></i>Periodo</label>
                <select class="form-select" id="periodo" name="periodo" required>
                    <option disabled selected>Selecciona...</option>
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->clave }}">{{ $periodo->clave }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Seleccione un periodo.
                </div>
            </div>

            <div class="col-md-6">
                <label for="docente" class="form-label"><i class="fas fa-user-tie me-2"></i>Docente</label>
                <select class="form-select" id="docente" name="docente" required>
                    <option disabled selected>Selecciona...</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente->rfc }}">{{ $docente->rfc }} / {{ $docente->persona->apellido_p }}
                            {{ $docente->persona->apellido_m }} {{ $docente->persona->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Seleccione un docente.
                </div>
            </div>

            <div class="col-md-6">
                <label for="asignatura" class="form-label"><i class="fas fa-book me-2"></i>Asignaturas</label>
                <select class="form-select" id="asignatura" name="asignatura" required>
                    <option disabled selected>Selecciona...</option>
                    @foreach ($asignaturas as $asignatura)
                        <option value="{{ $asignatura->clave }}">{{ $asignatura->clave }} / {{ $asignatura->nombre }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Seleccione una asignatura.
                </div>
            </div>

            <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right me-2"></i>Siguiente</button>
            </div>
        </form>
    @endcan


    @if(session('grupos') && session('docente'))
        @can('asignar-grupos-docente')
            <form action="{{ route('docentes.asignar') }}" method="POST" class="mt-4">
                @csrf

                <input type="hidden" name="rfc_docente" value="{{ session('docente')->rfc }}">
                <input type="hidden" name="clave_periodo" value="{{ session('periodo')->clave }}">

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr class="table-light text-center">
                            <th scope="col">RFC</th>
                            <th scope="col">Nombre completo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{ session('docente')->rfc }}</td>
                            <td>{{ session('docente')->persona->apellido_p }} {{ session('docente')->persona->apellido_m }}
                                {{ session('docente')->persona->nombre }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-sm table-bordered mt-4">
                    <thead>
                        <tr class="table-light text-center">
                            <th scope="col">Clave</th>
                            <th scope="col">Nombre completo</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check me-2"></i>Asignar
                                    Seleccionados</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('grupos') as $grupo)
                            <tr class="text-center">
                                <td>{{ $grupo->asignatura->clave}}</td>
                                <td>{{ $grupo->asignatura->nombre}}</td>
                                <td>{{ $grupo->clave_grupo}}</td>
                                <td>
                                    <input type="checkbox" name="grupos[{{ $grupo->clave_grupo }}][asignatura]"
                                        value="{{ $grupo->asignatura->clave }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        @endcan
    @endif
</div>

<script>
    // JavaScript para la validación del formulario
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // JavaScript para ocultar el mensaje de error después de 3 segundos
    document.addEventListener('DOMContentLoaded', function () {
        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function () {
                errorAlert.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endcan
@endsection
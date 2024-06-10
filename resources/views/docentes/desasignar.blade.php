@extends('layouts.app')

@section('content')

@can('eliminar-grupos-docente')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0 text-primary">
            <i class="fas fa-filter me-1"></i> Filtrar y remover asignaciones
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
                <i class="fas fa-filter me-1"></i>Filtrar y remover
            </li>
        </ol>
    </nav>

    <form action="{{ route('docentes.filtrar_grupos') }}" method="POST" class="row g-3 needs-validation" novalidate>
        @csrf
        <div class="col-md-6">
            <label for="rfc" class="form-label"><i class="fas fa-user-tie me-2"></i>Docente</label>
            <select class="form-select" id="rfc" name="rfc" required>
                <option disabled selected>Selecciona...</option>
                @foreach ($docentes as $docente)
                    <option value="{{ $docente->rfc }}">{{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }}
                        {{ $docente->persona->apellido_m }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Seleccione un docente.
            </div>
        </div>

        <div class="col-md-6">
            <label for="id_asignatura" class="form-label"><i class="fas fa-book me-2"></i>Asignatura</label>
            <select class="form-select" id="id_asignatura" name="id_asignatura" required>
                <option disabled selected>Selecciona...</option>
                @foreach ($asignaturas as $asignatura)
                    <option value="{{ $asignatura->clave }}">{{ $asignatura->clave }} // {{ $asignatura->nombre }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Seleccione una asignatura.
            </div>
        </div>

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

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Filtrar</button>
        </div>
    </form>

    @if (session('grupos'))
        <form action="{{ route('docentes.eliminar_asignacion') }}" method="POST" class="mt-4">
            @csrf

            <input type="hidden" name="rfc" value="{{ session('docente')->rfc }}">
            <input type="hidden" name="periodo" value="{{ session('periodo')->clave }}">

            <table class="table table-sm table-bordered">
                <thead>
                    <tr class="table-light text-center">
                        <th scope="col">Materia</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt me-2"></i>Eliminar Seleccionados</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('grupos') as $grupo)
                        <tr class="text-center">
                            <td>{{ $grupo->asignatura->clave}} // {{$grupo->asignatura->nombre}} </td>
                            <td>{{ $grupo->clave_grupo }}</td>
                            <td>
                                <input type="checkbox" name="grupos[{{ $grupo->clave_grupo }}][asignatura]"
                                    value="{{ $grupo->asignatura->clave }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
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
</script>
@endcan
@endsection

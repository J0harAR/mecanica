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

                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-journal me-1"></i> Prácticas
                    </li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('practicas.create') }}" class="btn btn-tecnm">
            <i class="fas fa-plus-circle me-1"></i>Registrar práctica
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("success-alert");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
            });
        </script>
    @endif

    <form  method="POST" class="mb-4" id="filterForm">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="docente" class="form-label">Docente</label>
                <input type="text" class="form-control" id="docente" name="docente">
            </div>
            <div class="col-md-4 mb-3">
                <label for="asignatura" class="form-label">Asignatura</label>
                <input type="text" class="form-control" id="asignatura" name="asignatura">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label d-block">Estatus</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="completa" name="estatus" value="1">
                    <label class="form-check-label" for="completa">Completadas</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="incompleta" name="estatus" value="0">
                    <label class="form-check-label" for="incompleta">No completadas</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" onclick="submitForm('{{ route('practicas.filtrar') }}')">Filtrar</button>
        <button type="button" class="btn btn-tecnm" onclick="submitForm('{{ route('reporte.practicas') }}')">
            <i class="bi bi-download"></i>
        </button>
    </form>


    <div class="container">
        @if($practicas->isEmpty())
            <div class="alert alert-secondary" role="alert">
                No hay prácticas registradas actualmente.
                <a href="{{ route('practicas.create') }}" class="alert-link">¡Crea una nueva práctica!</a>
            </div>
        @else
            @if (session('practicas'))
                <div class="card shadow-lg rounded-3 border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table
                                class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle"
                                style="border-collapse: separate; border-spacing: 0 10px;">
                                <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Docente</th>
                                        <th>Objetivo</th>
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('practicas') as $practica)
                                        <tr>
                                            <td>{{ $practica->id_practica }}</td>
                                            <td>{{ $practica->nombre }}</td>
                                            <td>{{ $practica->id_docente }}</td>
                                            <td>{{ $practica->objetivo }}</td>
                                            <td>
                                                @if ($practica->estatus == 0)
                                                    <form action="{{route('practicas.completar', ['id' => $practica->id_practica])}}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="btn shadow-sm rounded-pill">
  <i class="fas fa-check-circle me-2"></i> Marcar como completado
</button>                                                    </form>
                                                @else
                                                    Completada
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('practicas.show', ['id' => $practica->id_practica]) }}"
                                                    class="btn btn-outline-danger btn-sm  "><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('practicas.edit', ['id' => $practica->id_practica]) }}"
                                                    class="btn btn-outline-primary btn-sm  "><i class="fas fa-edit"></i></a>

                                                <!-- Botón para abrir el modal de confirmación -->
                                                <button type="button" class="btn btn-outline-danger btn-sm   " data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $practica->id_practica }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <!-- Modal de Confirmación -->
                                                <div class="modal fade" id="deleteModal-{{ $practica->id_practica }}" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas eliminar la práctica
                                                                "{{ $practica->nombre }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <form
                                                                    action="{{ route('practicas.destroy', ['id' => $practica->id_practica]) }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            @endif
                </div>
        @endif
        </div>
    </div>


    <script>
    function submitForm(actionUrl) {
            const form = document.getElementById('filterForm');
            form.action = actionUrl;
            form.submit();
        }
</script>
    @endsection
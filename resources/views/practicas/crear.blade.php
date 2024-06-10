@extends('layouts.app')
@section('content')

@can('crear-practica')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-pen"></i> Registro de práctica
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('practicas.index') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i> Prácticas
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-pen"></i> Registrar práctica
                    </li>
                </ol>
            </nav>
            <div class="container mt-4">
                @can('crear-practica')
                              
                <form class="row g-3 needs-validation" action="{{ route('practicas.store') }}" method="POST" novalidate>
                    @csrf
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-12">
                        <label for="codigo_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>No. Práctica</label>
                        <input type="text" class="form-control" name="codigo_practica" required>
                        <div class="invalid-feedback">
                            Ingrese el número de la práctica.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="docente" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
                        <select id="docente" class="form-select" required name="docente">
                            <option selected disabled>Selecciona un docente</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->rfc }}">{{ $docente->persona->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Seleccione un docente.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="docente" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Grupo</label>
                        <select id="docente" class="form-select" required name="grupo">
                            <option selected disabled>Selecciona el grupo</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->clave_grupo }}">{{ $grupo->clave_grupo }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Seleccione un grupo.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="nombre_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la práctica</label>
                        <input type="text" class="form-control" name="nombre_practica" required>
                        <div class="invalid-feedback">
                            Ingrese el nombre de la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="objetivo" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
                        <textarea class="form-control" name="objetivo" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Ingrese un objetivo para la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="introduccion" class="form-label"><i class="fas fa-file-alt me-2"></i>Introducción</label>
                        <textarea class="form-control" name="introduccion" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Ingrese una Introducción para la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="fundamento" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
                        <textarea class="form-control" name="fundamento" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Ingrese el fundamento de la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="referencias" class="form-label"><i class="fas fa-bookmark me-2"></i>Referencias</label>
                        <textarea class="form-control" name="referencias" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Ingrese las referencias utilizadas.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="articulos" class="form-label"><i class="fas fa-boxes me-2"></i>Artículos</label>
                        <div class="mb-2">
                            <button type="button" id="select-all" class="btn btn-primary btn-sm">Seleccionar todos</button>
                            <button type="button" id="deselect-all" class="btn btn-secondary btn-sm">Deseleccionar</button>
                        </div>
                        <select class="form-select" multiple="multiple" id="articulos" name="articulos[]">
                            @foreach ($catalogo_articulos as $articulo)
                                <option value="{{ $articulo->id_articulo }}">{{ $articulo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
                @endcan
            </div>
            <script>
                $(document).ready(function () {
                    $('#articulos').select2({
                        placeholder: "Seleccionar articulos"
                    });

                    $('#select-all').click(function () {
                        $('#articulos > option').prop("selected", true);
                        $('#articulos').trigger("change");
                    });

                    $('#deselect-all').click(function () {
                        $('#articulos > option').prop("selected", false);
                        $('#articulos').trigger("change");
                    });
                });
            </script>
@endcan
@endsection

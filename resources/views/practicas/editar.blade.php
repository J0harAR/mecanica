@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-pen me-1"></i> Editar práctica
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
                            <i class="bi bi-journal me-1"></i> Prácticas
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-pen me-1"></i> Editar práctica
                    </li>
                </ol>
            </nav>
            <!-- Vertical Form -->
            <form class="row g-3" action="{{ route('practicas.update', ['id' => $practica->id_practica]) }}"
                method="POST">
                @method('PATCH')
                @csrf
                <div class="col-12">
                    <label for="codigo_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>No. Practica
                    </label>
                    <input type="text" class="form-control" id="codigo_practica" name="codigo_practica"
                        value="{{ $practica->id_practica }}" required>
                </div>

                <div class="col-md-6">
                    <label for="docente" class="form-label"><i
                            class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
                    <select id="docente" class="form-select" required name="docente">
                        <option disabled>Selecciona un docente</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->rfc }}" {{ $practica->docente->rfc === $docente->rfc ? 'selected' : '' }}>
                                {{ $docente->persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                        <label for="docente" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Grupo</label>
                        <select id="docente" class="form-select" required name="grupo">
                            <option selected disabled>Selecciona el grupo</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->clave_grupo }}" {{ $practica->grupo->clave_grupo === $grupo->clave_grupo ? 'selected' : '' }}>
                                    {{$grupo->clave_grupo}}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Seleccione un grupo.
                        </div>
                    </div>

                <div class="col-12">
                    <label for="nombre_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la
                        práctica</label>
                    <input type="text" class="form-control" id="nombre_practica" name="nombre_practica" required
                        value="{{ $practica->nombre }}">
                </div>

                <div class="col-12">
                    <label for="objetivo" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
                    <textarea class="form-control wide-input" id="objetivo" name="objetivo"
                        required>{{ $practica->objetivo }}</textarea>
                </div>

                <div class="col-12">
                    <label for="introduccion" class="form-label"><i
                            class="fas fa-file-alt me-2"></i>Introducción</label>
                    <textarea class="form-control wide-input" id="introduccion" name="introduccion"
                        required>{{ $practica->introduccion }}</textarea>
                </div>

                <div class="col-12">
                    <label for="fundamento" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
                    <textarea class="form-control wide-input" id="fundamento" name="fundamento"
                        required>{{ $practica->fundamento }}</textarea>
                </div>

                <div class="col-12">
                    <label for="referencias" class="form-label"><i class="fas fa-bookmark me-2"></i>Referencias</label>
                    <textarea class="form-control wide-input" id="referencias" name="referencias"
                        required>{{ $practica->referencias }}</textarea>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-12 col-form-label"><i class="fas fa-boxes me-2"></i>Artículos</label>
                    <div class="col-sm-12 mb-2">
                        <button type="button" id="select-all" class="btn btn-primary btn-sm">Seleccionar todos</button>
                        <button type="button" id="deselect-all" class="btn btn-secondary btn-sm">Deseleccionar</button>
                    </div>
                    <div class="col-sm-12">
                        <select class="form-select" multiple="multiple" id="articulos" name="articulos[]">
                            @foreach ($articulos as $articulo)
                                <option value="{{ $articulo->id_articulo }}" {{ $practica->catalogo_articulos->contains('id_articulo', $articulo->id_articulo) ? 'selected' : '' }}>
                                    {{ $articulo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#articulos').select2({
            placeholder: "Selecciona artículos",
            width: '100%'  // para ajustar el ancho
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
@endsection

<style>
    .wide-input {
        width: 100%;
        height: 100px;
    }
</style>
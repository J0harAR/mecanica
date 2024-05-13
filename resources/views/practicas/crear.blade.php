@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Registro de práctica</h2>
    <form class="row g-3 needs-validation" action="{{ route('practicas.store') }}" method="POST" novalidate>
        @csrf

        <div class="col-md-6">
            <label for="codigo_practica" class="form-label">No. Práctica</label>
            <input type="text" class="form-control"  name="codigo_practica" required>
            <div class="invalid-feedback">
                Ingrese el número de la práctica.
            </div>
        </div>

        <div class="col-md-6">
            <label for="docente" class="form-label">Docente</label>
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

        <div class="col-12">
            <label for="nombre_practica" class="form-label">Nombre de la práctica</label>
            <input type="text" class="form-control"  name="nombre_practica" required>
            <div class="invalid-feedback">
                Ingrese el nombre de la práctica.
            </div>
        </div>

        <div class="col-12">
            <label for="objetivo" class="form-label">Objetivo</label>
            <input type="text" class="form-control"  name="objetivo" required>
            <div class="invalid-feedback">
                Ingrese un objetivo para la práctica.
            </div>
        </div>

        <div class="col-12">
            <label for="introduccion" class="form-label">Introducción</label>
            <input type="text" class="form-control" name="introduccion" required>
            <div class="invalid-feedback">
                Ingrese una Introducción para la práctica.
            </div>
        </div>

        <div class="col-12">
            <label for="fundamento" class="form-label">Fundamento</label>
            <input type="text" class="form-control"  name="fundamento" required>
            <div class="invalid-feedback">
                Ingrese el fundamento de la práctica.
            </div>
        </div>

        <div class="col-12">
            <label for="referencias" class="form-label">Referencias</label>
            <input type="text" class="form-control"  name="referencias" required>
            <div class="invalid-feedback">
                Ingrese las referencias utilizadas.
            </div>
        </div>

        <div class="col-md-12">
            <label for="articulos" class="form-label">Artículos</label>
            <select class="form-select" multiple aria-label="multiple select example" id="articulos" name="articulos[]">
                @foreach ($catalogo_articulos as $articulo)
                    <option value="{{ $articulo->id_articulo }}">{{ $articulo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>
@endsection
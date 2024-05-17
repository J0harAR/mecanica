@extends('layouts.app')

@section('content')

<form action="{{ route('practicasAlumno.store') }}" method="post">
    @csrf
    <div class="mb-3">
        <label for="no_control" class="form-label">Número de control</label>
        <input type="text" class="form-control" name="no_control" id="no_control">
    </div>

    <div class="mb-3">
        <label for="practica" class="form-label">Práctica</label>
        <select name="practica" class="form-select" id="practica">
            @foreach ($practicas as $practica)
                <option value="{{ $practica->id_practica }}">{{ $practica->id_practica }} // {{ $practica->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" class="form-control" name="fecha" id="fecha">
    </div>

    <div class="mb-3">
        <label for="no_equipo" class="form-label">Número de equipo</label>
        <input type="number" class="form-control" name="no_equipo" id="no_equipo">
    </div>

    <div class="mb-3">
        <label for="hora_entrada" class="form-label">Hora de entrada</label>
        <input type="time" class="form-control" name="hora_entrada" id="hora_entrada">
    </div>

    <div class="mb-3">
        <label for="hora_salida" class="form-label">Hora de salida</label>
        <input type="time" class="form-control" name="hora_salida" id="hora_salida">
    </div>

    <div class="mb-3">
        <label for="articulos" class="form-label">Artículos</label>
        <select class="form-select" multiple aria-label="multiple select example" name="articulos[]" id="articulos">
            @foreach ($articulos_inventariados as $articulo)
                <option value="{{ $articulo->id_inventario }}">Código: {{ $articulo->id_inventario }} // {{ $articulo->Catalogo_articulos->nombre }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('alumno_no_encontrado'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('alumno_no_encontrado') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@endsection

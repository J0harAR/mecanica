@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="bi bi-file-earmark-text"></i> Registro de práctica (Alumno)
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('practicas.index')}}" class="text-decoration-none text-primary">
                            <i class="bi bi-journal me-1"></i> Prácticas
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-file-earmark-text"></i> Registrar práctica (alumno)
                    </li>
                </ol>
            </nav>
            <div class="container mt-4"></div>
            <form class="row g-3 needs-validation" action="{{ route('practicasAlumno.store') }}" method="post" novalidate>
                @csrf
                <div class="col-md-6">
                    <label for="no_control" class="form-label"><i class="fas fa-id-card" ></i> Número de control</label>
                    <input type="text" class="form-control" name="no_control" id="no_control"required
                                autocomplete="no_control" autofocus>
                                <div class="invalid-feedback">
                            Ingrese el número de control del estudiante.
                        </div>
                </div>
                <div class="col-md-6">
                    <label for="no_equipo" class="form-label"><i class="fas fa-tag" ></i> Número de equipo</label>
                    <input type="number" class="form-control" name="no_equipo" id="no_equipo"required
                                autocomplete="no_equipo" autofocus>
                                <div class="invalid-feedback">
                            Ingrese el número de equipo.
                        </div>
                </div>
                <div class="mb-3">
                    <label for="practica" class="form-label"><i class="bi bi-journal me-1" ></i> Práctica</label>
                    <select name="practica" class="form-select" id="practica"required
                                autocomplete="practica" autofocus>
                                <div class="invalid-feedback">
                        </div>
                        @foreach ($practicas as $practica)
                            <option value="{{ $practica->id_practica }}">{{ $practica->id_practica }} //
                                {{ $practica->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha" class="form-label"><i class="fas fa-calendar-alt" ></i> Fecha</label>
                    <input type="date" class="form-control" name="fecha" id="fecha"required
                                autocomplete="fecha" autofocus>
                                <div class="invalid-feedback">
                            Ingrese la fecha.
                        </div>
                </div>

                

                <div class="col-md-6">
                    <label for="hora_entrada" class="form-label"><i class="fas fa-clock" ></i> Hora de entrada</label>
                    <input type="time" class="form-control" name="hora_entrada" id="hora_entrada"required
                                autocomplete="hora_entrada" autofocus>
                                <div class="invalid-feedback">
                            Ingrese la hora de entrada.
                        </div>
                </div>

                <div class="col-md-6">
                    <label for="hora_salida" class="form-label"><i class="fas fa-clock" ></i> Hora de salida</label>
                    <input type="time" class="form-control" name="hora_salida" id="hora_salida" required
                                autocomplete="hora_salida" autofocus>
                                <div class="invalid-feedback">
                            Ingrese la hora de salida.
                        </div>
                </div>

                <div class="mb-3">
                    <label for="articulos" class="form-label"><i class="fas fa-box" ></i> Artículos</label>
                    <select class="form-select" multiple aria-label="multiple select example" name="articulos[]"
                        id="articulos">
                        @foreach ($articulos_inventariados as $articulo)
                            <option value="{{ $articulo->id_inventario }}">Código: {{ $articulo->id_inventario }} //
                                {{ $articulo->Catalogo_articulos->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
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
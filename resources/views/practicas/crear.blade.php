@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-pen "></i> Registro de práctica
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
                            <i class="fas fa-home me-1"></i> Prácticas
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-pen "></i> Registrar práctica
                    </li>
                </ol>
            </nav>
            <div class="container mt-4">
                <form class="row g-3 needs-validation" action="{{ route('practicas.store') }}" method="POST" novalidate>
                    @csrf
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label for="codigo_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>No.
                            Práctica</label>
                        <input type="text" class="form-control" name="codigo_practica" required>
                        <div class="invalid-feedback">
                            Ingrese el número de la práctica.
                        </div>
                    </div>
                    

                    <div class="col-md-6">
                        <label for="docente" class="form-label"><i
                                class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
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
                        <label for="nombre_practica" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la
                            práctica</label>
                        <input type="text" class="form-control" name="nombre_practica" required>
                        <div class="invalid-feedback">
                            Ingrese el nombre de la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="objetivo" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
                        <input type="text" class="form-control" name="objetivo" required>
                        <div class="invalid-feedback">
                            Ingrese un objetivo para la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="introduccion" class="form-label"><i
                                class="fas fa-file-alt me-2"></i>Introducción</label>
                        <input type="text" class="form-control" name="introduccion" required>
                        <div class="invalid-feedback">
                            Ingrese una Introducción para la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="fundamento" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
                        <input type="text" class="form-control" name="fundamento" required>
                        <div class="invalid-feedback">
                            Ingrese el fundamento de la práctica.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="referencias" class="form-label"><i
                                class="fas fa-bookmark me-2"></i>Referencias</label>
                        <input type="text" class="form-control" name="referencias" required>
                        <div class="invalid-feedback">
                            Ingrese las referencias utilizadas.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="articulos" class="form-label"><i class="fas fa-boxes me-2"></i>Artículos</label>
                        <select class="form-select" multiple aria-label="multiple select example" id="articulos"
                            name="articulos[]">
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
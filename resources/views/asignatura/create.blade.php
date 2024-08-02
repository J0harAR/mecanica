@extends('layouts.app')

@section('content')
@can('crear-asignatura')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-book"></i> Registrar asignatura
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('asignatura.index') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-book me-1"></i> Asignatura
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-plus"></i> Registrar asignatura
                        </li>
                    </ol>
                </nav>
            </div>
            @can('crear-asignatura')
                <a href="{{ route('asignatura.index') }}" class="btn btn-primary shadow">
                    <i class="fas fa-book "></i> Asignaturas 
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    window.setTimeout(function () {
                        const successAlert = document.getElementById("success-alert");
                        if (successAlert) successAlert.style.display = 'none';
                    }, 3000);
                });
            </script>
        @endif

        <div class="card custom-card">
    <div class="card-body">
            <form class="row g-3 needs-validation" action="{{ route('asignatura.store') }}" method="POST" novalidate>
                @csrf
                <div class="col-md-6">
                    <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i>Nombre Completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @else
                <div class="invalid-feedback">
                    Ingrese el nombre completo de la asignatura.
                </div>
            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="clave" class="form-label"><i class="fas fa-key me-2"></i> Clave de Asignatura</label>
                            <input type="text" name="clave" id="clave" class="form-control" required>
                            <div class="invalid-feedback">
                                Ingrese la clave de la asignatura.
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm shadow">
                                <i class="bi bi-check2"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan
@endsection
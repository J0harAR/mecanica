@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users-cog"></i> Administración de Grupos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-users-cog"></i> Administración de Grupos
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrar Grupo
            </a>
        </div>
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
<div class="card custom-card">
      <div class="card-body">
          <div class="card-body">
            <table class="table table-responsive-md data-table">
            <h5 class="fw-bold mb-0 text-primary">Listado de Grupos</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Asignatura</th>
                        <th scope="col">Clave del Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grupos as $grupo)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                @foreach($grupo->asignaturas as $asignatura)
                                    {{ $asignatura->nombre }}
                                @endforeach
                            </td>
                            <td>{{ $grupo->clave }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


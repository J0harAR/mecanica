@extends('layouts.app')

@section('content')
@can('ver-grupos')
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
                @can('crear-grupo')
                    <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Registrar Grupo
                    </a>
                @endcan

            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="success-alert" role="alert">
                {{ session('error') }}
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("success-alert");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
                });
            </script>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger  alert-dismissible fade show"">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type=" button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </ul>
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

        @can('ver-grupos')

            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle"
                            style="border-collapse: separate; border-spacing: 0 10px;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Asignatura</th>
                                    <th scope="col">Clave del Grupo</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grupos as $grupo)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{$grupo->clave_periodo}}</td>
                                        <td>
                                            @if ($grupo->asignatura)
                                                {{$grupo->asignatura->clave}}//{{$grupo->asignatura->nombre}}
                                            @else
                                                Sin Asignatura
                                            @endif
                                            
                                        </td>
                                        <td>{{ $grupo->clave_grupo }}</td>

    
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#updateModal-{{ $grupo->clave_grupo }}">
                                                    <i class="fas fa-edit bt"></i>
                                            </button>

                                            @can('borrar-grupo')
                                                <button type="button" class="btn btn-danger " data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $grupo->clave_grupo }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </td>



                                    </tr>

                                    @can('borrar-grupo')
                                        <!-- Modal de eliminacion -->
                                        <div class="modal fade" id="deleteModal-{{ $grupo->clave_grupo }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas eliminar el grupo
                                                        "{{ $grupo->clave_grupo }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('grupos.destroy', ['id' => $grupo->clave_grupo]) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan


                                 
                                        <!-- Modal de update -->
                                        <div class="modal fade" id="updateModal-{{ $grupo->clave_grupo }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                                    <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil me-2"></i> Asignar asignatura al grupo:{{$grupo->clave_grupo}}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                                                    </div>
                                                    <div class="modal-body">
                                                        

                                                        <form action="{{ route('grupos.update', ['id' => $grupo->clave_grupo]) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                        
                                                            <label for="floatingSelect" class="form-label"><i class="fas fa-book me-2"></i> Asignatura</label>
                                                            <select class="form-select" id="floatingSelect" name="asignatura" required>
                                                                <option selected disabled>Selecciona una asignatura</option>
                                                                @foreach ($asignaturas as $asignatura)
                                                                    <option value="{{ $asignatura->clave }}">{{ $asignatura->clave }} - {{ $asignatura->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Seleccione una asignatura.
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
                                   
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endcan
        </div>
@endcan


<script>
  // JavaScript para ocultar el mensaje de error después de 3 segundos
  document.addEventListener('DOMContentLoaded', function () {
        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function () {
                errorAlert.style.display = 'none';
            }, 3000);
        }
    });
</script>
    @endsection
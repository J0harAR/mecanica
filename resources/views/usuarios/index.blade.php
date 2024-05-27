@extends('layouts.app')

@section('content')
@can('ver-usuarios')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users me-2"></i>Usuarios
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-users me-1"></i>Usuarios
                    </li>
                </ol>
            </nav>
        </div>
        @can('crear-usuarios')
        <a href="{{ route('usuarios.create') }}" class="btn btn-tecnm btn-lg shadow-sm rounded-pill">
            <i class="fas fa-user-plus me-1"></i>Añadir nuevo usuario
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded" role="alert" id="success-alert">
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

    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                        <tr>
                            <th scope="col" class="text-center">Nombre</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Rol</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        @foreach($usuarios as $usuario)
                        <tr class="shadow-sm transition-transform duration-300 hover:scale-105 bg-white">
                            <td class="text-center">{{ $usuario->name }}</td>
                            <td class="text-center">{{ $usuario->email }}</td>
                            <td class="text-center">
                                @if(!empty($usuario->getRoleNames()))
                                @foreach($usuario->getRoleNames() as $rolName)
                                <span class="badge bg-secondary rounded-pill shadow-sm">{{ $rolName }}</span>
                                @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @can('editar-usuarios')
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-outline-primary btn-sm  "><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('borrar-usuarios')
                                <button type="button" class="btn btn-outline-danger btn-sm  " data-bs-toggle="modal" data-bs-target="#modal-{{ $usuario->id }}"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modal-{{ $usuario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de querer eliminar el usuario: {{ $usuario->name }}?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-secondary rounded-pill shadow-sm" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger rounded-pill shadow-sm">Eliminar</button>
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
</div>
@endcan
@endsection


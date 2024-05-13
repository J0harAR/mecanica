@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0">Prácticas</h1>
    <a href="{{ route('practicas.create') }}" class="btn btn-outline-primary btn-sm"><i class="ri-add-line"></i> Registrar práctica</a>
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
<div class="container">
    @if($practicas->isEmpty())
        <div class="alert alert-secondary" role="alert">
            No hay prácticas registradas actualmente. <a href="{{ route('practicas.create') }}" class="alert-link">¡Crea una nueva práctica!</a>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($practicas as $practica)
                    <tr>
                        <td>{{ $practica->id_practica }}</td>
                        <td>{{ $practica->nombre }}</td>
                        <td>
                            <a href="{{ route('practicas.show', ['id' => $practica->id_practica]) }}" 
                            class="btn btn-outline-danger btn-sm"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('practicas.edit', ['id' => $practica->id_practica]) }}" 
                            class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                            
                            <!-- Botón para abrir el modal de confirmación -->
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $practica->id_practica }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <!-- Modal de Confirmación -->
                            <div class="modal fade" id="deleteModal-{{ $practica->id_practica }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que deseas eliminar la práctica "{{ $practica->nombre }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('practicas.destroy', ['id' => $practica->id_practica]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('content')
@can('ver-usuarios')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold mb-0">Usuarios</h1>
    @can('crear-usuarios')
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Añadir nuevo
      usuario</a>
    @endcan
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
  <div class="card shadow">
    <div class="card-body">
      <table class="table table-responsive-md table-hover">
        <thead class="thead-light">
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Rol</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usuarios as $usuario)
          <tr>
            <td>{{$usuario->name}}</td>
            <td>{{$usuario->email}}</td>
            <td>
              @if(!empty($usuario->getRoleNames()))
              @foreach($usuario->getRoleNames() as $rolName)
              <h6><span>{{$rolName}}</span></h6>
              @endforeach
              @endif
            </td>
            <td>
              @can('editar-usuarios')
              <a href="{{ route('usuarios.edit',$usuario->id) }}" class="btn btn-outline-primary btn-sm"><i
                  class="fas fa-edit"></i></a>
              @endcan

              @can('borrar-usuarios')
              <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                data-bs-target="#modal-{{ $usuario->id}}"><i class="fas fa-trash"></i></button>
              @endcan
            </td>
          </tr>

          <!-- Modal -->
          <div class="modal fade" id="modal-{{ $usuario->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  ¿Estás seguro de querer eliminar el usuario: {{$usuario->name}}?
                </div>
                <div class="modal-footer">
                  <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
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
@endsection
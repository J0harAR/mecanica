@extends('layouts.app')

@section('content')
@can('ver-rol')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Roles</h1>
        @can('crear-rol')
        <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir nuevo rol</a>
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
            <th scope="col">Rol</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
<tr>
    <td>{{ $role->name }}</td>
    <td>
      @can('editar-rol')
      <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
      @endcan

      @can('borrar-rol')
      <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{ $role->id }}"><i class="fas fa-trash-alt"></i></button>
      @endcan
    </td>
</tr>


 <!-- Modal Structure -->
 <div class="modal fade" id="modal-{{ $role->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $role->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $role->id }}">Confirmación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de querer eliminar el rol: {{ $role->name }}?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
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
    </div>
</div>
@endcan
@endsection
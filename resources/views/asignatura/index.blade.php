@extends('layouts.app')

@section('content')
@can('ver-asignaturas')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fw-bold mb-0 text-primary">
          <i class="fas fa-book"></i> Datos de la Asignatura
        </h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
            <li class="breadcrumb-item">
              <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                <i class="fas fa-home me-1"></i> Dashboard
              </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              <i class="fas fa-book me-1"></i> Asignaturas
            </li>
          </ol>
        </nav>
      </div>
      @can('crear-asignaturas')
      <a href="{{ route('asignatura.create') }}" class="btn btn-primary shadow">
        <i class="fas fa-plus"></i> Nueva Asignatura
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
        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <table class="table table-responsive-md table-hover data-table">
              <thead class="bg-primary text-white">
                <tr>
                  <th scope="col">Clave</th>
                  <th scope="col">Nombre Completo</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($asignaturas as $asignatura)
                <tr class="text-center">
                  <td scope="row">{{ $asignatura->clave }}</td>
                  <td>{{ $asignatura->nombre }}</td>
                  <td>
                    @can('editar-asignaturas')
                    <a href="{{ route('asignatura.edit', ['id' => $asignatura->clave]) }}"
                       class="btn btn-outline-primary btn-sm">
                      <i class="fas fa-edit mr-2"></i>
                    </a>
                    @endcan

                    @can('borrar-asignaturas')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-asignatura="{{ $asignatura->clave }}">
                      <i class="fas fa-trash mr-2"></i>
                    </button>
                    @endcan
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que quieres eliminar la asignatura seleccionada?
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var asignaturaClave = button.getAttribute('data-asignatura');
      var form = deleteModal.querySelector('#deleteForm');
      form.action = '/asignaturas/' ;
    });
  });
</script>


    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          var asignaturaClave = button.getAttribute('data-asignatura');
          var form = deleteModal.querySelector('#deleteForm');
          form.action = '/asignaturas/' + asignaturaClave;
        });
      });
    </script>

  </div>
@endcan
@endsection

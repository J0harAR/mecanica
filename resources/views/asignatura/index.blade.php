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
      @can('crear-asignatura')
      <button type="button" class="btn btn-primary"
             data-bs-toggle="modal" data-bs-target="#createModal">
             <i class="fas fa-user-plus"></i> Registrar Asignatura
       </button>

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

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
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
                <tr>
                  <td scope="row">{{ $asignatura->clave }}</td>
                  <td>{{ $asignatura->nombre }}</td>
                  <td>
                    @can('editar-asignatura')
                    <button type="button" class="btn btn-primary " data-bs-toggle="modal"
                            data-bs-target="#editModal-{{$asignatura->clave}}" data-asignatura="{{ $asignatura->clave }}">
                            <i class="fas fa-edit bt"></i>
                    </button>
                    
                    @endcan

                    @can('borrar-asignatura')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-asignatura="{{ $asignatura->clave }}">
                      <i class="fas fa-trash mr-2"></i>
                    </button>
                    @endcan
                  </td>
                </tr>
                                 

                @can('editar-asignatura')
<!-- Modal de Editar -->
<div class="modal fade" id="editModal-{{$asignatura->clave}}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil me-2"></i> Editar Asignatura</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('asignatura.update', ['id' => $asignatura->clave]) }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    
                    <div class="col-md-12">
                        <label for="nombre" class="form-label"><i class="fas fa-book me-2"></i> Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ $asignatura->nombre }}" required>
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

                    <div class="col-md-12">
                        <label for="clave" class="form-label"><i class="fas fa-key me-2"></i> Clave de Asignatura</label>
                        <input type="text" name="clave" id="clave" class="form-control" value="{{ $asignatura->clave }}" required>
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




                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
                                @can('crear-asignatura')
                                  <!-- Modal de create -->
                                    <div class="modal fade" id="createModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                                    <h5 class="modal-title" id="exampleModalLabel"><i
                                                            class="bi bi-person-plus me-2"></i>Crear asignatura</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                <form class="row g-3 needs-validation" action="{{ route('asignatura.store') }}" method="POST" novalidate>
                                                      @csrf
                                                      <div class="col-md-12">
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

                                                              <div class="col-md-12">
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

@can('borrar-asignatura')
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
@endcan

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

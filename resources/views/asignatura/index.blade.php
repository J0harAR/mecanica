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

       @include('asignatura.modals.create')

      @endcan
    </div>


       @include('layouts.notificaciones') 

   

  
        <div class="card shadow-lg rounded-3 border-0">
          <div class="card-body py-4">
            <table class="table datatable">
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
                    <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
                            data-bs-target="#editModal-{{$asignatura->clave}}" data-asignatura="{{ $asignatura->clave }}">
                            <i class="fas fa-edit bt"></i>
                    </button>
                    
                    @endcan

                    @can('borrar-asignatura')
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-asignatura="{{ $asignatura->clave }}">
                      <i class="fas fa-trash mr-2"></i>
                    </button>
                    @endcan
                  </td>
                </tr>
                                 
              @include('asignatura.modals.edit')
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
  
    
            @include('asignatura.modals.delete')

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

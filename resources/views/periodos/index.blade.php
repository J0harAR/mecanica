@extends('layouts.app')


@section('content')

@can('ver-periodos')
    

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users"></i> Periodos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('grupos.index')}}" class="text-decoration-none text-primary">
                            <i class="fas fa-users me-1"></i> Cursos
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-plus"></i> Periodos
                    </li>
                </ol>
            </nav>
        </div>
  
<div>
    @can('crear-periodo')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
      <i class="ri-add-line"></i> Añadir periodo
    </button>
    @endcan

</div>
</div>
    @if (session('success'))
        <div class="alert alert-success" id="error-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

     @if ($errors->any())
    <div class="alert alert-danger" id="error-alert">
        Todos los campos son requeridos  
    </div>
    @endif

@can('crear-periodo')     
<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar periodo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" action="{{ route('periodos.store') }}" method="POST">
            @csrf
            <div class="col-md-12 mb-3">
              <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>Clave del periodo</label>
              <input type="text" class="form-control" name="periodo" id="periodo" pattern="^\d{4}-\d{1}$"  required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="fecha_inicio" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha de inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_final" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha final</label>
                <input type="date" class="form-control" name="fecha_final" id="fecha_final" >
            </div>


            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Vertically centered Modal -->
  @endcan






@can('ver-periodos')
    
    <div class="card">
      <div class="card-body">
          <div class="card-body">
            <table class="table table-responsive-md data-table">
            <h5 class="fw-bold mb-0 text-primary mt-5">Listado de periodos</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th >Clave de periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $periodo)
                        <tr>
                            <td>{{$periodo->clave}} <br>  
                            {{mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_inicio)->locale('es')->isoFormat('MMMM')) }} -
                            {{ mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_final)->locale('es')->isoFormat('MMMM')) }}</td>
                            <td>
                                @can('borrar-periodo')                                                             
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $periodo->clave}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan

                                @can('editar-periodo')                                                                 
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{$periodo->clave}}">
                                    <i class="bi bi-pencil-square "></i>
                                </button>
                                @endcan                          
                            </td>
                        </tr>
@can('editar-periodo')
                                            
   <!-- Vertically centered Modal -->
<div class="modal fade" id="modal-{{$periodo->clave}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar periodo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('periodos.update', ['id' => $periodo->clave]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-12 mb-3">
                        <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>Clave del periodo</label>
                        <input type="text" class="form-control" name="periodo" id="periodo" value="{{$periodo->clave}}" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha de inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required value="{{$periodo->fecha_inicio}}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_final" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha final</label>
                            <input type="date" class="form-control" name="fecha_final" id="fecha_final"  required value="{{$periodo->fecha_final}}">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Vertically centered Modal -->
@endcan

@can('borrar-periodo') 
<!-- Modal Eliminar -->
<div class="modal fade" id="modal-delete{{ $periodo->clave }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Estás seguro de querer eliminar el periodo: {{$periodo->clave}}?
            </div>
            <div class="modal-footer">
              <form action="{{route('periodos.destroy',['id'=>$periodo->clave])}}" method="POST">
                @csrf
                @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Eliminar -->
      @endcan             
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endcan
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
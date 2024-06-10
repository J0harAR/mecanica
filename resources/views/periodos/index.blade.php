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
    </div>



    @can('crear-periodo')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
      <i class="ri-add-line"></i> Añadir periodo
    </button>
    @endcan


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
            <div class="col-md-12mb-3">
              <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>Clave del periodo</label>
              <input type="text" class="form-control" name="periodo" id="periodo">
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
    
    <div class="card custom-card">
      <div class="card-body">
          <div class="card-body">
            <table class="table table-responsive-md data-table">
            <h5 class="fw-bold mb-0 text-primary">Listado de periodos</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th >Clave de periodo</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $periodo)
                        <tr>
                            <td>{{$periodo->clave}}</td>
                            <td>
                                @can('borrar-periodo')                                                             
                                <form action="{{route('periodos.destroy',['id'=>$periodo->clave])}}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                <button  class="btn btn-outline-danger btn-sm">Eliminar</button>
                                </form>
                                @endcan
                            
                            </td>
                        </tr>
                  
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endcan
@endcan
@endsection
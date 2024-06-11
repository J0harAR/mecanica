@extends('layouts.app')

@section('content')

@can('ver-prestamos')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-tools"></i> Préstamos
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-tools"></i> Préstamos
          </li>
        </ol>
      </nav>
    </div>
    <div>
    
    @can('crear-prestamo')        
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
      <i class="ri-add-line"></i> Añadir préstamo
    </button>
    @endcan
    @can('generar_reporte_prestamo')
    <a class="btn btn btn-tecnm" href="{{route('reporte.prestamo')}}"><i class="bi bi-download"></i></a>
    @endcan
    </div>
    
  </div>


  @if (session('docente_no_encontrado')) 
  <div class="alert alert-danger" id="danger-alert">
    {{ session('docente_no_encontrado') }}
  </div>
  @endif

  @if (session('herramienta_no_disponible'))
  <div class="alert alert-danger" id="danger-alert">
    {{ session('herramienta_no_disponible') }}
  </div> 
  @endif

  @if (session('herramienta_no_encontrada'))
  <div class="alert alert-danger" id="danger-alert">
    {{ session('herramienta_no_encontrada') }}
  </div>
  @endif

  @if (session('success'))
  <div class="alert alert-success" id="success-alert">
    {{ session('success') }}
  </div>
  @endif

  @if (session('error'))
  <div class="alert alert-danger" id="success-alert">
    {{ session('error') }}
  </div>
  @endif


  @can('crear-prestamo')
  <!-- Vertically centered Modal -->
  <div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar préstamo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" action="{{ route('prestamos.store') }}" method="POST">
            @csrf
            <div class="col-md-6 mb-3">
              <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>RFC del docente</label>
              <input type="text" class="form-control" name="rfc" id="rfc">
            </div>
            <div class="col-md-6 mb-3">
              <label for="herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione herramienta</label>
              <select class="form-select" aria-label="Seleccione una herramienta" name="herramienta" id="herramienta">
                <option selected disabled>Seleccione la herramienta</option>
                @foreach ($herramientas as $herramienta)
                <option value="{{ $herramienta->id_herramientas }}">
                  ID: {{ $herramienta->id_herramientas }} - {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del préstamo</label>
              <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo">
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha_devolucion" class="form-label"><i class="bi bi-calendar-check me-2"></i>Fecha de devolución</label>
              <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion">
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
  @can('ver-prestamos')
    
  <div class="card shadow-lg rounded-3 border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table
                                class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle"
                                style="border-collapse: separate; border-spacing: 0 10px;">
                                <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                                    <tr>
        <th>RFC del Docente</th>
        <th>Nombre</th>
        <th>Fecha de Préstamo</th>
        <th>Fecha de Devolución</th>
        <th>Herramienta</th>
        <th>Acciones</th>
        <th>Estatus</th>
        
      </tr>
    </thead>
    <tbody>
      @foreach ($prestamos as $docente)
      @foreach ($docente->herramientas as $prestamo)
      <tr>
        <td>{{ $docente->rfc }}</td>
        <td>{{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }} {{ $docente->persona->apellido_m }}</td>
        <td>{{ $prestamo->pivot->fecha_prestamo }}</td>
        <td>{{ $prestamo->pivot->fecha_devolucion }}</td>
        <td>{{ $prestamo->Articulo_inventariados->Catalogo_articulos->nombre }}</td>
        <td>

        @if ($prestamo->pivot->estatus == "Pendiente")
          @can('editar-prestamo')
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update-{{ $prestamo->pivot->id }}">
            <i class="fas fa-pencil-alt"></i>
          </button>
          @endcan
        @endif
          @can('borrar-prestamo')       
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $prestamo->pivot->id }}">
            <i class="fas fa-trash"></i>
          </button>
          @endcan

          @if ($prestamo->pivot->estatus == "Pendiente")
          @can('finalizar-prestamo')                    
         <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-finalizar{{ $prestamo->pivot->id }}">
            <i class="fas fa-check"></i>
          </button>
          @endcan
          @else
          <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-finalizar{{ $prestamo->pivot->id }}"disabled>
            <i class="fas fa-check"></i>
          </button>
          @endif
          
        
          

        </td>
        <td>{{ $prestamo->pivot->estatus }}</td>

      </tr>

      @can('finalizar-prestamo')
      <!-- Modal Finalizar -->
      <div class="modal fade" id="modal-finalizar{{ $prestamo->pivot->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Estás seguro de finalizar el préstamo: {{ $prestamo->pivot->id }}?
            </div>
            <div class="modal-footer">
              <form action="{{ route('prestamos.finalizar', ['id' => $prestamo->pivot->id]) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Confirmar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Finalizar -->
      @endcan
     
      @can('editar-prestamo')      
      <!-- Modal Actualizar -->
      <div class="modal fade" id="modal-update-{{ $prestamo->pivot->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
              <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Actualizar préstamo</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="row g-3" action="{{ route('prestamos.update', ['id' => $prestamo->pivot->id]) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="col-md-12 mb-3">
                  <label for="id_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>ID del préstamo</label>
                  <input type="text" class="form-control" name="id_prestamo" id="id_prestamo" value="{{ $prestamo->pivot->id }}" disabled>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>RFC del docente</label>
                    <input type="text" class="form-control" name="rfc" id="rfc" value="{{ $docente->rfc }}" readonly>
                  </div>

                    <div class="col-md-6 mb-3">
                    <label for="herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione herramienta</label>
                    <select class="form-select" aria-label="Seleccione una herramienta" name="herramienta" id="herramienta" disabled>
                      <option selected>Seleccione la herramienta</option>
                      @foreach ($herramientas as $herramienta)
                      <option value="{{ $herramienta->id_herramientas }}" @if ($prestamo->id_herramientas == $herramienta->id_herramientas) selected @endif>
                        {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
                      </option>
                      @endforeach
                    </select>
                  </div>

                </div>

                <div class ="row">
                    <div class="col-md-6 mb-3">
                      <label for="fecha_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del préstamo</label>
                      <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo" value="{{ $prestamo->pivot->fecha_prestamo }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="fecha_devolucion" class="form-label"><i class="bi bi-calendar-check me-2"></i>Fecha de devolución</label>
                      <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion" value="{{ $prestamo->pivot->fecha_devolucion }}">
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
      <!-- End Modal Actualizar -->
      @endcan


      @can('borrar-prestamo')     
      <!-- Modal Eliminar -->
      <div class="modal fade" id="modal-delete{{ $prestamo->pivot->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Estás seguro de querer eliminar el registro: {{ $prestamo->pivot->id }}?
            </div>
            <div class="modal-footer">
              <form action="{{ route('prestamos.destroy', ['id' => $prestamo->pivot->id]) }}" method="POST">
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
      @endforeach
    </tbody>
  </table>
</div>
@endcan
</div>
@endcan
@endsection

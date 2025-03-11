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
      <button class="btn btn btn-tecnm" type="button" data-bs-toggle="modal" data-bs-target="#modal-download">
                <i class="bi bi-download"></i>
      </button>
      @endcan
    </div>

    </div>


    @can('generar_reporte_prestamo')
    <!-- Modal -->
    <div class="modal fade" id="modal-download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reporte de prestamos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reporte.prestamo') }}" method="POST" target="_blank">
                    @csrf
                    <div class="modal-body">
                        <label>Seleciona el periodo</label>

                        <select name="periodo" class="form-select">
                            @foreach ($periodos as $periodo)
                                <option value="{{$periodo->clave}}">{{$periodo->clave}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Descargar</button>
                    </div>
                </form>

            </div>
        </div>
    </div><!-- End Modal -->
    @endcan


    @can('crear-prestamo')
    <!-- Vertically centered Modal -->
    <!-- Modal para registrar préstamo -->
    <div class="modal fade @if($errors->any()) show @endif" id="modal" tabindex="-1" @if($errors->any()) style="display:block;" @endif>
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header" style="background-color: #002855; color: #ffffff;">
            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar préstamo</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

      <div class="modal-body">
      <!-- Mostrar mensajes de error -->

            <form class="row g-3 miFormulario" action="{{ route('prestamos.store') }}" method="POST" #modal-finalizaronsubmit="return validateDates()">
            @csrf
            <div class="col-md-6 mb-3">
              <label for="docente" class="form-label"><i class="bi bi-person"></i> Docente</label>
              <select class="form-select" name="rfc" id="docente" required oninput="clearError('rfcError')">
              <option selected disabled>Seleccione un docente</option>
              @foreach ($docentes as $docente)
            <option value="{{ $docente->rfc }}" @if(old('rfc') == $docente->rfc) selected @endif>
            {{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }} {{ $docente->persona->apellido_m }}
            </option >
          @endforeach
              </select >
              @error('rfc')
            <div id="rfcError" class="text-danger mt-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione herramienta</label>
              <select class="form-select" name="herramienta" id="herramienta" required oninput="clearError('herramientaError')">
              <option selected disabled>Seleccione la herramienta</option>
              @foreach ($herramientas as $herramienta)
            <option value="{{ $herramienta->id_inventario }}" @if(old('herramienta') == $herramienta->id_inventario) selected @endif>
            ID: {{ $herramienta->id_inventario }} - {{ $herramienta->Catalogo_articulos->nombre }}
            </option>
          @endforeach
              </select>
              @error('herramienta')
            <div id="herramientaError" class="text-danger mt-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del
              préstamo</label>
              <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo"
              value="{{ old('fecha_prestamo') }}" required oninput="validateDates()">
              @error('fecha_prestamo')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha_devolucion" class="form-label"><i class="bi bi-calendar-check me-2"></i>Fecha de
              devolución</label>
              <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion"
              value="{{ old('fecha_devolucion') }}" required oninput="validateDates()">
              <div id="dateError" class="text-danger mt-1" style="display: none;">

              </div>
            </div>
            <div class="text-center mt-4">
              <button type="button" class="btn btn-secondary" onclick="resetForm()">Limpiar</button>
              <button type="submit" class="btn btn-primary"
              style="background-color: #002855; border-color: #002855;">Guardar</button>
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
              @foreach ($prestamos as $prestamo)
            
              <tr>
              <td>{{ $prestamo->id_docente }}</td>
              <td>{{ $prestamo->docente->persona->nombre}} {{ $prestamo->docente->persona->apellido_p }} {{ $prestamo->docente->persona->apellido_m }}</td>
              <td>{{ $prestamo->fecha_prestamo }}</td>
              <td>{{ $prestamo->fecha_devolucion }}</td>
              <td>{{ $prestamo->id_herramientas }}</td>
              <td>

              @if ($prestamo->estatus == "Pendiente")
                  @can('editar-prestamo')
                  <button type="button" class="btn btn-outline btn-tecnm btn-sm" data-bs-toggle="modal"
                  data-bs-target="#modal-update-{{ $prestamo->id }}">
                  <i class="fas fa-pencil-alt"></i>
                  </button>
                @endcan
              @endif
        
          

              @if ($prestamo->estatus == "Pendiente")
              @can('finalizar-prestamo')
              <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
              data-bs-target="#modal-finalizar{{ $prestamo->id }}">
              <i class="fas fa-check"></i>
              </button>
            @endcan
            @else
              <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
              data-bs-target="#modal-finalizar{{ $prestamo->id }}" disabled>
              <i class="fas fa-check"></i>
              </button>
            @endif




              </td>
              <td>{{ $prestamo->estatus }}</td>

              </tr>

              @can('finalizar-prestamo')
              <!-- Modal Finalizar -->
              <div class="modal fade" id="modal-finalizar{{ $prestamo->id }}" tabindex="-1"
              aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              ¿Estás seguro de finalizar el préstamo: {{ $prestamo->id }}?
              </div>
              <div class="modal-footer">
              <form action="{{ route('prestamos.finalizar', ['id' => $prestamo->id]) }}" method="POST">
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
              <div class="modal fade" id="modal-update-{{ $prestamo->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content border-0 shadow-lg">
              <div class="modal-header" style="background-color: #002855; color: #ffffff;">
              <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Actualizar préstamo</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <form class="row g-3" action="{{ route('prestamos.update', ['id' => $prestamo->id]) }}"
              method="POST">
              @method('PUT')
              @csrf
              <div class="col-md-12 mb-3">
              <label for="id_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>ID del
              préstamo</label>
              <input type="text" class="form-control" name="id_prestamo" id="id_prestamo"
              value="{{ $prestamo->id }}" disabled>
              </div>
              <div class="row">
              <div class="col-md-6 mb-3">
              <label for="rfc" class="form-label"><i class="bi bi-calendar me-2"></i>RFC del docente</label>
              <input type="text" class="form-control" name="rfc" id="rfc" value="{{ $docente->rfc }}"
              readonly>
              </div>

              <div class="col-md-6 mb-3">
              <label for="herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione
              herramienta</label>
              <select class="form-select" aria-label="Seleccione una herramienta" name="herramienta"
              id="herramienta" disabled>
              <option selected>Seleccione la herramienta</option>
              @foreach ($herramientas as $herramienta)
              <option value="{{ $herramienta->id_herramientas }}" @if ($prestamo->id_herramientas == $herramienta->id_herramientas) selected @endif>
              {{ $herramienta->id_herramienta }}
              </option>
            @endforeach
              </select>
              </div>

              </div>

              <div class="row">
              <div class="col-md-6 mb-3">
              <label for="fecha_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha del
              préstamo</label>
              <input type="date" class="form-control" name="fecha_prestamo" id="fecha_prestamo"
              value="{{ $prestamo->fecha_prestamo }}" disabled>
              </div>
              <div class="col-md-6 mb-3">
              <label for="fecha_devolucion" class="form-label"><i
              class="bi bi-calendar-check me-2"></i>Fecha de devolución</label>
              <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion"
              value="{{ $prestamo->fecha_devolucion }}">
              </div>
              </div>
              <div class="text-center mt-4">
              <button type="button" class="btn btn-secondary" onclick="resetForm()">Limpiar</button>
              <button type="submit" class="btn btn-primary"
              style="background-color: #002855; border-color: #002855;">Guardar</button>
              </div>

              
              </form>
              </div>
              </div>
              </div>
              </div>
              <!-- End Modal Actualizar -->
            @endcan
            
            @endforeach
          
        </tbody>
      </table>
      </div>
    @endcan
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
      @if($errors->any())
      var myModal = new bootstrap.Modal(document.getElementById('modal'), {
      keyboard: false
      });
      myModal.show();
    @endif
      });
    </script>

    <script>
      function validateDates() {
      const fechaPrestamo = document.getElementById('fecha_prestamo').value;
      const fechaDevolucion = document.getElementById('fecha_devolucion').value;
      const dateError = document.getElementById('dateError');

      if (new Date(fechaDevolucion) < new Date(fechaPrestamo)) {
        dateError.innerText = "La fecha de devolución debe ser posterior o igual a la fecha del préstamo.";
        dateError.style.display = 'block';
        return false;
      } else {
        dateError.style.display = 'none'; // Ocultar el mensaje de error si la fecha es válida
        dateError.innerText = ''; // Limpiar el mensaje de error
        return true;
      }
      }
    </script>

    <script>
      function clearError(errorId) {
      document.getElementById(errorId).style.display = 'none';
      }
    </script>

    <script>
      function resetForm() {
      // Obtén el formulario por su clase o ID
      const form = document.querySelector('.miFormulario');

      // Restablece todos los campos del formulario
      form.reset();

      // También puedes ocultar mensajes de error si es necesario
      document.getElementById('dateError').style.display = 'none';
      document.getElementById('dateError').innerText = '';
      }
    </script>


    <script>

      var formularios = document.querySelectorAll('.miFormulario');
      formularios.forEach(function (formulario) {
      formulario.addEventListener('submit', function (event) {
        var boton = formulario.querySelector('.miBoton');
        boton.disabled = true;
      });
      });
    </script>



@endcan
@endsection
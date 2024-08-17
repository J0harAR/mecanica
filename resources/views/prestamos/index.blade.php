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

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
      {{ session('success') }}
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
        <div class="alert alert-danger alert-dismissible fade show" id="success-alert" role="alert">
                {{ session('error') }}
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                window.setTimeout(function () {
                    const successAlert = document.getElementById("success-alert");
                    if (successAlert) successAlert.style.display = 'none';
                }, 3000);
                });
            </script>
        </div>
    @endif


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
      <option value="{{ $herramienta->id_herramientas }}" @if(old('herramienta') == $herramienta->id_herramientas) selected @endif>
      ID: {{ $herramienta->id_herramientas }} - {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
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
          <button type="button" class="btn btn-outline btn-tecnm btn-sm" data-bs-toggle="modal"
          data-bs-target="#modal-update-{{ $prestamo->pivot->id }}">
          <i class="fas fa-pencil-alt"></i>
          </button>
        @endcan
      @endif
 
  

      @if ($prestamo->pivot->estatus == "Pendiente")
      @can('finalizar-prestamo')
      <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
      data-bs-target="#modal-finalizar{{ $prestamo->pivot->id }}">
      <i class="fas fa-check"></i>
      </button>
    @endcan
    @else
      <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
      data-bs-target="#modal-finalizar{{ $prestamo->pivot->id }}" disabled>
      <i class="fas fa-check"></i>
      </button>
    @endif




      </td>
      <td>{{ $prestamo->pivot->estatus }}</td>

      </tr>

      @can('finalizar-prestamo')
      <!-- Modal Finalizar -->
      <div class="modal fade" id="modal-finalizar{{ $prestamo->pivot->id }}" tabindex="-1"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
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
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
      aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form class="row g-3" action="{{ route('prestamos.update', ['id' => $prestamo->pivot->id]) }}"
      method="POST">
      @method('PUT')
      @csrf
      <div class="col-md-12 mb-3">
      <label for="id_prestamo" class="form-label"><i class="bi bi-calendar me-2"></i>ID del
      préstamo</label>
      <input type="text" class="form-control" name="id_prestamo" id="id_prestamo"
      value="{{ $prestamo->pivot->id }}" disabled>
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
      {{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}
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
      value="{{ $prestamo->pivot->fecha_prestamo }}" disabled>
      </div>
      <div class="col-md-6 mb-3">
      <label for="fecha_devolucion" class="form-label"><i
      class="bi bi-calendar-check me-2"></i>Fecha de devolución</label>
      <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion"
      value="{{ $prestamo->pivot->fecha_devolucion }}">
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
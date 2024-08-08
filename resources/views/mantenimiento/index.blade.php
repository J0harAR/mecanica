@extends('layouts.app')

@section('content')
@can('ver-mantenimientos')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-tools "></i> Mantenimiento
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-tools "></i> Mantenimiento
          </li>
        </ol>
      </nav>
    </div>
    <div class="btn-group" role="group">
      @can('crear-mantenimiento')
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
        <i class="ri-add-line"></i> Añadir
      </button>
      @endcan
      
      @can('ver-insumos')
      <a href="{{ route('insumos.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-droplet"></i> Insumos
      </a>
      @endcan

      @can('ver-maquinarias')
      <a href="{{ route('maquinaria.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-gear"></i> Maquinaria
      </a>
      @endcan
    </div>
  </div>

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
  <div class="alert alert-danger" id="danger-alert">
    {{ session('error') }}
  </div>
@endif

  @if (session('errores_cantidad'))
  @foreach (session('errores_cantidad') as $error)
  <div class="alert alert-danger" id="danger-alert">
    {{$error}}
  </div>
@endforeach

@endif

@can('crear-mantenimiento')
  <!-- Vertically centered Modal -->
  <div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar mantenimiento</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3 miFormulario" action="{{ route('mantenimiento.store') }}" method="POST">
            @csrf
            <div class="col-md-6 mb-3">
              <label for="maquina" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione máquina</label>
              <select class="form-select" aria-label="Seleccione una máquina" name="maquina" id="maquina">
                <option selected disabled>Seleccione una máquina</option>
                @foreach ($maquinarias as $maquinaria)
          <option value="{{ $maquinaria->id_maquinaria }}">
          ID: {{ $maquinaria->id_maquinaria }} -
          {{ $maquinaria->Articulo_inventariados->Catalogo_articulos->nombre }}
          </option>
        @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Fecha</label>
              <input type="date" class="form-control" name="fecha" id="fecha">
            </div>
            <div class="col-md-12 mb-3">
              <label for="insumos" class="form-label"><i class="bi bi-box-seam me-2"></i>Insumos</label>
              <div id ="insumos-container">


              </div>
              <!-- @foreach ($insumos as $insumo)
        <div class="row align-items-center mb-2">
          <div class="col-md-5">
          <input type="checkbox" name="{{ $insumo->id_insumo }}" data-id="{{ $insumo->id_insumo }}"
            class="insumo-enable me-2">
          <span> {{$insumo->id_insumo}} {{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}</span>
          </div>
          <div class="col-md-7">
          <div class="input-group">
            <input type="text" name="insumos[{{ $insumo->id_insumo }}]" placeholder="Cantidad"
            data-id="{{ $insumo->id_insumo }}" class="insumo-cantidad form-control" disabled>
            <span class="input-group-text">Litros</span>
          </div>
          </div>
        </div>
      @endforeach -->
     


            </div>
            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary miBoton"
                style="background-color: #002855; border-color: #002855;">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Vertically centered Modal -->
  @endcan

@can('ver-mantenimientos')
  <div class="card shadow-lg rounded-3 border-0">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle"
          style="border-collapse: separate; border-spacing: 0 10px;">
          <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
            <tr>
              <th>N.Mantenimiento</th>
              <th>Máquina</th>
              <th>Insumos Utilizados</th>
              <th data-type="date" data-format="YYYY/DD/MM">Fecha</th>
              <th>Borrar</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($mantenimientos as $mantenimiento)
        <tr>
        <td>{{$mantenimiento->id}}</td>
        <td>
          {{$mantenimiento->Maquinarias->id_maquinaria}}/{{$mantenimiento->Maquinarias->Articulo_inventariados->Catalogo_articulos->nombre}}
        </td>
        <td>

          <button type="button" class="btn btn-outline-primary btn-sm  "
          data-bs-toggle="modal" data-bs-target="#modal-{{ $mantenimiento->id }}"
          style="border-color: #002855; color: #002855;">
          <i class="bi bi-droplet"></i>
          </button>



        </td>
        <td>{{$mantenimiento->fecha}}</td>
        <td class="text-center">
          @can('borrar-mantenimiento')             
          <button type="button" class="btn btn-outline-danger btn-sm btn-sm  "
          data-bs-toggle="modal" data-bs-target="#modal-delete{{ $mantenimiento->id}}"><i
            class="fas fa-trash"></i></button>
           @endcan
        </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="modal-{{ $mantenimiento->id }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow-lg">
          <div class="modal-header" style="background-color: #002855; color: #ffffff;">
            <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-list-check me-2"></i>Insumos
            utilizados
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
            aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
            @foreach ($mantenimiento->insumos as $insumo)
        <div class="row align-items-center mb-2">
        <div class="col-md-6">
        {{$insumo->id_insumo}}/{{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}
        </div>
        <div class="col-md-4">
        <input type="text" name="insumos[{{ $insumo->id_insumo }}]" class="form-control"
          value="{{ $insumo->pivot->cantidad }}" disabled>
        </div>
        <div class="col-md-2">
        <p class="mb-0">Mililitros</p>
        </div>
        </div>
      @endforeach
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
          </div>
        </div>
        </div>
        <!-- End Modal -->

      @can('borrar-mantenimiento')
        <!-- Modal -->
        <div class="modal fade" id="modal-delete{{ $mantenimiento->id}}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de querer eliminar el registro : {{$mantenimiento->id}}?
          </div>
          <div class="modal-footer">
            <form action="{{ route('mantenimiento.destroy', $mantenimiento->id) }}" method="POST">
            @csrf
            @method('delete')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </div>
          </div>
        </div>
        </div><!-- End Modal -->
      @endcan

      @endforeach
          </tbody>

        </table>
      </div>
    </div>
  </div>
  @endcan
  @endcan
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
        $('#maquina').on('change', function () {
            let maquinaId = $(this).val();
            if (maquinaId) {
                $.ajax({
                    url: '{{ route("get-insumos-por-maquinaria") }}',
                    type: 'GET',
                    data: { id: maquinaId },
                    success: function (data) {
                        let container = $('#insumos-container');
                        container.empty(); // Limpiar el contenedor

                        // Aplanar el array
                        let insumos = data.flat();

                        insumos.forEach(function (insumo) {
                            let insumoHtml = `
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-5">
                                        <input type="checkbox" name="${insumo.id_inventario}" data-id="${insumo.id_inventario}"
                                            class="insumo-enable me-2">
                                        <span> ${insumo.id_inventario} ${insumo.catalogo_articulos.nombre}</span>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input type="text" name="insumos[${insumo.id_inventario}]" placeholder="Cantidad"
                                                data-id="${insumo.id_inventario}" class="insumo-cantidad form-control" disabled>
                                            <span class="input-group-text">Mililitros</span>
                                        </div>
                                    </div>
                                </div>`;
                            container.append(insumoHtml);
                            console.log(insumo.id_inventario);
                        });

                        // Volver a asociar el evento click después de agregar nuevos elementos
                        $('.insumo-enable').on('click', function () {
                            let id = $(this).attr('data-id');
                            let enable = $(this).is(":checked");
                            $('.insumo-cantidad[data-id="' + id + '"]').attr('disabled', !enable).attr('required', enable);
                            $('.insumo-cantidad[data-id="' + id + '"]').val(null);
                        });
                    }
                });
            }
        });
    });
</script>



  </script>

  <script>
    
    var formularios = document.querySelectorAll('.miFormulario');
    formularios.forEach(function(formulario) {
        formulario.addEventListener('submit', function(event) {
            var boton = formulario.querySelector('.miBoton');
            boton.disabled = true; 
        });
    });
</script>

  @endsection
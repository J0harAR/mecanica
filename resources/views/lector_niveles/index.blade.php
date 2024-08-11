@extends('layouts.app')

@section('content')
@can('ver-lecturas')
  

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-tools "></i> Lectura de insumos por maquina
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-tools "></i> lectura
          </li>
        </ol>
      </nav>
    </div>
    <div class="btn-group" role="group">
      
      @can('crear-lectura')       
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

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert" id="danger-alert">
    {{ session('error') }}
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    window.setTimeout(function () {
      const successAlert = document.getElementById("danger-alert");
      if (successAlert) successAlert.style.display = 'none';
    }, 3000);
    });
  </script>
@endif




        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle"
                style="border-collapse: separate; border-spacing: 0 10px;">
                <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                    <tr>
                    <th>N.Lectura</th>
                    <th>Máquina</th>
                    <th>Insumos Utilizados</th>
                    <th data-type="date" data-format="YYYY/DD/MM">Fecha</th>
                    
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lecturas as $lectura)
                <tr>
                    <td>{{$lectura->id}}</td>
                <td>
                    {{$lectura->Maquinarias->id_maquinaria}}/{{$lectura->Maquinarias->Articulo_inventariados->Catalogo_articulos->nombre}}
                </td>
                <td>
                    <button type="button" class="btn btn-outline-primary btn-sm  "
                    data-bs-toggle="modal" data-bs-target="#modal-{{ $lectura->id }}"
                    style="border-color: #002855; color: #002855;">
                    <i class="bi bi-droplet"></i>
                    </button>
                </td>
                <td>{{$lectura->fecha}}</td>
            
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modal-{{ $lectura->id }}" tabindex="-1"
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
                    @foreach ($lectura->insumos as $insumo)
                    
                <div class="row align-items-center mb-2">
                    <div class="col-md-6">
                    {{$insumo->id_articulo}}/{{ $insumo->nombre }}
                    </div>

                    <div class="col-md-6">
                         <div class="input-group"> 
                         <input type="text" name="insumos[{{ $insumo->id_insumo }}]" class="form-control"
                         value="{{ $insumo->pivot->cantidad }}" disabled>                        
                                <span class="input-group-text">Mililitros</span>
                        </div>
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

            

            @endforeach
                </tbody>

                </table>
            </div>
            </div>
        </div>


  @can('crear-lectura')
<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header" style="background-color: #002855; color: #ffffff;">
          <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Registrar Lectura</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3 miFormulario" action="{{ route('lector.store') }}" method="POST">
            @csrf
            <div class="col-md-6 mb-3">
              <label for="maquina" class="form-label"><i class="bi bi-tools me-2"></i>Seleccione máquina</label>
              <select class="form-select" aria-label="Seleccione una máquina" name="maquina" id="maquina" required>
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
              <input type="date" class="form-control" name="fecha" id="fecha" required>
            </div>
            
            <div class="col-md-12 mb-3">
                <label for="insumos" class="form-label"><i class="bi bi-box-seam me-2"></i>Datos generales</label>
                    <div id="datos-maquina">


                  </div>
            </div>

            <div class="col-md-12 mb-3">
              <label for="fecha" class="form-label"><i class="bi bi-calendar me-2"></i>Observaciones</label>
              <input type="text" class="form-control" name="observaciones" id="observaciones" required>
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





  <script>
    $(document).ready(function () {
        $('#maquina').on('change', function () {
            let maquinaId = $(this).val();
            if (maquinaId) {
               
                $.ajax({
                url: '{{ route("get-datos-maquinaria") }}',
                type: 'GET',
                data: { id: maquinaId },
                success: function (data) {
                    let container = $('#datos-maquina');
                    container.empty(); // Limpiar el contenedor

                    let datos = data;

                    // Crear HTML para los datos generales
                    let DatosHtml = ` 
                        <div class="row">
                            <div class="col-md-12 text-left">
                                <p>ID:${datos.articulo_inventariados.id_inventario}-${datos.articulo_inventariados.catalogo_articulos.nombre}</p>
                            </div>
                        </div>         
                    `;

                    // Crear HTML para los insumos
                    DatosHtml += `
                        <div class="row">
                            <div class="col-md-6">
                                <p class="form-label">Insumos</p>
                            </div>
                            <div class="col-md-6">
                                <p class="form-label">Cantidad</p>
                            </div>
                        </div>
                    `;

                    datos.insumos.forEach(function(insumo) {
                        DatosHtml += `
                            <div class="row align-items-center mb-2">
                                <div class="col-md-5">
                                    <input type="checkbox" name="${insumo.id_articulo}" data-id="${insumo.id_articulo}" class="insumo-enable me-2">
                                    <span> ${insumo.id_articulo} ${insumo.nombre}</span>
                                </div>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <input type="text" data-id="${insumo.id_articulo}" class="insumo-cantidad form-control" name="insumos[${insumo.id_articulo}]" disabled>
                                        <span class="input-group-text">Mililitros</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    // Agregar el HTML al contenedor
                    container.append(DatosHtml);
                }
            });
            
            }
        });

        // Delegación de eventos para asegurarse de que se aplica a elementos dinámicamente agregados
        $(document).on('click', '.insumo-enable', function () {
            let id = $(this).data('id');
            let enable = $(this).is(":checked");
            $('.insumo-cantidad[data-id="' + id + '"]').attr('disabled', !enable).attr('required', enable);
            $('.insumo-cantidad[data-id="' + id + '"]').val(null);
        });
    });
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

@endcan
@endsection
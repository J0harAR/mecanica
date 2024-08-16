@extends('layouts.app')

@section('content')
@can('ver-lecturas')
  

<div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-primary">
      <i class="bi bi-file-earmark-bar-graph"></i> Lectura de insumos-maquina
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="bi bi-file-earmark-bar-graph"></i> lectura
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

<h3 class="fw-bold text-secondary mb-4">Consulta de Consumo de Insumos</h3>

  <div class="row">

      <div class="col-md-6">
        <form id="filterForm">
          <div class="row">
            <!-- Maquinaria Selector -->
            <div class="col-md-4">
              <label for="maquinaria" class="form-label">Maquinaria</label>
              <select id="maquinaria" name="maquinaria_id" class="form-select" required>
                <option value="" selected disabled>Seleccione una maquinaria</option>
                <!-- Options should be dynamically generated from the server -->
                @foreach($maquinarias as $maquinaria)
                  <option value="{{ $maquinaria->id_maquinaria }}">{{ $maquinaria->Articulo_inventariados->Catalogo_articulos->nombre }}</option>
                @endforeach
              </select>
            </div>

            <!-- Date Range Selector -->
            <div class="col-md-4">
              <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
              <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="col-md-4">
              <label for="fecha_fin" class="form-label">Fecha de Fin</label>
              <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row mt-3">
            <div class="col-md-12 text-right mb-3">
              <button type="submit" class="btn btn-primary">Generar Gráfica</button>
            </div>
          </div>
          
        </form>
      </div>


    <div class="col-md-6">
      <div  id="clearButtonContainer" style="display: block;" class="text-end">
        <button id="clearChart" class="btn btn-danger mt-3" type="button"><i class="bi bi-trash"></i></button> 
      </div>
      
      <div id="columnChart"></div>

    </div>



  </div>
 


  <h3 class="fw-bold text-secondary mb-4">Listado de lecturas</h3>

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
              <div id="fecha-feedback" class="invalid-feedback"></div>

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

<script>
 document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('filterForm');
  const clearButton = document.getElementById('clearChart');
  let chart; // Declare the chart variable globally
  document.getElementById('clearButtonContainer').style.display = 'none';
  
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(form);

    // Destroy the previous chart if it exists
    if (chart) {
      chart.destroy();
      document.querySelector("#columnChart").innerHTML = ''; // Clear the chart container
    }

    // AJAX request to the server
    $.ajax({
      url: '{{ route("comportamiento.insumos") }}', // Update with your actual route
      method: 'GET',
      data: {
        maquinaria_id: formData.get('maquinaria_id'),
        fecha_inicio: formData.get('fecha_inicio'),
        fecha_fin: formData.get('fecha_fin')
      },
      success: function(data) {
        // Prepare categories and series data
        let categories = [];
        let seriesData = [];
        let insumosMap = {}; // Map to accumulate quantities by month and insumo name

        function getDayMonthYear(dateStr) {
          const date = new Date(dateStr);
          const year = date.getFullYear();
          const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Meses comienzan desde 0
          const day = date.getDate().toString().padStart(2, '0');
          return `${year}-${month}-${day}`;
        }

        data.forEach(entry => {
        let day = getDayMonthYear(entry.fecha); // Obtener el día, mes y año

        if (!categories.includes(day)) {
          categories.push(day); // Añadir el día si no está ya en las categorías
        }

        entry.insumos.forEach(insumo => {
          let key = `${day}-${insumo.nombre}`;
          if (!insumosMap[key]) {
            insumosMap[key] = { name: insumo.nombre, data: Array(categories.length).fill(0) };
          }

          const index = categories.indexOf(day);
          insumosMap[key].data[index] += insumo.pivot.cantidad_nueva;
        });
      });

        // Convert insumosMap to seriesData
        seriesData = Object.values(insumosMap);

        // Show the clear button
        document.getElementById('clearButtonContainer').style.display = 'block';

        // Update the chart with the new data
        chart = new ApexCharts(document.querySelector("#columnChart"), {
          series: seriesData,
          chart: {
            type: 'bar',
            height: 350
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              endingShape: 'rounded'
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: categories,
            title: {
              text: 'Días'
            }
          },
          yaxis: {
            title: {
              text: 'Cantidad de Insumos'
            }
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function(val) {
                return val + " Mililitros";
              }
            }
          }
        });

        chart.render();
      },
      error: function(xhr, status, error) {
        console.error('Error fetching data:', error);
      }
    });
  });

  clearButton.addEventListener('click', function() {
    if (chart) {
      chart.destroy(); // Destroy the chart
    }
    document.querySelector("#columnChart").innerHTML = ''; // Clear the chart container

    // Hide the clear button container
    document.getElementById('clearButtonContainer').style.display = 'none';
  });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const fechaInput = document.getElementById('fecha');
    
    // Establece el valor máximo permitido como la fecha actual (solo fecha)
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Meses comienzan desde 0
    const dd = String(today.getDate()).padStart(2, '0');
    const formattedToday = `${yyyy}-${mm}-${dd}`;

    fechaInput.setAttribute('max', formattedToday);
});
</script>
@endcan
@endsection
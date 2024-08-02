@extends('layouts.app')
@section('content')

@can('ver-herramientas')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-clipboard-list me-2"></i>Inventario
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        @can('ver-inventario')                                                
                        <a href="{{ route('inventario.index') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-clipboard-list me-1"></i>Inventario
                        </a>
                        @endcan

                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-wrench me-1"></i>Herramientas
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            @can('crear-herramienta')                      
            <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fas fa-plus-circle me-1"></i>Agregar herramienta
            </button>
            @endcan

            @can('generar_reporte_herramientas')                         
            <button class="btn btn btn-tecnm"  type="button"  data-bs-toggle="modal" data-bs-target="#modal-download" >
                <i class="bi bi-download"></i>
          </button>
          @endcan

        </div>
    </div>

    @can('generar_reporte_herramientas') 
 <!-- Modal -->
 <div class="modal fade" id="modal-download" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reporte de herramientas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('reporte.herramientas') }}" method="POST">
            @csrf
          <div class="modal-body">
          <label >Seleciona el periodo</label>

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


    @can('crear-herramienta')
    <!-- Vertically centered Modal -->
    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Agregar herramienta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('herramientas.store') }}" method="POST">
                        @csrf

                        <div class="col-md-12 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Nombre de
                                la herramienta</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="cantidad" class="form-label"><i class="bi bi-stack me-2"></i>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad"required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="estatus" class="form-label"><i
                                    class="bi bi-check-circle me-2"></i>Estatus</label>
                            <select id="estatus" class="form-select" name="estatus">
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                        </div>


                        <div id="tipo_herramienta" class="col-md-6 mb-3">
                            <label for="tipo_herramienta" class="form-label"><i class="bi bi-tools me-2"></i>Tipo de
                                herramientas</label>
                            <select id="tipo_herramienta" class="form-select" name="tipo_herramienta"required>
                                <option selected disabled>Selecciona un tipo</option>
                                <option value="Herramienta de corte">Herramienta de corte</option>
                                <option value="Herramienta de golpe">Herramienta de golpe</option>
                                <option value="Herramienta de mantenimiento">Herramienta de mantenimiento</option>
                                <option value="Herramienta de maquinado">Herramienta de maquinado</option>
                                <option value="Herramienta de medición">Herramienta de medición</option>
                                <option value="Herramienta de montaje">Herramienta de montaje</option>
                                <option value="Herramienta de neumáticos">Herramienta de neumáticos</option>
                                <option value="Herramienta de seguridad">Herramienta de seguridad</option>
                                <option value="Herramienta de sujeción">Herramienta de sujeción</option>
                                <option value="Herramienta de torno">Herramienta de torno</option>
                                <option value="Herramienta eléctrica">Herramienta eléctrica</option>
                                <option value="Herramienta manual">Herramienta manual</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="dimensionHerramienta">
                            <label for="dimension_herramienta" class="form-label"><i
                                    class="bi bi-rulers me-2"></i>Dimensión</label>
                            <input type="number" class="form-control" id="dimension_herramienta"
                                name="dimension_herramienta"required>
                        </div>
                        <div class="col-md-12 mb-3" id="condicionHerramienta" >
                            <label for="condicion_herramienta" class="form-label"><i
                                    class="bi bi-activity me-2"></i>Condición</label>
                            <input type="text" class="form-control" id="condicion_herramienta_input"
                                name="condicion_herramienta"required>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #002855; border-color: #002855;">Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    @endcan
   
    @can('ver-herramientas')
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-seleccion">
                    <div class="card-body">
                        <h5 class="card-title1">Herramientas</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="fas fa-wrench me-1"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('herramientas.index') }}">Ver más...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                       
            @endcan
            @can('ver-maquinarias')                         
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1">Maquinaria</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-gear"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('maquinaria.index') }}">Ver más...</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan    
            @can('ver-insumos')                     
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title1">Insumos</h5>
                        <div class="d-flex justify-content-between">
                            <h2 class="text-right"><i class="bi bi-droplet"></i></h2>
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h2><span></span></h2>
                                <p class="m-b-0 text-right"><a href="{{ route('insumos.index') }}">Ver más...</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
 

    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
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

    @if (session('tipo_vacia'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('tipo_vacia') }}
        </div>
    @endif 

    @can('ver-herramientas')       
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                        <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dimensión</th>
                        <th>Condición</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody  class="bg-light">
                    @foreach ($herramientas as $herramienta)
                        <tr>
                            <th>{{$herramienta->id_herramientas}}</th>
                            <td>{{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                            <td>{{$herramienta->dimension}}</td>
                            <td>{{$herramienta->condicion}}</td>
                            <td>{{$herramienta->Articulo_inventariados->estatus}}</td>
                            <td class="text-center">
                                
                                @can('editar-herramienta')                                                                
                                <button type="button" class="btn btn-outline-primary btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#modal-update-{{ $herramienta->id_herramientas}}"><i
                                        class="fas fa-edit bt"></i></button>
                                @endcan

                                @can('borrar-herramienta')                                                                  
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $herramienta->id_herramientas}}"><i
                                        class="fas fa-trash"></i></button>
                                @endcan


                            </td>
                        </tr>


                        @can('borrar-herramienta')
                        <!-- Modal -->
                        <div class="modal fade" id="modal-{{ $herramienta->id_herramientas}}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de querer eliminar la herramienta:
                                        {{$herramienta->Articulo_inventariados->Catalogo_articulos->nombre}}?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('herramientas.destroy', $herramienta->id_herramientas) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Modal -->
                        @endcan

                        @can('editar-herramienta')
                                               
                        <!-- Vertically centered Modal -->
                        <div class="modal fade" id="modal-update-{{ $herramienta->id_herramientas}}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar herramienta
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3"
                                            action="{{ route('herramientas.update', $herramienta->id_herramientas) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-6 mb-3">
                                                <label for="codigo_herramienta" class="form-label"><i
                                                        class="bi bi-tag me-2"></i>Código de herramienta</label>
                                                <input type="text" class="form-control" id="codigo_herramienta"
                                                    name="codigo_herramienta" value="{{ $herramienta->id_herramientas }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="nombre_herramienta" class="form-label"><i
                                                        class="bi bi-box me-2"></i>Nombre de la herramienta</label>
                                                <input type="text" class="form-control" id="nombre_herramienta"
                                                    name="nombre_herramienta"
                                                    value="{{ $herramienta->Articulo_inventariados->Catalogo_articulos->nombre }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="condicion_herramienta" class="form-label"><i
                                                        class="bi bi-activity me-2"></i>Condición</label>
                                                <input type="text" class="form-control" id="condicion_herramienta"
                                                    name="condicion_herramienta" value="{{ $herramienta->condicion }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="estatus" class="form-label"><i
                                                        class="bi bi-check-circle me-2"></i>Estatus</label>
                                                <select id="estatus" class="form-select" name="estatus">
                                                    <option value="Disponible" {{ $herramienta->Articulo_inventariados->estatus == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                                    <option value="No disponible" {{ $herramienta->Articulo_inventariados->estatus == 'No disponible' ? 'selected' : '' }}>No disponible</option>
                                                </select>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary"
                                                    style="background-color: #002855; border-color: #002855;">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Vertically centered Modal -->

                        @endcan


                    @endforeach


                </tbody>
            </table>
        </div>
    </div>

  @endcan

</div>

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.setTimeout(function () {
                const successAlert = document.getElementById("success-alert");
                if (successAlert) successAlert.style.display = 'none';
            }, 3000);
        });
    </script>
@endif

@endcan
@endsection
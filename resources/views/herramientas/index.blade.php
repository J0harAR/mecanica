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
                    <form class="row g-3 miFormulario" action="{{ route('herramientas.store') }}" method="POST">
                        @csrf


                        <div class="col-md-12 mb-3">
                        <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Seleccion el articulo</label>
                            <select name="id_articulo" id="id_articulo" class="form-select">
                                
                                @foreach ($articulos as $articulo)
                                    <option value="{{$articulo->id_articulo}}">{{$articulo->id_articulo}}//{{$articulo->nombre}}</option>
                                    
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label"><i class="bi bi-stack me-2"></i>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="1000" required>
                        </div>

                        
                        <div class="col-md-12 mb-3">
                            <label for="estatus" class="form-label"><i
                                    class="bi bi-check-circle me-2"></i>Estatus</label>
                            <select id="estatus" class="form-select" name="estatus">
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                        </div>


                        
                        <div class="col-md-12 mb-3" id="condicionHerramienta">
                            <label for="condicion_herramienta" class="form-label"><i class="bi bi-activity me-2"></i>Condición</label>
                            <select id="condicion_herramienta" class="form-select" name="condicion_herramienta" required>
                                <option value="Buen estado">Buen estado</option>
                                <option value="Mal estado">Mal estado</option>
                            </select>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary miBoton"
                                style="background-color: #002855; border-color: #002855;">Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                    <div class="modal-footer miFormulario">
                                        <form action="{{ route('herramientas.destroy', $herramienta->id_herramientas) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger miBoton">Eliminar</button>
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
                                        <form class="row g-3 miFormulario"
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
                                                <label for="condicion_herramienta" class="form-label"><i class="bi bi-activity me-2"></i>Condición</label>
                                                <select id="condicion_herramienta" class="form-select" name="condicion_herramienta">
                                                    <option value="Buen estado" {{ $herramienta->condicion == 'Buen estado' ? 'selected' : '' }}>Buen estado</option>
                                                    <option value="Mal estado" {{ $herramienta->condicion == 'Mal estado' ? 'selected' : '' }}>Mal estado</option>
                                                </select>
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
                                                <button type="submit" class="btn btn-primary miBoton"
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
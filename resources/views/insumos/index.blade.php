@extends('layouts.app')

@section('content')

@can('ver-insumos')
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
                        <a href="{{ route('inventario.index') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-clipboard-list me-1"></i>Inventario
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-droplet"></i>Insumos
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            @can('crear-insumo')                   
            <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal">
                <i class="fas fa-plus-circle me-1"></i>Agregar insumo
            </button>
            @endcan

            @can('generar_reporte_insumos')                           
            <button class="btn btn btn-tecnm"  type="button"  data-bs-toggle="modal" data-bs-target="#modal-download" >
                <i class="bi bi-download"></i>
          </button>
          @endcan
        </div>
    </div>

@can('generar_reporte_insumos')
   
    <!-- Modal -->
 <div class="modal fade" id="modal-download" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reporte de herramientas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('reporte.insumos') }}" method="POST">
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

    @can('crear-insumo')
        <!-- Vertically centered Modal -->
        <div class="modal fade" id="modal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Agregar insumo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('insumos.store') }}" method="POST">
                        @csrf
                        <div class="col-md-12 mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Nombre del
                                insumo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                autocomplete="nombre" autofocus>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="cantidad" class="form-label"><i class="bi bi-stack me-2"></i>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="estatus" class="form-label"><i
                                    class="bi bi-check-circle me-2"></i>Estatus</label>
                            <select id="estatus" class="form-select" required name="estatus">
                                <option value="Disponible">Disponible</option>
                                <option value="No disponible">No disponible</option>
                            </select>
                        </div>


                        <div class="col-md-8 mb-3" id="tipoInsumo">
                            <label for="tipo_insumo" class="form-label"><i class="bi bi-fuel-pump me-2"></i>Tipo de
                                insumo</label>
                            <input type="text" class="form-control" id="tipo_insumo" name="tipo_insumo"required>
                        </div>
                        <div class="col-md-4 mb-3" id="capacidadInsumo">
                            <label for="capacidad_insumo" class="form-label"><i
                                    class="bi bi-speedometer2 me-2"></i>Capacidad</label>
                            <input type="number" class="form-control" id="capacidad_insumo" name="capacidad_insumo"required>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #002855; border-color: #002855;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>       
    </div>
    @endcan
    @can('ver-herramientas')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title1">Herramientas</h5>
                    <div class="d-flex justify-content-between">
                        <h2 class="text-right"><i class="bi bi-wrench me-1"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{route('herramientas.index')}}">Ver mas...</a></p>
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
                            <p class="m-b-o text-right"><a href="{{route('maquinaria.index')}}">Ver mas...</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endcan

        @can('ver-insumos')  
        <div class="col-lg-4">
            <div class="card card-seleccion">
                <div class="card-body">
                    <h5 class="card-title1">Insumos</h5>
                    <div class="d-flex justify-content-between">
                        <h2 class="text-right"><i class="bi bi-droplet"></i></h2>
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2><span></span></h2>
                            <p class="m-b-o text-right"><a href="{{route('insumos.index')}}">Ver mas...</a></p>
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
    @can('ver-insumos')    
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table datatable table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                        <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody  class="bg-light">
                    @foreach ($insumos as $insumo)
                        <tr>
                            <th>{{$insumo->id_insumo}}</th>
                            <td>{{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}</td>
                            <td>{{$insumo->Articulo_inventariados->Catalogo_articulos->tipo}}</td>
                            <td>{{$insumo->capacidad}}</td>
                            <td>{{$insumo->Articulo_inventariados->estatus}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#modal-update-{{ $insumo->id_insumo}}"><i
                                        class="fas fa-edit bt"></i></button>
                                <button type="button" class="btn btn-outline-danger btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $insumo->id_insumo}}"><i class="fas fa-trash"></i></button>
                            </td>

                        </tr>

                        @can('borrar-insumo')                                                 
                        <!-- Modal -->
                        <div class="modal fade" id="modal-{{ $insumo->id_insumo}}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de querer eliminar :
                                        {{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" method="POST">
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
                        
                        @can('editar-insumo')
                        <!-- Modal Update -->
                        <div class="modal fade" id="modal-update-{{ $insumo->id_insumo }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar artículo</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3" action="{{ route('insumos.update', $insumo->id_insumo) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-4 mb-3">
                                                <label for="id_insumo" class="form-label"><i class="bi bi-gear me-2"></i>Código de insumo</label>
                                                <input type="text" class="form-control" id="id_insumo" name="id_insumo" value="{{ $insumo->id_insumo }}" disabled>
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label for="nombre" class="form-label"><i class="bi bi-box-seam me-2"></i>Nombre del artículo</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $insumo->Articulo_inventariados->Catalogo_articulos->nombre }}" disabled>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="capacidad" class="form-label"><i class="bi bi-bar-chart me-2"></i>Capacidad</label>
                                                <input type="number" class="form-control" id="capacidad" name="capacidad" value="{{ $insumo->capacidad }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="estatus" class="form-label"><i class="bi bi-check-circle me-2"></i>Estatus</label>
                                                <input type="text" class="form-control" id="estatus" name="estatus" value="{{ $insumo->Articulo_inventariados->estatus }}">
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary" style="background-color: #002855; border-color: #002855;">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Update -->
                        @endcan



                    @endforeach

                </tbody>
            </table>
        </div>
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
@endcan
@endsection
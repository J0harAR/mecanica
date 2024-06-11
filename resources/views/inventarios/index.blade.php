@extends('layouts.app')
@section('content')
@can('ver-inventario')


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
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-clipboard-list me-1"></i>Inventario
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#ExtralargeModal">
                    <i class="fas fa-history me-1"></i>
                </button>

                @can('generar_reporte_inventario')
                    <button class="btn btn btn-tecnm" type="button" data-bs-toggle="modal" data-bs-target="#modal-download">
                        <i class="bi bi-download"></i>
                    </button>
                @endcan

            </div>
        </div>
        <!-- Extra Large Modal -->
        <div class="modal fade" id="ExtralargeModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Historial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Evento</th>
                                    <th>Modelo</th>
                                    <th>Id artículo</th>
                                    <th>Id usuario</th>
                                    <th>Datos pasados</th>
                                    <th>Datos actuales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historial as $auditoria)
                                    <tr>
                                        <td>{{ $auditoria->id }}</td>
                                        <td>{{ $auditoria->event }}</td>
                                        <td>{{ $auditoria->subject_type }}</td>
                                        <td>{{ $auditoria->subject_id }}</td>
                                        <td>{{ $auditoria->cause_id }}</td>
                                        <td>
                                            @if($auditoria->old_data === "[]")
                                                Sin datos
                                            @else
                                                <button onclick="mostrarJSON('{{ $auditoria->old_data }}')"
                                                    class="btn btn-info btn-sm"><i class="fas fa-info"></i>Ver más</button>
                                            @endif
                                        </td>
                                        <td>
                                            <button onclick="mostrarJSON('{{ $auditoria->new_data }}')"
                                                class="btn btn-info btn-sm"><i class="fas fa-info"></i>Ver más</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @can('generar_reporte_inventario')
            <!-- Modal -->
            <div class="modal fade" id="modal-download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reporte de inventario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('reporte.inventario') }}" method="POST">
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



        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="error-alert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
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
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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


        @if(session('tipo_null'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="tipo_null-alert">
                {{ session('tipo_null') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    window.setTimeout(function () {
                        const successAlert = document.getElementById("tipo_null-alert");
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

                                <th scope="col" class="text-center">Código</th>
                                <th scope="col" class="text-center">Nombre</th>
                                <th scope="col" class="text-center">Cantidad</th>
                                <th scope="col" class="text-center">Tipo</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-light">
                            @foreach ($catalogo_articulo as $articulo)
                                <tr>
                                    <td>{{ $articulo->id_articulo }}</td>
                                    <td>{{ $articulo->nombre }}</td>
                                    <td>{{ $articulo->cantidad }}</td>
                                    <td>{{ $articulo->tipo }}</td>
                                    <td class="text-center">
                                        @can('borrar-inventario')
                                            <button type="button" class="btn btn-outline-danger btn-sm " data-bs-toggle="modal"
                                                data-bs-target="#modal-{{ $articulo->id_articulo }}"><i
                                                    class="fas fa-trash"></i></button>
                                        @endcan

                                    </td>
                                </tr>
                                @can('borrar-inventario')
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-{{ $articulo->id_articulo }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Estás seguro de querer eliminar: {{ $articulo->nombre }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('inventario.destroy', $articulo->id_articulo) }}"
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
                                    </div>
                                @endcan
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
@endcan
        </>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var tipoSelect = document.getElementById('tipo');
                var tipo_herramienta = document.getElementById('tipo_herramienta');
                var tipo_maquina = document.getElementById('tipoMaquina');
                var tipo_insumo = document.getElementById('tipoInsumo');
                var capacidad_insumo = document.getElementById('capacidadInsumo');
                var dimension_herramienta = document.getElementById('dimensionHerramienta');
                var condicion_herramienta = document.getElementById('condicionHerramienta');
                var seccion = document.getElementById('seccion');
                var todos_insumos = document.getElementById('todos_insumos');
                var condicion_herramienta_input = document.getElementById('condicion_herramienta_input');

                tipoSelect.addEventListener('change', function () {
                    switch (this.value) {
                        case 'Herramientas':
                            showElement(tipo_herramienta);
                            showElement(dimension_herramienta);
                            showElement(condicion_herramienta);
                            setRequired([tipo_herramienta, dimension_herramienta, condicion_herramienta_input], true);
                            setRequired([tipo_maquina, tipo_insumo, capacidad_insumo, seccion, todos_insumos], false);
                            hideElements([tipo_maquina, tipo_insumo, capacidad_insumo, seccion, todos_insumos]);
                            break;
                        case 'Maquinaria':
                            showElement(tipo_maquina);
                            showElement(seccion);
                            showElement(todos_insumos);
                            setRequired([tipo_maquina, seccion], true);
                            setRequired([tipo_herramienta, tipo_insumo, capacidad_insumo, dimension_herramienta, condicion_herramienta_input], false);
                            hideElements([tipo_herramienta, tipo_insumo, capacidad_insumo, dimension_herramienta, condicion_herramienta]);
                            break;
                        case 'Insumos':
                            showElement(tipo_insumo);
                            showElement(capacidad_insumo);
                            setRequired([tipo_insumo, capacidad_insumo], true);
                            setRequired([tipo_herramienta, tipo_maquina, dimension_herramienta, condicion_herramienta_input, seccion, todos_insumos], false);
                            hideElements([tipo_herramienta, tipo_maquina, dimension_herramienta, condicion_herramienta, seccion, todos_insumos]);
                            break;
                        default:
                            break;
                    }
                });

                function showElement(element) {
                    element.style.display = 'block';
                    if (element.querySelector('input, select')) {
                        element.querySelector('input, select').required = true;
                    }
                }

                function hideElements(elements) {
                    elements.forEach(element => {
                        element.style.display = 'none';
                        if (element.querySelector('input, select')) {
                            element.querySelector('input, select').required = false;
                        }
                    });
                }

                function setRequired(elements, required) {
                    elements.forEach(element => {
                        if (element.querySelector('input, select')) {
                            element.querySelector('input, select').required = required;
                        }
                    });
                }
            });

        </script>

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
            function mostrarJSON(jsonData) {
                var jsonStr = JSON.stringify(JSON.parse(jsonData), null, 2);
                jsonStr = jsonStr.substring(1, jsonStr.length - 1);
                var css = `
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        pre {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            max-width: 100%;
        }
    `;
                var style = '<style>' + css + '</style>';
                var content = style + '<body><pre>' + jsonStr + '</pre></body>';
                var width = 400;
                var height = 400;
                var left = window.innerWidth / 2 - width / 2;
                var top = window.innerHeight / 2 - height / 2;
                var ventanaEmergente = window.open('', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=' + top + ',left=' + left + ',width=' + width + ',height=' + height);
                ventanaEmergente.document.write(content);
            }
        </script>
        @endsection
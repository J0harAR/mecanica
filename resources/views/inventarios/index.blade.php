@extends('layouts.app')
@section('content')
@canany(['ver-inventario','ver-insumos','ver-maquinarias','ver-herramientas'])  


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

                 <!-- BOTON DE catalogo -->
                @can('crear-articulo')
                 <button type="button" class="btn btn-tecnm" data-bs-toggle="modal" data-bs-target="#modal-catalogo">
                    <i class="fas fa-history me-1"></i>Agregar al catalogo
                </button>
                @endcan
                 <!-- BOTON DE HISTORIAL -->
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

        @can('crear-articulo')
        <!-- Modal Catalogo -->
        <div class="modal fade" id="modal-catalogo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #002855; color: #ffffff;">
                <h5 class="modal-title">Catálogo de artículos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <form class="row" action="{{ route('inventario.store') }}" method="POST">
        @csrf
        <!-- Selector de Tipo -->
        <div class="col-md-6">
            <label for="tipo" class="form-label">Tipo de Producto:</label>
            <select name="tipo" id="tipo" class="form-control" required onchange="clearError('tipoError')">
                <option value="default">Seleccione un tipo</option>
                <option value="Herramientas" {{ old('tipo') == 'Herramientas' ? 'selected' : '' }}>Herramientas</option>
                <option value="Maquinaria" {{ old('tipo') == 'Maquinaria' ? 'selected' : '' }}>Maquinaria</option>
                <option value="Insumos" {{ old('tipo') == 'Insumos' ? 'selected' : '' }}>Insumos</option>
            </select>
            @error('tipo')
                <div id="tipoError" class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div id="nombre" class="col-md-6 mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sección de Herramientas -->
        <div id="tipo_herramienta" style="display: none;" class="col-md-6">
            <label for="tipoHerramienta" class="form-label">Tipo de Herramienta:</label>
            <select id="tipo_herramienta" class="form-select" name="tipo_herramienta">
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
            @error('tipo_herramienta')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3" id="dimensionHerramienta" style="display: none;">
            <label for="dimension_herramienta" class="form-label"><i class="bi bi-rulers me-2"></i>Dimensión</label>
            <input type="number" class="form-control" id="dimension_herramienta" name="dimension_herramienta" value="{{ old('dimension_herramienta') }}">
            @error('dimension_herramienta')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

   
        <div class="col-md-12 mb-3" id="seccion" style="display: none;">
            <label for="seccion" class="form-label"><i class="bi bi-tags me-2"></i>Sección</label>
            <select id="seccion" class="form-select" name="seccion">
                                <option selected disabled>Selecciona una sección</option>
                                <option value="03">03 Metrología II</option>
                                <option value="04">04 Mecánica de materiales</option>
                                <option value="05">05 Mantenimiento</option>
                                <option value="06">06 Robots industriales</option>
                                <option value="07">07 Mecánica de materiales</option>
                                <option value="08">08 Manufactura sustractiva</option>
                                <option value="09">09 Manufactura aditiva</option>
                                <option value="12">12 Mecánica de fluidos y termodinámica</option>
                                <option value="13">13 Neumática</option>
                                <option value="20">20 Área de diseño digital</option>
                            </select>
            @error('seccion')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

       
        <!-- Botón de Envío -->
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tipoSelect = document.getElementById('tipo');
        var tipo_herramienta = document.getElementById('tipo_herramienta');
        var dimensionHerramienta = document.getElementById('dimensionHerramienta');
        var seccion = document.getElementById('seccion');

        function mostrarElementos(tipo) {
            tipo_herramienta.style.display = 'none';
            dimensionHerramienta.style.display = 'none';
            seccion.style.display = 'none';

            switch(tipo) {
                case 'Herramientas':
                    tipo_herramienta.style.display = 'block';
                    dimensionHerramienta.style.display = 'block';
                    break;
                case 'Maquinaria':
                    seccion.style.display = 'block';
                    break;
                case 'Insumos':
                 
                    break;
            }
        }

        // Mostrar los elementos si hay un valor seleccionado previamente (para errores)
        var tipoInicial = tipoSelect.value;
        if (tipoInicial !== 'default') {
            mostrarElementos(tipoInicial);
        }

        // Mostrar los elementos correspondientes cuando se cambia el tipo
        tipoSelect.addEventListener('change', function () {
            var tipo = this.value;
            mostrarElementos(tipo);
        });

        // Abrir el modal automáticamente si hay errores de validación
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('modal-catalogo'), {
                keyboard: false
            });
            myModal.show();
        @endif
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('modal-catalogo'), {
                keyboard: false
            });
            
            myModal.show();
        @endif
    });
</script>
<script>
    function clearError(errorId) {
        document.getElementById(errorId).style.display = 'none';
    }
</script>

        @endcan

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

        @can('ver-herramientas')
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
        @can('ver-inventario') 
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
            @endcanany
        </>
       

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var tipoSelect = document.getElementById('tipo');
                var tipo_herramienta = document.getElementById('tipo_herramienta');
                var dimension_herramienta = document.getElementById('dimensionHerramienta');       
                var seccion = document.getElementById('seccion');
                
                tipoSelect.addEventListener('change', function () {
                    
                    switch (this.value) {
                        case 'Herramientas':
                            showElement(tipo_herramienta);
                            showElement(dimension_herramienta); 
                            setRequired([tipo_herramienta,dimension_herramienta]);                                                        
                            hideElements([seccion]);
                            break;
                        case 'Maquinaria':
                            showElement(seccion);                 
                            setRequired([seccion]);                        
                            hideElements([tipo_herramienta,dimension_herramienta]);
                            break;
                        case 'Insumos':                                                  
                            hideElements([tipo_herramienta, dimension_herramienta, seccion]);
                            break;
                        case 'default':
                            hideElements([ 
                            seccion, 
                            dimension_herramienta,
                            seccion,
                            tipo_herramienta]);
                            break;
                        default:
                        setRequired(tipoSelect);           
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
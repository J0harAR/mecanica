@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Inventario</h1>
      
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal">Agregar articulo</button>

    </div>
            
<div class="row">
    <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Herramientas</h5>
                <div class="d-flex justify-content-between">
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
                    <div class="d-flex flex-column justify-content-between align-items-center"> 
                        <h2><span></span></h2>
                        <p class="m-b-o text-right"><a href="/roles">Ver mas...</a></p>
                    </div> 
                </div> 
                
            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Maquinaria</h5>
                <div class="d-flex justify-content-between">
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
                    <div class="d-flex flex-column justify-content-between align-items-center"> 
                        <h2><span></span></h2>
                        <p class="m-b-o text-right"><a href="/roles">Ver mas...</a></p>
                    </div> 
                </div> 
                
            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Insumos</h5>
                <div class="d-flex justify-content-between">
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
                    <div class="d-flex flex-column justify-content-between align-items-center"> 
                        <h2><span></span></h2>
                        <p class="m-b-o text-right"><a href="/roles">Ver mas...</a></p>
                    </div> 
                </div> 
                
            </div>
          </div>
        </div>
</div>

    <div class="card">
            <div class="card-body">
            
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>
                            Codigo
                            </th>
                            <th>Nombre</th>
                            <th>Estatus</th>
                            <th>Tipo</th>
                            <th>Subtipo</th>
                            <th>Seccion</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($articulos_inventariados  as $articulo )
                        <tr>
                            <td>{{$articulo->id_inventario}}</td>
                            <td>{{$articulo->Catalogo_articulos->nombre}}</td>
                            <td>{{$articulo->estatus}}</td>
                            <td>{{$articulo->tipo}}</td>

                            @if ($articulo->Catalogo_articulos->tipo)
                                <td>{{$articulo->Catalogo_articulos->tipo}}</td>
                            @else
                                <td>Sin subtipo</td>
                            @endif

                            

                            @if ($articulo->Catalogo_articulos->seccion)
                                <td>{{$articulo->Catalogo_articulos->seccion}}</td>
                            @else
                                <td>Sin sección</td>
                            @endif
                            <td>{{$articulo->id_inventario}}</td>
                           
                        </tr>  
                        @endforeach
                       
                        </tbody>
                    </table>
             </div>
    </div>
    















<!-- Vertically centered Modal -->
              <div class="modal fade" id="modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Agregar articulo</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                        <form class="row g-3" action="{{route('inventario.store')}}" method="POST">
                        @csrf
                            <div class="col-md-12">
                                <label for="nombre" class="form-label">Nombre del articulo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>

                            <div class="col-md-12">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad">
                            </div>


                            <div class="col-md-12">
                                <label for="estatus" class="form-label">Estatus</label>
                                <input type="text" class="form-control" id="estatus" name="estatus">
                            </div>

                            <div class="col-md-12">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select id="tipo" class="form-select" required name="tipo">
                                    <option selected disabled>Selecciona un tipo</option>
                                    <option value="Insumos">Insumos</option>
                                    <option value="Maquinaria">Maquinaria</option>
                                     <option value="Herramientas">Herramientas</option>
                                </select>
                            </div>



                            <div class="col-md-12" id="seccion">
                                <label for="seccion" class="form-label">Seccion</label>
                                    <select id="seccion" class="form-select" name="seccion">
                                    <option selected disabled>Selecciona una seccion </option>
                                        <option value="03">03 Metrologia II </option>
                                        <option value="04">04 Mecanica de materiales</option>
                                        <option value="05">05 Mantenimiento</option>
                                        <option value="06">06 Robots industriales</option>
                                        <option value="07">07 Mecanica de materiales</option>
                                        <option value="08">08 Manufactura sustractiva</option>
                                        <option value="09">09 Manufactura adidtiva</option>
                                        <option value="12">12 Mecanica de fluidos y termodinamica</option>
                                        <option value="13">13 Neumatica</option>
                                        <option value="20">20 Área de diseño digital</option>
                                    </select>
                            </div>

                            

                            <div id="tipo_herramienta" style="display: none;" class="col-md-6">
                                <label for="tipo_herramienta" class="form-label">Tipo de herramientas</label>
                                <select id="tipo_herramienta" class="form-select" name="tipo_herramienta">
                                <option selected disabled>Selecciona un tipo</option>
                                <option value="Herramienta de corte">Herramienta de corte</option>
                                <option value="Herramienta de golpe">Herramienta de golpe</option>
                                <option value="Herramienta de mantenimimiento">Herramienta de mantenimimiento</option>
                                <option value="Herramienta de maquinado">Herramienta de maquinado</option>
                                <option value="Herramienta de medición">Herramienta de medición</option>
                                <option value="Herramienta de montaje">Herramienta de montaje</option>
                                <option value="Herramienta de neumaticos">Herramienta de neumaticos</option>
                                <option value="Herramienta de seguridad">Herramienta de seguridad</option>
                                <option value="Herramienta de sujecion">Herramienta de sujeción</option>
                                <option value="Herramienta de torno">Herramienta de torno</option>
                                <option value="Herramienta Electrica">Herramienta Electrica</option>
                                <option value="Herramienta manual">Herramienta manual</option>
                                </select>
                            </div>  

                            <div class="col-md-6" id="dimensionHerramienta" style="display:none;">
                                <label for="dimension_herramienta" class="form-label">Dimensión</label>
                                <input type="number" class="form-control" id="dimension_herramienta" name="dimension_herramienta">
                            </div>

                            <div class="col-md-12" id="condicionHerramienta" style="display:none;">
                                <label for="condicion_herramienta" class="form-label">Condición</label>
                                <input type="text" class="form-control" id="condicion_herramienta" name="condicion_herramienta">
                            </div>



                            <div class="col-md-12" id="tipoMaquina" style="display:none;">
                                <label for="tipo_maquina" class="form-label">Tipo de maquina</label>
                                <input type="text" class="form-control" id="tipo_maquina"  name="tipo_maquina">
                            </div>

                            <div class="col-md-8" id="tipoInsumo" style="display:none;">
                                <label for="tipo_insumo" class="form-label">Tipo de insumo</label>
                                <input type="text" class="form-control" id="tipo_insumo"  name="tipo_insumo">
                            </div>

                            <div class="col-md-4" id="capacidadInsumo" style="display:none;">
                                <label for="capacidad_insumo" class="form-label">Capacidad</label>
                                <input type="number" class="form-control" id="capacidad_insumo" name="capacidad_insumo">
                            </div>


                   
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Guardar</button>                               
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
   
    var tipoSelect = document.getElementById('tipo');
   
    var tipo_herramienta = document.getElementById('tipo_herramienta');


    var tipo_maquina=document.getElementById('tipoMaquina');

    var tipo_insumo=document.getElementById('tipoInsumo');

    var capacidad_insumo=document.getElementById('capacidadInsumo');

    var dimension_herramienta=document.getElementById('dimensionHerramienta');

    var condicion_herramienta=document.getElementById('condicionHerramienta');

    var seccion=document.getElementById('seccion');


    tipoSelect.addEventListener('change', function() {
    switch (this.value) {
        case 'Herramientas':
            showElement(tipo_herramienta);
            showElement(dimension_herramienta);
            showElement(condicion_herramienta);
            
            hideElements([tipo_maquina, tipo_insumo,capacidad_insumo,seccion]);
            break;
        case 'Maquinaria':
            showElement(tipo_maquina);
            showElement(seccion);
            hideElements([tipo_herramienta, tipo_insumo,capacidad_insumo,dimension_herramienta,condicion_herramienta]);
           
            break;
        case 'Insumos':
            showElement(tipo_insumo);
            showElement(capacidad_insumo);
            hideElements([tipo_herramienta, tipo_maquina,dimension_herramienta,condicion_herramienta,seccion]);
            break;
        default:
         
            break;
    }

    function showElement(element) {
        element.style.display = 'block';
    }

    function hideElements(elements) {
        elements.forEach(element => {
            element.style.display = 'none';
        });
    }

                
    });
});
</script>






@endsection
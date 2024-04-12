@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Inventario</h1>
      
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal">Agregar articulo</button>

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
                        <form class="row g-3 needs-validation" action="{{route('inventario.store')}}" method="POST">
                        
                            <div class="col-md-12">
                                <label for="nombre" class="form-label">Nombre del articulo</label>
                                <input type="text" class="form-control" id="nombre">
                            </div>

                            <div class="col-md-12">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad">
                            </div>


                            <div class="col-md-12">
                                <label for="estatus" class="form-label">Estatus</label>
                                <input type="text" class="form-control" id="estatus">
                            </div>


                            <div class="col-md-6">
                                <label for="seccion" class="form-label">Seccion</label>
                                    <select id="seccion" class="form-select">
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

                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select id="tipo" class="form-select" required>
                                    <option selected disabled>Selecciona un tipo</option>
                                    <option value="Insumos">Insumos</option>
                                    <option value="Maquinaria">Maquinaria</option>
                                     <option value="Herramientas">Herramientas</option>
                                </select>
                            </div>

                            <div id="otroTipo" style="display: none;" class="col-md-6">
                                <label for="otroTipo" class="form-label">Tipo de herramientas</label>
                                <select id="otroTipo" class="form-select">
                                <option selected disabled>Selecciona un tipo</option>
                                <option value="HC">Herramienta de corte</option>
                                <option value="HG">Herramienta de golpe</option>
                                <option value="HM">Herramienta de mantenimimiento</option>
                                <option value="MA">Herramienta de maquinado</option>
                                <option value="HM">Herramienta de medición</option>
                                <option value="HM">Herramienta de montaje</option>
                                <option value="HN">Herramienta de neumaticos</option>
                                <option value="HS">Herramienta de seguridad</option>
                                <option value="HS">Herramienta de sujeción</option>
                                <option value="HT">Herramienta de torno</option>
                                <option value="HE">Herramienta Electrica</option>
                                <option value="HM">Herramienta manual</option>
                                </select>
                            </div>  

                            <div class="col-md-6" id="dimensionHerramienta" style="display:none;">
                                <label for="dimension_herramienta" class="form-label">Dimensión</label>
                                <input type="number" class="form-control" id="dimension_herramienta">
                            </div>

                            <div class="col-md-12" id="condicionHerramienta" style="display:none;">
                                <label for="condicion_herramienta" class="form-label">Condición</label>
                                <input type="text" class="form-control" id="condicion_herramienta">
                            </div>



                            <div class="col-md-12" id="tipoMaquina" style="display:none;">
                                <label for="tipo_maquina" class="form-label">Tipo de maquina</label>
                                <input type="text" class="form-control" id="tipo_maquina">
                            </div>

                            <div class="col-md-8" id="tipoInsumo" style="display:none;">
                                <label for="tipo_insumo" class="form-label">Tipo de insumo</label>
                                <input type="text" class="form-control" id="tipo_insumo">
                            </div>

                            <div class="col-md-4" id="capacidadInsumo" style="display:none;">
                                <label for="capacidad_insumo" class="form-label">Capacidad</label>
                                <input type="number" class="form-control" id="capacidad_insumo">
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
   
    var otroTipoDiv = document.getElementById('otroTipo');


    var tipo_maquina=document.getElementById('tipoMaquina');

    var tipo_insumo=document.getElementById('tipoInsumo');

    var capacidad_insumo=document.getElementById('capacidadInsumo');

    var dimension_herramienta=document.getElementById('dimensionHerramienta');

    var condicion_herramienta=document.getElementById('condicionHerramienta');



    tipoSelect.addEventListener('change', function() {
    switch (this.value) {
        case 'Herramientas':
            showElement(otroTipoDiv);
            showElement(dimension_herramienta);
            showElement(condicion_herramienta);
            hideElements([tipo_maquina, tipo_insumo,capacidad_insumo]);
            break;
        case 'Maquinaria':
            showElement(tipo_maquina);
            hideElements([otroTipoDiv, tipo_insumo,capacidad_insumo,dimension_herramienta,condicion_herramienta]);
            break;
        case 'Insumos':
            showElement(tipo_insumo);
            showElement(capacidad_insumo);
            hideElements([otroTipoDiv, tipo_maquina,dimension_herramienta,condicion_herramienta]);
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
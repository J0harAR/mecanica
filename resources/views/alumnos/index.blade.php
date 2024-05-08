
@extends('layouts.app')

@section('content')


<button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modal"><i class="ri-add-line"></i></button>

<!-- Vertically centered Modal -->
<div class="modal fade" id="modal" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Agregar alumno</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                   
                   
                    <div class="modal-body">
                        <form class="row g-3" action="{{route('alumnos.store')}}" method="POST">
                        @csrf
                    
                            <div class="col-md-12">
                                <label for="estatus" class="form-label">Numero de control</label>
                                <input type="text" class="form-control" id="no_control" name="no_control">

                            </div>
                                                            
                            <div class="col-md-6" id="dimensionHerramienta" >
                                <label for="dimension_herramienta" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>

                            <div class="col-md-12" id="condicionHerramienta" >
                                <label for="condicion_herramienta" class="form-label">Apellido Parterno</label>
                                <input type="text" class="form-control" id="apellido_p" name="apellido_p">
                            </div>


                            <div class="col-md-8" id="tipoInsumo" >
                                <label for="tipo_insumo" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_m"  name="apellido_m">
                            </div>

                            <div class="col-md-4" id="capacidadInsumo" >
                                <label for="capacidad_insumo" class="form-label">Curp</label>
                                <input type="text" class="form-control" id="curp" name="curp">
                            </div>

                            <div class="col-md-12" id="capacidadInsumo" >
                                <label for="capacidad_insumo" class="form-label">Grupo</label>
                                <select multiple class="form-control" id="grupos[]" name="grupos[]">
                                @foreach ($grupos as $grupo)
                                    <option value="{{$grupo->clave}}"> {{$grupo->clave}}  </option>                                     
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Guardar</button>                               
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->

@endsection
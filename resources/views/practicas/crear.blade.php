@extends('layouts.app')

@section('content')


<!-- Vertical Form -->
    <form class="row g-3" action="{{route('practicas.store')}}" method="POST">
        @csrf

            <div class="col-12">
                <label for="inputNanme4" class="form-label">No.Practica </label>
                <input type="text" class="form-control" name="codigo_practica" required>
            </div>

            <div class="col-md-12">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" class="form-select" required name="docente">
                    <option selected disabled>Selecciona un docente</option>
                    @foreach ( $docentes as  $docente)
                        <option value="{{$docente->rfc}}">{{$docente->persona->nombre}}</option>
                        
                    @endforeach
                      
                      
                </select>

            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Nombre de la practica</label>
                <input type="text" class="form-control" name="nombre_practica" required>
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Objetivo</label>
                <input type="text" class="form-control" name="objetivo" required>
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Introduccion</label>
                <input type="text" class="form-control" name="introduccion" required>
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Fundamento</label>
                <input type="text" class="form-control" name="fundamento" required>
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Referencias</label>
                <input type="text" class="form-control" name="referencias" required>
            </div>  

            <div class="row mb-3">
                  <label class="col-sm-12 col-form-label">Articulos</label>
                  <div class="col-sm-10">
                    <select class="form-select" multiple aria-label="multiple select example" name="articulos[]">
                    <option selected>Open this select menu</option>
                    @foreach ($catalogo_articulos as $articulo)
                      <option value="{{$articulo->id_articulo}}">{{$articulo->nombre}}</option>
                    @endforeach 
                    
                    </select>
                  </div>
            </div>




            <div class="text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
           
        </form><!-- End Vertical Form -->


@endsection
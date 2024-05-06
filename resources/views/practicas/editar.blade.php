@extends('layouts.app')

@section('content')

<!-- Vertical Form -->
<form class="row g-3" action="{{route('practicas.update',['id'=>$practica->id_practica])}}" method="POST">
        @method('PATCH')
        @csrf
            <div class="col-12">
                <label for="inputNanme4" class="form-label">No.Practica </label>
                <input type="text" class="form-control" name="codigo_practica" value="{{$practica->id_practica}}"required >
            </div>

            <div class="col-md-12">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" class="form-select" required name="docente">
                    <option  disabled>Selecciona un docente</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente->rfc }}" {{ $practica->docente->rfc === $docente->rfc ? 'selected' : '' }}>
                            {{ $docente->persona->nombre }}
                        </option>
                    @endforeach
                      
             
                </select>

            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Nombre de la practica</label>
                <input type="text" class="form-control" name="nombre_practica" required value="{{$practica->nombre}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Objetivo</label>
                <input type="text" class="form-control" name="objetivo" required value="{{$practica->objetivo}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Introduccion</label>
                <input type="text" class="form-control" name="introduccion" required value="{{$practica->introduccion}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Fundamento</label>
                <input type="text" class="form-control" name="fundamento" required  value="{{$practica->fundamento}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Referencias</label>
                <input type="text" class="form-control" name="referencias" required  value="{{$practica->referencias}}">
            </div>  

            <div class="row mb-3">
                  <label class="col-sm-12 col-form-label">Articulos</label>
                  <div class="col-sm-10">
                    <select class="form-select" multiple aria-label="multiple select example" name="articulos[]">
                    <option disabled>Open this select menu</option>
                    @foreach ($articulos as $articulo)
                        <option value="{{ $articulo->id_articulo }}" {{ $practica->catalogo_articulos->contains('id_articulo', $articulo->id_articulo) ? 'selected' : '' }}>
                            {{ $articulo->nombre }}
                        </option>
                    @endforeach               
                    </select>
                  </div>
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label">Estatus</label>
                <input type="text" class="form-control" name="referencias" required  value="{{$practica->referencias}}">
            </div>  



            <div class="text-center">
                <button type="submit" class="btn btn-primary">Editar</button>
            </div>
           
        </form><!-- End Vertical Form -->









@endsection
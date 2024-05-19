@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-pen "></i> Editar práctica
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('practicas.index')}}" class="text-decoration-none text-primary">
                            <i class="fas fa-book me-1"></i> Prácticas
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-pen "></i> Editar práctica
                    </li>
                </ol>
            </nav>
<!-- Vertical Form -->
<form class="row g-3" action="{{route('practicas.update',['id'=>$practica->id_practica])}}" method="POST">
        @method('PATCH')
        @csrf
            <div class="col-12">
                <label for="inputNanme4" class="form-label"><i class="fas fa-id-badge me-2"></i>No.Practica </label>
                <input type="text" class="form-control" name="codigo_practica" value="{{$practica->id_practica}}"required >
            </div>

            <div class="col-md-12">
                <label for="tipo" class="form-label"><i class="fas fa-chalkboard-teacher me-2"></i>Docente</label>
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
                <label for="inputNanme4" class="form-label"><i class="fas fa-id-badge me-2"></i>Nombre de la practica</label>
                <input type="text" class="form-control" name="nombre_practica" required value="{{$practica->nombre}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label"><i class="fas fa-bullseye me-2"></i>Objetivo</label>
                <input type="text" class="form-control" name="objetivo" required value="{{$practica->objetivo}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label"><i class="fas fa-file-alt me-2"></i>Introduccion</label>
                <input type="text" class="form-control" name="introduccion" required value="{{$practica->introduccion}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label"><i class="fas fa-gavel me-2"></i>Fundamento</label>
                <input type="text" class="form-control" name="fundamento" required  value="{{$practica->fundamento}}">
            </div>

            <div class="col-12">
                <label for="inputNanme4" class="form-label"><i class="fas fa-bookmark me-2"></i>Referencias</label>
                <input type="text" class="form-control" name="referencias" required  value="{{$practica->referencias}}">
            </div>  

            <div class="row mb-3">
                  <label class="col-sm-12 col-form-label"><i class="fas fa-boxes me-2"></i>Articulos</label>
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
                <button type="submit" class="btn btn-primary">  Guardar  </button>
            </div>
           
        </form><!-- End Vertical Form -->









@endsection
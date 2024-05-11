@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
 <div class="col-lg-12">
        <!-- Card with header and footer -->
        <div class="card">
                    <div class="card-header">Datos del grupo</div>
                    <div class="card-body mt-2">             
                        <form action="{{route('grupos.store')}}" method="POST" class="row g-3">
                            @csrf                       
                                <div class="col-md-8">
                                    <label for="" class="form-label">Asignatura</label>
                                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="asignatura">
                                        <option selected>Open this select menu</option>
                                        @foreach ($asignaturas as $asignatura)
                                            <option value="{{$asignatura->clave}}">{{$asignatura->clave}}-{{$asignatura->nombre}}</option>    
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Grupo</label>
                                    <input type="text" name="clave_grupo" id="clave_grupo" class="form-control">
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-3 mr-2">
                                    <a href="{{route('grupos.index')}}" class="btn btn-light btn-sm text-black me-1">
                                        <i class="bi bi-arrow-left"></i>
                                        Atrás
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-sm ">
                                        <i class="bi bi-check2"></i>
                                        Guardar
                                    </button>
                                </div>

                        </form>
                    </div>
        </div><!-- End Card with header and footer -->
  </div>
</div>



@endsection
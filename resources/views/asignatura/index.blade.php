
@extends('layouts.app')

@section('content')
<style>
.btn-custom-purple {
    --primary-color-500: 72, 61, 139; 
    --tw-text-opacity: 1; 

    color: rgb(var(--primary-color-500) / var(--tw-text-opacity));
    background-color: rgba(var(--primary-color-500), 1); 
    border-color: rgba(var(--primary-color-500), 1);
}

.btn-custom-purple:hover {
    background-color: rgba(var(--primary-color-500), 0.8); 
    border-color: rgba(var(--primary-color-500), 0.8); 
}
</style>

<div class="row justify-content-center">
 <div class="col-lg-12">
        <!-- Card with header and footer -->
        <div class="card">
                    <div class="card-header">Datos de la asignatura</div>
                    <div class="card-body mt-2">             
                      <form action="{{route('asignatura.store')}}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-8">
                                <label for="" class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" id="nombre" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label for="" class="form-label">Clave de asignatura</label>
                                <input type="text" name="clave" id="clave" class="form-control">
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-3"> 
                              <button type="submit" class="btn btn-primary btn-sm">
                              <i class="bi bi-check2"></i>
                                Guardar
                              </button>
                            </div>
                      
                        </form>
                    </div>
        </div><!-- End Card with header and footer -->
  </div>
</div>




<table class="table table-sm table-bordered ">
  <thead>
    <tr class="table-light  text-center">
      <th scope="col">Clave</th>
      <th scope="col">Nombre completo</th>
      <th scope="col">Editar</th>
      <th scope="col">Eliminar</th>

    </tr>
  </thead>
  <tbody>
    @foreach ($asignaturas as $asignatura)
    <tr class="text-center">
        <td scope="row">{{$asignatura->clave}}</td>
        <td>{{$asignatura->nombre}}</td>
        <td>

               <a href="{{route('asignatura.edit', ['id'=>$asignatura->clave])}}" class="btn btn-custom-purple btn-sm text-white">
                <i class="fas fa-edit mr-2"></i>
                Editar
                </a>
        </td>
        <td>
            <form action="{{route('asignatura.destroy', ['id'=>$asignatura->clave])}}" method="POST">
                @csrf
                @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                      <i class="fas fa-trash mr-2"></i>
                      Eliminar

                    </button>
            </form>
           
        </td>
    </tr>
    @endforeach
    
  </tbody>
</table>

@endsection
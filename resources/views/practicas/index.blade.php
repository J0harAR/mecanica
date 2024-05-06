@extends('layouts.app')

@section('content')
<style>
  .folder-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
  }

  .folder-header {
    height: 50px;
    background-color: #007bff;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }

  .folder-content {
    padding: 20px;
  }

  .folder-content h2 {
    margin-top: 0;
  }

  .folder-content p {
    margin-bottom: 0;
  }

  /* Efecto de elevación */
  .folder-card:hover {
    transform: translateY(-5px);
  }
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Practicas</h1>
      
        <a href="{{route('practicas.create')}}"  class="btn btn-outline-primary btn-sm" ><i class="ri-add-line"></i>Registrar práctica</a>
      
    </div>


    <div class="container">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach ($practicas as $practica)
    <div class="col">
      <a href="{{route('practicas.show',['id'=>$practica->id_practica])}}">
        <div class="folder-card">
          <div class="folder-header"></div>
          <div class="folder-content">
            <h2>{{ $practica->id_practica }}</h2>
            <p>{{ $practica->nombre }}</p>
            <!-- Botón para editar -->
            <a href="{{ route('practicas.edit', ['id'=>$practica->id_practica]) }}">
              <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
            </a>
            <form action="{{ route('practicas.destroy', ['id'=>$practica->id_practica]) }}" method="POST" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> </button>
            </form>
        
          </div>
        </div>
      </a>
    </div>
    @endforeach
  </div>
</div>








@endsection
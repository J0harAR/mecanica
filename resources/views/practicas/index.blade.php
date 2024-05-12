@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Practicas</h1>
      
        <a href="{{route('practicas.create')}}"  class="btn btn-outline-primary btn-sm" ><i class="ri-add-line"></i>Registrar práctica</a>
      
    </div>


    <div class="container">
    @if($practicas->isEmpty())
        <div class="alert alert-secondary" role="alert">
            No hay prácticas registradas actualmente. <a href="{{ route('practicas.create') }}" class="alert-link">¡Crea una nueva práctica!</a>
        </div>
    @else
  <div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach ($practicas as $practica)
    <div class="col">
      <a href="{{route('practicas.show',['id'=>$practica->id_practica])}}">
        <div class="folder-card">
          <div class="folder-header"></div>
          <div class="folder-content">
            <h2>{{ $practica->id_practica }}</h2>
            <p>{{ $practica->nombre }}</p>
        
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






@endif
</div>

@endsection
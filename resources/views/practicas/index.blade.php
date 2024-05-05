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



<div class="container">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach ($practicas as $practica)
    <div class="col">
      <div class="folder-card">
        <div class="folder-header"></div>
        <div class="folder-content">
          <h2>{{ $practica->id_practica }}</h2>
          <p>{{ $practica->nombre }}</p>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>









@endsection
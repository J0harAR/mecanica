@extends('layouts.app')

@section('content')
<section class="section">
  <div class="container">
    <!-- Encabezado de bienvenida -->
    <div class="jumbotron text-center bg-light p-4 rounded mb-2 shadow-sm">
      <h1 class="display-4 text-primary fw-bold">Bienvenido al sistema </h1>
      <p class="lead text-secondary">Administra eficientemente tus usuarios, roles, inventarios y más.</p>
      <hr class="my-2">
      <p class="text-muted mb-1">Utiliza los accesos rápidos para navegar fácilmente por el sistema.</p>
    </div>

    <!-- Accesos rápidos -->
    <div class="row text-center mb-3">
      <div class="col-lg-3 col-md-6 mb-2">
        <a href="/practicas" class="btn btn-outline-primary btn-block py-3 shadow-sm rounded">
          <i class="bi bi-people display-4"></i>
          <h5 class="mt-2">Prácticas</h5>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 mb-2">
        <a href="/docentes" class="btn btn-outline-primary btn-block py-3 shadow-sm rounded">
          <i class="bi bi-person-lock display-4"></i>
          <h5 class="mt-2">Docentes</h5>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 mb-2">
        <a href="/inventario" class="btn btn-outline-primary btn-block py-3 shadow-sm rounded">
          <i class="bi bi-box-seam display-4"></i>
          <h5 class="mt-2">Inventario</h5>
        </a>
      </div>
      <div class="col-lg-3 col-md-6 mb-2">
        <a href="/mantenimiento" class="btn btn-outline-primary btn-block py-3 shadow-sm rounded">
          <i class="bi bi-tools display-4"></i>
          <h5 class="mt-2">Mantenimiento</h5>
        </a>
      </div>
    </div>

    <!-- Tarjetas informativas -->
    <div class="row">
      @can('ver-usuarios')
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm hover-card h-100 rounded">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title1 text-primary mb-2">Usuarios</h5>
              <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-right text-primary"><i class="bi bi-people display-4"></i></h2>
                <div class="text-center">
                  <h2 class="text-dark"><span>{{$cant_usuarios}}</span></h2>
                  <p class="mb-0"><a href="/usuarios" class="text-primary">Ver más...</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endcan

      @can('ver-rol')
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm hover-card h-100 rounded">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title1 text-primary mb-2">Roles</h5>
              <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-right text-primary"><i class="bi bi-person-lock display-4"></i></h2>
                <div class="text-center">
                  <h2 class="text-dark"><span>{{$cant_roles}}</span></h2>
                  <p class="mb-0"><a href="/roles" class="text-primary">Ver más...</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endcan
    </div>

    
  </div>
</section>
@endsection

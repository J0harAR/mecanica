@extends('layouts.app')

@section('content')
<section class="section">
  <div class="container">
    <!-- Encabezado de bienvenida -->
    <div class=" text-center  p-5 rounded mb-4 shadow-sm">
      <h1 class="display-4 fw-bold">¡Bienvenido a tu sistema!</h1>
      <hr class="my-4 bg-light">
      <p class="mb-0">Utiliza los accesos rápidos para navegar fácilmente por el sistema.</p>
    </div>

    <!-- Accesos rápidos -->
    <div class="row text-center mb-4">
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/practicas" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-people display-3"></i>
          <h5 class="mt-3">Prácticas</h5>
        </a>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/docentes" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-person-lock display-3"></i>
          <h5 class="mt-3">Docentes</h5>
        </a>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/inventario" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-box-seam display-3"></i>
          <h5 class="mt-3">Inventario</h5>
        </a>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/mantenimiento" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-tools display-3"></i>
          <h5 class="mt-3">Mantenimiento</h5>
        </a>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/reportes" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-clipboard-data display-3"></i>
          <h5 class="mt-3">Reportes</h5>
        </a>
      </div>
      <div class="col-lg-2 col-md-4 mb-4">
        <a href="/prestamos" class="btn btn-outline-light btn-block py-4 shadow-sm rounded-lg">
          <i class="bi bi-arrow-left-right display-3"></i>
          <h5 class="mt-3">Préstamos</h5>
        </a>
      </div>
    </div>

    <!-- Tarjetas informativas -->
    <div class="row">
      @can('ver-usuarios')
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100 rounded-lg">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title1 text-primary mb-3">Usuarios</h5>
              <div class="d-flex justify-content-between align-items-center">
                <div class="icon-wrapper">
                  <i class="bi bi-people display-3 text-primary"></i>
                </div>
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
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100 rounded-lg">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title1 text-primary mb-3">Roles</h5>
              <div class="d-flex justify-content-between align-items-center">
                <div class="icon-wrapper">
                  <i class="bi bi-shield-lock display-3 text-primary"></i>
                </div>
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
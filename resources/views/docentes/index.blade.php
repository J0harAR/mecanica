@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="fas fa-chalkboard-teacher me-1"></i>Docentes
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-chalkboard-teacher me-1"></i>Docentes
          </li>
        </ol>
      </nav>
    </div>
    
    <div class="btn-group" role="group">
    <a href="{{ route('docentes.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Añadir docente
</a>
      <a href="{{ route('docentes.asigna') }}" class="btn btn-outline-primary">
        <i class="fas fa-chalkboard-teacher me-1"></i>Añadir asignatura
      </a>
      <a href="{{ route('docentes.eliminacion_asignacion') }}" class="btn btn-outline-primary">
        <i class="fas fa-trash-alt"></i> Remover asignatura
      </a>
    </div>
  </div>


    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-lg rounded-3 border-0">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle"
          style="border-collapse: separate; border-spacing: 0 10px;">
          <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
            <tr>
          <th scope="col">RFC</th>
          <th scope="col">Curp</th>
          <th scope="col">Nombre</th>
          <th scope="col">Area</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($docentes as $docente)
          <tr>
          <td scope="row">{{$docente->rfc}}</td>
            <td scope="row">{{$docente->curp}}</td>
            <td>{{$docente->persona->nombre}} {{$docente->persona->apellido_p}} {{$docente->persona->apellido_m}}</td>
            <td>{{$docente->area}}</td>
            <td>
              <a href="{{route('docentes.show', $docente->rfc)}}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-vcard"></i> Datos generales
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    </div>
    </div>
  </div>
</div>
@endsection

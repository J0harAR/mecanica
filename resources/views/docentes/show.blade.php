@extends('layouts.app')

@section('content')
@can('ver-docente')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0 text-primary">
        <i class="bi bi-person-vcard me-1"></i>Datos Generales
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('docentes.index') }}" class="text-decoration-none text-primary">
              <i class="fas fa-chalkboard-teacher me-1"></i>Docentes
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <i class="bi bi-person-vcard me-1"></i>Datos Generales
          </li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card shadow-sm">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img src="{{ asset($docente->foto) }}" alt="Profile" class="rounded-circle mb-3"
              style="width: 150px; height: 150px;">
            <h2>{{ $docente->persona->nombre }}</h2>
          </div>
        </div>
      </div>
      <div class="col-xl-8">
        <div class="card shadow-sm">
          <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab"
                  data-bs-target="#profile-overview">Descripción</button>
              </li>
              @can('editar-docente')
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar</button>
              </li>
              @endcan
            </ul>
            <div class="tab-content pt-2">
              <div class="tab-pane fade show active" id="profile-overview">
                <h5 class="card-title1 fw-bold">Detalles del perfil</h5>
                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 fw-bold">Nombre completo</div>
                  <div class="col-lg-9 col-md-8">{{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }}
                    {{ $docente->persona->apellido_m }}</div>
                </div>
                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 fw-bold">CURP</div>
                  <div class="col-lg-9 col-md-8">{{ $docente->persona->curp }}</div>
                </div>
                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 fw-bold">RFC</div>
                  <div class="col-lg-9 col-md-8">{{ $docente->rfc }}</div>
                </div>
                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 fw-bold">Área</div>
                  <div class="col-lg-9 col-md-8">{{ $docente->area }}</div>
                </div>
                <div class="row mb-3">
                  <div class="col-lg-3 col-md-4 fw-bold">Teléfono</div>
                  <div class="col-lg-9 col-md-8">{{ $docente->telefono }}</div>
                </div>
              </div>

              @can('editar-docente')
              <div class="tab-pane fade" id="profile-edit">
                <form action="{{ route('docentes.update', ['id' => $docente->rfc]) }}" method="POST"
                  enctype="multipart/form-data" class="mt-3">
                  @csrf
                  @method('PUT')
                  <div class="mb-4 text-center">
                    <img src="{{ asset($docente->foto) }}" alt="Profile" class="img-thumbnail mb-2"
                      style="width: 120px; height: 120px;">
                    <div class=" col-md-10 center">
                      <input type="file" class="form-control" id="profileImage" name="foto">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="fullName" class="col-md-4 col-form-label">Nombre</label>
                    <div class="col-md-6">
                      <input name="nombre" type="text" class="form-control" id="fullName"
                        value="{{ $docente->persona->nombre }}">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="apellido_p" class="col-md-4 col-form-label">Apellido Paterno</label>
                    <div class="col-md-6">
                      <input name="apellido_p" type="text" class="form-control" id="apellido_p"
                        value="{{ $docente->persona->apellido_p }}">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="apellido_m" class="col-md-4 col-form-label">Apellido Materno</label>
                    <div class="col-md-6">
                      <input name="apellido_m" type="text" class="form-control" id="apellido_m"
                        value="{{ $docente->persona->apellido_m }}">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="curp" class="col-md-4 col-form-label">CURP</label>
                    <div class="col-md-6">
                      <input name="curp" type="text" class="form-control" id="curp"
                        value="{{ $docente->persona->curp }}">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="area" class="col-md-4 col-form-label">Área</label>
                    <div class="col-md-6">
                      <input name="area" type="text" class="form-control" id="area" value="{{ $docente->area }}">
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="telefono" class="col-md-4 col-form-label">Teléfono</label>
                    <div class="col-md-6">
                      <input name="telefono" type="text" class="form-control" id="telefono"
                        value="{{ $docente->telefono }}">
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                  </div>
                </form>
              </div>
              @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="card shadow-lg rounded-3 border-0 mt-4">
    <div class="card-body p-4">
      <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
          <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
            <tr>
              <th scope="col" class="text-center">Clave de la materia</th>
              <th scope="col" class="text-center">Nombre completo</th>
              <th scope="col" class="text-center">Grupo</th>
              <th scope="col" class="text-center">Periodo</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($asignaturas as $asig)
        <tr class="text-center">
          <td>{{ $asig->asignatura->clave }}</td>
          <td>{{ $asig->asignatura->nombre }}</td>
          <td>{{ $asig->clave_grupo }}</td>
          <td>{{ $asig->clave_periodo}}</td>
        </tr>
      @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endcan
@endsection
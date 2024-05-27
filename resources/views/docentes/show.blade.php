@extends('layouts.app')

@section('content')

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
          <img src="{{ asset($docente->foto) }}" alt="Profile" class="rounded-circle">
          <h2>{{ $docente->persona->nombre }}</h2>
        </div>
      </div>
    </div>
    <div class="col-xl-8">
      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Descripción</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar</button>
            </li>
          </ul>
          <div class="tab-content pt-2">
            <div class="tab-pane fade show active profile-overview" id="profile-overview">
              <h5 class="card-title1">Detalles del Perfil</h5>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Nombre completo</div>
                <div class="col-lg-9 col-md-8">{{ $docente->persona->nombre }} {{ $docente->persona->apellido_p }} {{ $docente->persona->apellido_m }}</div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">CURP</div>
                <div class="col-lg-9 col-md-8">{{ $docente->persona->curp }}</div>
              </div>
              
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Área</div>
                <div class="col-lg-9 col-md-8">{{ $docente->area }}</div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Teléfono</div>
                <div class="col-lg-9 col-md-8">{{ $docente->telefono }}</div>
              </div>
            </div>
            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
              <!-- Profile Edit Form -->
              <form action="{{ route('docentes.create', $docente->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                  <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Imagen de Perfil</label>
                  <div class="col-md-8 col-lg-9">
                    <img src="{{ asset($docente->foto) }}" alt="Profile">
                    <div class="pt-2">
                      <input type="file" class="form-control" id="profileImage" name="foto">
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre Completo</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="nombre" type="text" class="form-control" id="fullName" value="{{ $docente->persona->nombre }}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="apellido_p" class="col-md-4 col-lg-3 col-form-label">Apellido Paterno</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="apellido_p" type="text" class="form-control" id="apellido_p" value="{{ $docente->persona->apellido_p }}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="apellido_m" class="col-md-4 col-lg-3 col-form-label">Apellido Materno</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="apellido_m" type="text" class="form-control" id="apellido_m" value="{{ $docente->persona->apellido_m }}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="curp" class="col-md-4 col-lg-3 col-form-label">CURP</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="curp" type="text" class="form-control" id="curp" value="{{ $docente->persona->curp }}">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <label for="area" class="col-md-4 col-lg-3 col-form-label">Área</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="area" type="text" class="form-control" id="area" value="{{ $docente->area }}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="telefono" class="col-md-4 col-lg-3 col-form-label">Teléfono</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="telefono" type="text" class="form-control" id="telefono" value="{{ $docente->telefono }}">
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
              </form><!-- End Profile Edit Form -->
            </div>
          </div><!-- End Bordered Tabs -->
        </div>
      </div>
    </div>
  </div>
</section>

<div class="card shadow-lg rounded-3 border-0">
  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle" style="border-collapse: separate; border-spacing: 0 10px;">
        <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
          <tr>
            <th scope="col" class="text-center">Clave de la materia</th>
            <th scope="col" class="text-center">Nombre completo</th>
            <th scope="col" class="text-center">Grupo</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($docente->grupos as $grupo)
          <tr class="text-center">
            <td>{{ $grupo->asignaturas[0]->clave }}</td>
            <td>{{ $grupo->asignaturas[0]->nombre }}</td>
            <td>{{ $grupo->clave }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

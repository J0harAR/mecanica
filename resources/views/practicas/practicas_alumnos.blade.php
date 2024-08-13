@extends('layouts.app')
@section('content')
@can('ver-practicas') 
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="bi bi-journal me-1"></i> Prácticas de los alumnos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-journal me-1"></i> Prácticas
                    </li>
                </ol>
            </nav>
        </div>
        @can('crear-practica')
        <a href="{{ route('practicasAlumno.create') }}" class="btn btn-tecnm">
            <i class="fas fa-plus-circle me-1"></i>Registrar practica del alumno
        </a>
        @endcan
        
    </div>

    @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" id="success-alert">
    {{ session('success') }}
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    window.setTimeout(function () {
      const successAlert = document.getElementById("success-alert");
      if (successAlert) successAlert.style.display = 'none';
    }, 3000);
    });
  </script>
@endif
    
    @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                </div>
    @endif






<form method="POST" class="border p-4 rounded shadow-sm row g-3 mb-4" id="filterForm">
    @csrf
  
        <div class="col-md-12">
            <label for="asignatura" class="form-label">Practica</label>
            <select class="form-select" id="practica" name="practica">
                <option value="">Seleccione la practica</option>
                @foreach ($practicas as $practica)
                    <option value="{{ $practica->id_practica }}">{{ $practica->id_practica }}</option>
                @endforeach
            </select>
        </div>
       
</form>





<div class="card shadow-lg rounded-3 border-0">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table  table-striped table-hover table-bordered shadow-sm rounded align-middle"
                   style="border-collapse: separate; border-spacing: 0 10px;">
                <thead class="bg-primary text-white position-sticky top-0" style="z-index: 1;">
                    <tr>
                        <th>Número de control</th>
                        <th>Nombre completo</th>
                        <th>Número de equipo</th>
                        <th>Fecha</th>
                        <th>Hora de entrada</th>
                        <th>Hora de salida</th>
                    </tr>
                </thead>
                <tbody id="cuerpo">
                    <!-- Las filas se agregarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>
  
  
</div>

<script>
$(document).ready(function () {
    $('#practica').on('change', function () {
        let practicaId = $(this).val();

        if (practicaId) {
            $.ajax({
                url: '{{ route("practicas.alumno.obtener") }}', // Ajusta esta ruta a la que corresponda en tu aplicación
                type: 'GET',
                data: { id: practicaId },
                success: function (data) {
                    let cuerpoTabla = $('#cuerpo');
                    cuerpoTabla.empty(); 

                  
                    if (Array.isArray(data)) {
                        data.forEach(function(alumno) {
                            let AlumnoTr = `
                                <tr>
                                    <td>${alumno.no_control}</td>
                                    <td>${alumno.persona.nombre} ${alumno.persona.apellido_p} ${alumno.persona.apellido_m}</td>
                                    <td>${alumno.pivot.no_equipo}</td>
                                    <td>${alumno.pivot.fecha}</td>
                                    <td>${alumno.pivot.hora_entrada}</td>
                                    <td>${alumno.pivot.hora_salida}</td>
                                </tr>
                            `;
                            cuerpoTabla.append(AlumnoTr);
                        });
                    } else {
                        console.error('El formato de los datos no es un array.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los alumnos:', error);
                }
            });
        }
    });
});
</script>

@endcan
@endsection
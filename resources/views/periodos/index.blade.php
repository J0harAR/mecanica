@extends('layouts.app')


@section('content')

@can('ver-periodos')
    

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users"></i> Periodos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('grupos.index')}}" class="text-decoration-none text-primary">
                            <i class="fas fa-users me-1"></i> Cursos
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-plus"></i> Periodos
                    </li>
                </ol>
            </nav>
        </div>
  
<div>
    @can('crear-periodo')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
      <i class="ri-add-line"></i> Añadir periodo
    </button>
    @endcan

</div>
</div>
    
@include('layouts.notificaciones') 

@include('periodos.modals.create')






@can('ver-periodos')
    
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body py-4">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th >Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $periodo)
                        <tr>
                        @php
                            $year = explode('-', $periodo->clave)[0];
                        @endphp
                            <td> 
                            {{mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_inicio)->locale('es')->isoFormat('MMMM')) }} -
                            {{ mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_final)->locale('es')->isoFormat('MMMM')) }}/{{$year}}
                            
                            </td>
                            <td>
                                @can('borrar-periodo')                                                             
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $periodo->clave}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan

                                @can('editar-periodo')                                                                 
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-{{$periodo->clave}}">
                                    <i class="bi bi-pencil-square "></i>
                                </button>
                                @endcan                          
                            </td>
                        </tr>
                        @include('periodos.modals.edit')

                        @include('periodos.modals.delete')    
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endcan
@endcan


@endsection
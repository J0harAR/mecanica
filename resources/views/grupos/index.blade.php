@extends('layouts.app')

@section('content')
@can('ver-grupos')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-users-cog"></i> Administración de Grupos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light shadow-sm p-3 mb-4 rounded">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-primary">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-users-cog"></i> Administración de Grupos
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                @can('crear-grupo')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creargrupoModal">
                        <i class="fas fa-user-plus"></i> Registrar grupo
                    </button>

                    @include('grupos.modals.create')
                    
                @endcan

            </div>
        </div>
        

        @include('layouts.notificaciones') 


        @can('ver-grupos')

            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered shadow-sm rounded align-middle datatable"
                            style="border-collapse: separate; border-spacing: 0 10px;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Asignatura</th>
                                    <th scope="col">Clave del Grupo</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grupos as $grupo)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{$grupo->clave_periodo}}</td>
                                        <td>
                                            @if ($grupo->asignatura)
                                                {{$grupo->asignatura->clave}}//{{$grupo->asignatura->nombre}}
                                            @else
                                                Sin Asignatura
                                            @endif
                                            
                                        </td>
                                        <td>{{ $grupo->clave_grupo }}</td>

    
                                        <td>
                                            

                                            @can('borrar-grupo')
                                                <button type="button" class="btn btn-danger " data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $grupo->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </td>



                                    </tr>

                                    @include('grupos.modals.delete')                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endcan
        </div>
@endcan




    @endsection
@extends('layouts.app')

@section('content')
<style>
    .custom-card {
      max-width: 700px;
      margin: 0 auto;
      margin-top: 100px;
    }
</style>


@can('crear-role')
  
  <div class="pagetitle">
    <h1>Roles</h1>
    <nav>
     <ol class="breadcrumb">
     <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/roles">Roles</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>
    </nav>
  </div><!-- End Page Title -->

<div class="card custom-card">
    <div class="card-body">
        <h5 class="card-title">Registrar Rol</h5>

        <!-- Vertical Form -->
        <form class="row g-3" action="{{route('roles.store')}}" method="POST">
        @csrf
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Nombre del rol  </label>
                  <input type="text" class="form-control" name="name" required>
                </div>
               
                <div class="col-12">
                    <div class="form-group">
                        <label for="inputPassword4" class="form-label">Permisos del rol </label>
                            <br>
                            @foreach ($permission as $value)
                                <label class="form-check-label">
                                <input type="checkbox" name="permission[]" value="{{$value->name}}" class="name">
                                {{$value->name}}
                                </label>
                            <br>
                            @endforeach
                    </div>
                </div>
               

            @can('crear-rol')
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
            @endcan
        </form><!-- End Vertical Form -->
        </div>
</div><!-- End card custom -->
@endcan

@endsection
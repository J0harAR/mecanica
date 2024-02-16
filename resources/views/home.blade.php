@extends('layouts.app')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Usuarios</h5>
              @php           
                    use App\Models\User;
                    $cant_usuarios=User::count(); 
              @endphp
                <div class="d-flex justify-content-between">
                    <h2 class="text-right"><i class="bi bi-people"></i></h2>
                    <div class="d-flex flex-column justify-content-between align-items-center"> 
                        <h2><span>{{$cant_usuarios}}</span></h2>
                        <p class="m-b-o text-right"><a href="/usuarios">Ver mas...</a></p>
                    </div>
                </div> 
            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Roles</h5>
                @php
                    use Spatie\Permission\Models\Role;
                    $cant_roles=Role::count();
                 @endphp
                <div class="d-flex justify-content-between">
                    <h2 class="text-right"><i class="bi bi-person-lock"></i></h2>
                    <div class="d-flex flex-column justify-content-between align-items-center"> 
                        <h2><span>{{$cant_roles}}</span></h2>
                        <p class="m-b-o text-right"><a href="/roles">Ver mas...</a></p>
                    </div> 
                </div> 
                
            </div>
          </div>
        </div>
    </div>
</section>


@endsection


<?php
        

        $maquinaria_mantenimiento = DB::table('insumos_maquinaria')
        ->whereColumn('cantidad_actual', '<=', 'cantidad_minima')
        ->get();
        $cantidad_maquinas=count($maquinaria_mantenimiento);
        $prestamos_pendientes=DB::table('prestamo')
        ->whereColumn('fecha_devolucion', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 1 DAY)'))
        ->where('estatus','Pendiente')
        ->get();
        $cantidad_prestamos=count($prestamos_pendientes);
        $total_notificaciones=$cantidad_maquinas + $cantidad_prestamos;

?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center"
  style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{route('home')}}" class="logo d-flex align-items-center">
      <img src="/assets/img/logo.png" alt="">
      <span class="d-none d-lg-block">Lab. Mecánica</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>

  </div><!-- End Logo -->


  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->
      <li class="nav-item dropdown">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">{{$total_notificaciones}}</span>
        </a><!-- End Notification Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
        
          Tienes {{$total_notificaciones}} nuevas notificaciones
            
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

 
          @foreach ($maquinaria_mantenimiento as $maquina)
            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Maquina:{{$maquina->maquinaria_id}}</h4>
                <p>Se necesita brindarle mantenimiento urgente</p>
                <p>Insumo:{{$maquina->insumo_id}} nivel bajo</p>
              </div>
          </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            @endforeach
        

          @foreach ($prestamos_pendientes as $prestamo)
          <li class="notification-item">
            <i class="bi bi-info-circle text-primary"></i>
            <div>
            <h4>Prestamo:{{$prestamo->id}} pendiente</h4>
                <p>Fecha de entrega cerca : {{$prestamo->fecha_devolucion}}</p>
                <p>Docente:{{$prestamo->id_docente}}</p>
                <p>Herramienta:{{$prestamo->id_herramientas}}</p>

              </div>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          @endforeach
        
        </ul>
        <!--  End Notification Dropdown Items -->

      </li><!-- End Notification Nav -->

      <li class="nav-item dropdown pe-3">
        <a class="nav-link d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
        </a><!-- End Profile Image Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header text-center">
            <h6>{{ Auth::user()->name }}</h6>
            <span>{{ Auth::user()->email }}</span><br>
            <span>{{ Auth::user()->roles->pluck('name')->first() }}</span>

          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i>
              {{ __('Salir') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>


          </li>
        </ul>
      </li><!-- End Profile Nav -->
    </ul><!-- End Profile Dropdown Items -->
  </nav><!-- End Header Nav -->
</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside class="sidebar in" id="main-sidebar" role="navigation">
  <div class="sidebar-outer">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{route('home')}}">
          <i class="bi bi-house"></i>
          <span>Dashboard</span>
        </a>
      </li>
      @canany(['ver-alumnos', 'ver-docentes'])
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('personas.*', 'docentes.*', 'alumnos.*') ? 'active' : 'collapsed' }}"
          data-bs-target="#personas-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person"></i><span>Personas</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="personas-nav" class="nav-content collapse " data-bs-parent="#personas-nav">
          <li>
            @can('ver-docentes')
                        
            <a class="collapsed" data-bs-toggle="collapse" href="#submenu-docente">
              <span>Docente</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            @endcan
            <ul id="submenu-docente" class="collapse">
             
              @can('ver-docentes')                           
              <li>
                <a href="{{route('docentes.index')}}">
                  <span>Ver docentes</span>
                </a>
              </li>
              @endcan
              
              @can('crear-docente')                           
              <li>
                <a href="{{route('docentes.create')}}">
                  <span>Registrar docente</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @can('ver-alumnos')            
          <li>
            <a href="{{ route('alumnos.index') }}">
              <span>Alumno</span>
            </a>
          </li>
          @endcan
        </ul>
      </li><!-- End Charts Nav -->
      @endcanany

      @canany(['ver-grupos','ver-asignaturas','ver-periodos'])
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cursos.*', 'asignatura.*', 'grupos.*', 'periodos.*') ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse" href="#cursos-nav">
          <i class="bi bi-book"></i><span>Cursos</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="cursos-nav" class="nav-content collapse" data-bs-parent="#parent-nav">
          <li>
            @can('ver-asignaturas')                     
            <a class="collapsed" data-bs-toggle="collapse" href="#submenu-asignatura">
              <span>Asignatura</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            @endcan
            <ul id="submenu-asignatura" class="collapse">

              @can('ver-asignaturas')     
              <li>
                <a href="{{route('asignatura.index')}}">
                  <span>Ver asignaturas</span>
                </a>
              </li>
              @endcan

              @can('crear-asignatura')    
              <li>
                <a href="{{route('asignatura.create')}}">
                  <span>Registrar asignatura</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          <li>
          @can('ver-grupos')      
            <a class="collapsed" data-bs-toggle="collapse" href="#submenu-grupos">
              <span>Grupos</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
          @endcan
            <ul id="submenu-grupos" class="collapse">
            @can('ver-grupos')     
              <li>
                <a href="{{route('grupos.index')}}">
                  <span>Ver grupos</span>
                </a>
              </li>
              @endcan
              @can('crear-grupo')     
              <li>
                <a href="{{route('grupos.create')}}">
                  <span>Registrar grupo</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>

          @can('ver-periodos')
          <li>
            <a href="{{route('periodos.index')}}">
              <span>Periodos</span>
            </a>
          </li>
          @endcan

        </ul>
      </li><!-- End Charts Nav -->
      @endcanany


      <li class="nav-heading">Páginas</li>

      @can('ver-usuarios')
      <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : 'collapsed' }}"
        href="{{ route('usuarios.index') }}">
        <i class="bi bi-people"></i>
        <span>Usuarios</span>
      </a>
      </li><!-- End Users Page Nav -->
    @endcan


      @can('ver-roles')
      <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : 'collapsed' }}"
        href="{{ route('roles.index') }}">
        <i class="bi bi-person-lock"></i>
        <span>Roles</span>
      </a>
      </li><!-- End Role Page Nav -->
    @endcan

      @can('ver-inventario')       
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : 'collapsed' }}"
          href="{{ route('inventario.index') }}">
          <i class="bi bi-box"></i>
          <span>Inventario</span>
        </a>
      </li><!-- End Role Page Nav -->
      @endcan

      @can('ver-mantenimientos')      
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('mantenimiento.*') ? 'active' : 'collapsed' }}"
          href="{{ route('mantenimiento.index') }}">
          <i class="bi bi-tools"></i>
          <span>Mantenimiento</span>
        </a>
      </li><!-- End Role Page Nav -->
      @endcan

      
      @can('ver-practicas')     
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('practicas.*') || request()->routeIs('practicasAlumno.*') ? 'active' : 'collapsed' }}"
          data-bs-target="#practicas-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal"></i><span>Prácticas</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="practicas-nav" class="nav-content collapse " data-bs-parent="#practicas-nav">
         
          @can('ver-practicas')         
          <li>
            <a href="{{ route('practicas.index') }}">
              <span>Ver prácticas</span>
            </a>
          </li>
          @endcan

          @can('crear-practica-alumno')                         
          <li>
            <a href="{{route('practicasAlumno.create')}}">
              <span>Registrar práctica del alumno</span>
            </a>
          </li>
          @endcan

        </ul>
      </li><!-- End Charts Nav -->
      @endcan
      
      @can('ver-prestamos')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : 'collapsed' }}"
          href="{{ route('prestamos.index') }}">
          <i class="bi bi-arrow-left-right"></i>
          <span>Préstamos</span>
        </a>
      </li><!-- End Role Page Nav -->
      @endcan
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const searchInput = document.getElementById('searchInput');

          searchInput.addEventListener('keyup', function () {
            const input = searchInput.value.toLowerCase();
            // Selector ajustado para incluir todos los elementos dentro de los menús colapsados
            const navLinks = document.querySelectorAll('#sidebar-nav a');

            navLinks.forEach(link => {
              const text = link.textContent.toLowerCase();
              const listItem = link.closest('li');

              if (text.includes(input)) {
                listItem.style.display = ''; // Muestra el ítem si coincide
                showParents(listItem); // Muestra todos los elementos padres si es necesario
              } else {
                listItem.style.display = 'none'; // Oculta el ítem si no coincide
              }
            });
          });

          // Función para mostrar todos los elementos padres del ítem actual
          function showParents(listItem) {
            while (listItem.id !== 'sidebar-nav') {
              if (listItem.tagName === 'LI') {
                listItem.style.display = '';
              }
              listItem = listItem.parentNode;
              if (listItem.tagName === 'UL') {
                // Si el UL es un menú colapsable, asegúrate de que esté expandido
                if (listItem.classList.contains('collapse')) {
                  listItem.classList.add('show');
                }
              }
            }
          }
        });
      </script>


</aside><!-- End Sidebar-->
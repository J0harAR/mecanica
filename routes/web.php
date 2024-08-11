<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CatalogoArticulosController;
use App\Http\Controllers\HerramientasController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\LectorController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Rutas del modulo de roles y permisos 

Route::group(['middleware' => ['auth']], function () {
    
    Route::resource('roles',RolController::class);
    Route::resource('usuarios',UsuarioController::class);
    
});



//Rutas del modulo de inventario
    //Parte de catalogo de articulos
Route::get('/articulos', [CatalogoArticulosController::class, 'index']);
    

    //parte de inventario
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
Route::get('/inventario/maquinaria/{id}', [MaquinariaController::class, 'show'])->name('maquinaria.show');
Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');




//Parte de herramientas
Route::post('/inventario/herramientas', [HerramientasController::class, 'store'])->name('herramientas.store');
Route::get('/inventario/herramientas', [HerramientasController::class, 'index'])->name('herramientas.index');
Route::delete('/inventario/herramientas/{id}', [HerramientasController::class, 'destroy'])->name('herramientas.destroy');
Route::put('/inventario/herramientas/{id}', [HerramientasController::class, 'update'])->name('herramientas.update');


//Parte de insumos
Route::post('/inventario/insumos', [InsumosController::class, 'store'])->name('insumos.store');
Route::get('/inventario/insumos', [InsumosController::class, 'index'])->name('insumos.index');
Route::delete('/inventario/insumos/{id}', [InsumosController::class, 'destroy'])->name('insumos.destroy');
Route::put('/inventario/insumos/{id}', [InsumosController::class, 'update'])->name('insumos.update');

//Parte de maquinaria
Route::post('/inventario/maquinaria', [MaquinariaController::class, 'store'])->name('maquinaria.store');
Route::get('/inventario/maquinaria', [MaquinariaController::class, 'index'])->name('maquinaria.index');
Route::delete('/inventario/maquinaria/{id}', [MaquinariaController::class, 'destroy'])->name('maquinaria.destroy');
Route::put('/inventario/maquinaria/{id}', [MaquinariaController::class, 'update'])->name('maquinaria.update');
Route::match(['put', 'patch'], '/inventario/maquinaria/asignar/{id}', [MaquinariaController::class, 'asignar_cantidad_insumos'])->name('maquinaria.cantidad');
Route::match(['put', 'patch'], '/inventario/maquinaria/asignar_insumos/{id}', [MaquinariaController::class, 'asignar_insumos'])->name('maquinaria.insumos_asignar');
Route::match(['put', 'patch'], '/inventario/maquinaria/desasignar_insumos/{id}', [MaquinariaController::class, 'desasignar_insumo'])->name('maquinaria.insumos_desasignar');


//Parte de mantenimiento
Route::get('/mantenimiento', [MantenimientoController::class, 'index'])->name('mantenimiento.index');
Route::post('/mantenimiento', [MantenimientoController::class, 'store'])->name('mantenimiento.store');
Route::delete('/mantenimiento/{id}', [MantenimientoController::class, 'destroy'])->name('mantenimiento.destroy');
Route::get('/get-insumos-por-maquinaria', [MantenimientoController::class, 'getInsumosPorMaquinaria'])->name('get-insumos-por-maquinaria');

Route::get('/datos-maquinaria', [MantenimientoController::class, 'obtenerDatosMaquinaria'])->name('get-datos-maquinaria');



//Parte de las practicas
Route::get('/practicas', [PracticaController::class, 'index'])->name('practicas.index');
Route::get('/practicas/create', [PracticaController::class, 'create'])->name('practicas.create');
Route::post('/practicas/create', [PracticaController::class, 'store'])->name('practicas.store');
Route::get('/practicas/{id}', [PracticaController::class, 'show'])->name('practicas.show');
Route::get('/practicas/{id}/edit', [PracticaController::class, 'edit'])->name('practicas.edit');
Route::PATCH('/practicas/{id}', [PracticaController::class, 'update'])->name('practicas.update');
Route::delete('/practicas/{id}', [PracticaController::class, 'destroy'])->name('practicas.destroy');

Route::post('/practicas/filtrar', [PracticaController::class, 'filtrar'])->name('practicas.filtrar');
Route::post('/practicas/completar/{id}', [PracticaController::class, 'completar_practica'])->name('practicas.completar');


//Parte de realizar practica de alumnos
Route::get('/alumnos/practicas/', [PracticaController::class, 'create_practica_alumno'])->name('practicasAlumno.create');
Route::post('/alumnos/practicas/', [PracticaController::class, 'store_practica_Alumno'])->name('practicasAlumno.store');





//Parte de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
Route::post('/alumnos-asignar-grupo', [AlumnoController::class, 'asignarGrupo'])->name('alumnos.asignar-grupo');
Route::post('/alumnos-desasignar-grupo', [AlumnoController::class, 'desasignarGrupo'])->name('alumnos.desasignar-grupo');
Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::match(['put', 'patch'], '/alumnos/{id}', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
Route::post('/grupos-alumno', [AlumnoController::class, 'filtraGrupo'])->name('alumnos.filtrar-grupos');




//Parte de asignatura
Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignatura.index');
Route::get('/asignaturas/create', [AsignaturaController::class, 'create'])->name('asignatura.create');
Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignatura.store');
Route::get('/asignaturas/{id}/edit', [AsignaturaController::class, 'edit'])->name('asignatura.edit');
Route::PATCH('/asignaturas/{id}', [AsignaturaController::class, 'update'])->name('asignatura.update');
Route::delete('/asignaturas/{id}', [AsignaturaController::class, 'destroy'])->name('asignatura.destroy');


//Parte de docentes 
Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
Route::get('/docentes/create', [DocenteController::class, 'create'])->name('docentes.create');
Route::post('/docentes/create', [DocenteController::class, 'store'])->name('docentes.store'); 
Route::get('/docentes/asigna/', [DocenteController::class, 'asigna'])->name('docentes.asigna');
Route::get('/docentes/{id}', [DocenteController::class, 'show'])->name('docentes.show');
Route::match(['put', 'patch'], '/docentes/{id}', [DocenteController::class, 'update'])->name('docentes.update');
Route::post('/docentes/filtrar_asignaturas', [DocenteController::class, 'filtrar_asignaturas'])->name('docentes.filtrar_asignaturas');
Route::post('/asignar', [DocenteController::class, 'asignar'])->name('docentes.asignar');
Route::delete('/docente/{id}', [DocenteController::class, 'destroy'])->name('docentes.destroy');
Route::post('/desasignar', [DocenteController::class, 'eliminar_asignacion'])->name('docentes.eliminar_asignacion');
Route::get('/desasignar', [DocenteController::class, 'eliminacion_asignacion'])->name('docentes.eliminacion_asignacion');
Route::post('/docentes/filtrar_grupos', [DocenteController::class, 'filtrar'])->name('docentes.filtrar_grupos');


//Parte de grupos
Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
Route::match(['put', 'patch'],'/grupos/{id}', [GrupoController::class, 'update'])->name('grupos.update');
Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->name('grupos.destroy');



//Parte de prestamo
Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
Route::match(['put', 'patch'], '/prestamos/{id}', [PrestamoController::class, 'update'])->name('prestamos.update');
Route::delete('/prestamos/{id}', [PrestamoController::class, 'destroy'])->name('prestamos.destroy');
Route::match(['put', 'patch'], '/prestamos/finalizar/{id}', [PrestamoController::class, 'finalizar'])->name('prestamos.finalizar');


//Parte de periodo

Route::get('/periodos', [PeriodoController::class, 'index'])->name('periodos.index');
Route::post('/periodos', [PeriodoController::class, 'store'])->name('periodos.store');
Route::match(['put', 'patch'], '/periodos/{id}', [PeriodoController::class, 'update'])->name('periodos.update');
Route::delete('/periodos/{id}', [PeriodoController::class, 'destroy'])->name('periodos.destroy');



//Parte de generar reportes

Route::get('/reporte/prestamos', [ReportesController::class, 'generar_reporte_prestamo'])->name('reporte.prestamo');
Route::post('/reporte/inventario', [ReportesController::class, 'generar_reporte_inventario'])->name('reporte.inventario');

Route::post('/reporte/practicas', [ReportesController::class, 'generar_reporte_practicas_completas'])->name('reporte.practicas');
Route::post('/reporte/inventario/herramientas', [ReportesController::class, 'generar_reporte_herramientas'])->name('reporte.herramientas');
Route::post('/reporte/inventario/maquinarias', [ReportesController::class, 'generar_reporte_maquinaria'])->name('reporte.maquinarias');
Route::post('/reporte/inventario/insumos', [ReportesController::class, 'generar_reporte_insumos'])->name('reporte.insumos');



//Proceso del lector de insumos de la maquinaria

Route::get('/lectura', [LectorController::class, 'index'])->name('lector.index');
Route::post('/lectura', [LectorController::class, 'store'])->name('lector.store');
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



Route::get('/inventario/herramientas', [HerramientasController::class, 'index'])->name('herramientas.index');
Route::get('/inventario/maquinaria', [MaquinariaController::class, 'index'])->name('maquinaria.index');
Route::get('/inventario/insumos', [InsumosController::class, 'index'])->name('insumos.index');



Route::delete('/inventario/herramientas/{id}', [HerramientasController::class, 'destroy'])->name('herramientas.destroy');
Route::delete('/inventario/insumos/{id}', [InsumosController::class, 'destroy'])->name('insumos.destroy');
Route::delete('/inventario/maquinaria/{id}', [MaquinariaController::class, 'destroy'])->name('maquinaria.destroy');
Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');




Route::put('/inventario/herramientas/{id}', [HerramientasController::class, 'update'])->name('herramientas.update');
Route::put('/inventario/maquinaria/{id}', [MaquinariaController::class, 'update'])->name('maquinaria.update');
Route::put('/inventario/insumos/{id}', [InsumosController::class, 'update'])->name('insumos.update');




//Parte de mantenimiento

Route::get('/mantenimiento', [MantenimientoController::class, 'index'])->name('mantenimiento.index');

Route::post('/mantenimiento', [MantenimientoController::class, 'store'])->name('mantenimiento.store');


//Parte de las practicas
Route::get('/practicas', [PracticaController::class, 'index'])->name('practicas.index');
Route::get('/practicas/create', [PracticaController::class, 'create'])->name('practicas.create');
Route::post('/practicas/create', [PracticaController::class, 'store'])->name('practicas.store');
Route::get('/practicas/{id}', [PracticaController::class, 'show'])->name('practicas.show');
Route::get('/practicas/{id}/edit', [PracticaController::class, 'edit'])->name('practicas.edit');
Route::PATCH('/practicas/{id}', [PracticaController::class, 'update'])->name('practicas.update');
Route::delete('/practicas/{id}', [PracticaController::class, 'destroy'])->name('practicas.destroy');


//Parte de realizar practica de alumnos
Route::get('/alumnos/practicas/', [PracticaController::class, 'RegistroPracticaAlumno'])->name('practicasAlumno.create');
Route::get('/buscarAlumno', [PracticaController::class, 'buscarAlumno']);


//Parte de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');


//Parte de asignatura
Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignatura.index');
Route::get('/asignaturas/create', [AsignaturaController::class, 'create'])->name('asignatura.create');
Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignatura.store');
Route::get('/asignaturas/{id}/edit', [AsignaturaController::class, 'edit'])->name('asignatura.edit');
Route::PATCH('/asignaturas/{id}', [AsignaturaController::class, 'update'])->name('asignatura.update');
Route::delete('/asignaturas/{id}', [AsignaturaController::class, 'destroy'])->name('asignatura.destroy');


//Parte de docentes 
Route::get('/docentes/create', [DocenteController::class, 'create'])->name('docentes.create');
Route::post('/docentes/create', [DocenteController::class, 'store'])->name('docentes.store'); 

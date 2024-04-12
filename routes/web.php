<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CatalogoArticulosController;



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
    return view('welcome');
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

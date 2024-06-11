<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Herramientas;
use App\Models\Maquinaria;
use App\Models\Insumos;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;


class InventarioTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_invetario(): void
    {
        Artisan::call('migrate');

        User::create([
            "name" =>"Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        
        ]);

        $acceso->assertStatus(302)->assertRedirect(route('home'));
        //Ver la tabla de inventario

        $acceso = $this->get(route('inventario.index'))
        ->assertStatus(200)
        ->assertViewIs('inventarios.index');

    }

    public function test_delete_invetario(): void

    {
        Artisan::call('migrate');

        User::create([
            "name" =>"Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        
        ]);

        $data = [
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 
        ];


        //Creacion correcta de una herramienta
        $response = $this->post(route('herramientas.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Torno',
            'cantidad' => 1,
            'tipo' => 'Herramienta manual'
        ]);
        $articulo=Catalogo_articulo::where('nombre', 'Torno')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        $delete_correcto = $this->delete(route('inventario.destroy',$articulo->id_articulo))->assertRedirect(route('inventario.index'));
       
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('catalogo_articulo', ['id_articulo' => $articulo->id_articulo]);

    }

   

}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Insumos;
class InsumosTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_insumos():void{
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

        $acceso = $this->get(route('insumos.index'))
        ->assertStatus(200)
        ->assertViewIs('insumos.index');

    }



    public function test_create_insumo():void{
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

        //Create pero con la asignacion de los insumos
        $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
           
        ];

        $response_insumo = $this->post(route('insumos.store'), $data_insumos); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('insumos.index'));

        $this->assertDatabaseHas('insumos', [
            'capacidad' => 3,              
        ]);

        $this->assertDatabaseHas('articulo_inventariado', [
            'id_articulo' => 'AI',              
        ]);

        $insumo=Insumos::find('AI01');
        $this->assertNotNull($insumo);


         //Insertar un insumo ya registrado

         $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
           
         ];

        $response = $this->post(route('insumos.store'), $data_insumos); 
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));

        $insumo=Insumos::find('AI03');
        $this->assertNotNull($insumo);
    
    }

    public function test_edit_insumo(){

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
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
        ];

        
        $response = $this->post(route('insumos.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Aceite Industrial',
            'cantidad' => 2,
            'tipo' => 'Aceite para maquina'
        ]);
        $articulo=Catalogo_articulo::where('nombre', 'Aceite Industrial')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        //Ahora se crean los id de inventariado por la cantidad que se ingreso
        for ($i = 0; $i <2; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Insumos'
            ]);
        }

        $this->assertDatabaseHas('insumos', [
            'capacidad' => 3,    
        ]);
        
        $insumo=Insumos::find('AI01');
        $this->assertNotNull($insumo);

        $data_update=[
            'capacidad' => '20',
            'estatus'=>'No disponible'
        ];

        $updateCorrecto = $this->put(route('insumos.update',$insumo->id_insumo),$data_update);

        $insumo_update=Insumos::where('capacidad',20);
        $this->assertNotNull($insumo_update);

        $updateCorrecto->assertRedirect(route('insumos.index'));
    }

    public function test_delete_insumo():void{
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
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,

        ];
       
       
        $response = $this->post(route('insumos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));
        

        $insumo=Insumos::find('AI01');

        $this->assertNotNull($insumo);

        $delete_correcto = $this->delete(route('insumos.destroy',$insumo->id_insumo))->assertRedirect(route('insumos.index'));
    
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('insumos', ['id_insumos' => $insumo->id_insumo]);


    }
}

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
    public function test_view_catalogo(): void
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


    public function test_create_catalogo_herramienta():void{
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
        //Crear una herramienta en el catalogo
        $data=[
            "tipo"=>"Herramientas",
            'nombre' => 'pinza de chofer',
            'tipo_herramienta' => "Herramienta de corte",
            'dimension_herramienta' =>234,
        ];
    
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));

        $herramienta=Catalogo_articulo::where('id_articulo', 'HC-PC-0234')->first();

        //No es nulo
        $this->assertNotNull($herramienta);
        //Crear una herramienta con nombre diferente pero con codigo similar

        $data=[
            "tipo"=>"Herramientas",
            'nombre' => 'pinza de c',
            'tipo_herramienta' => "Herramienta de corte",
            'dimension_herramienta' =>234,
        ];

        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
        //No es nulo
        $herramienta=Catalogo_articulo::where('id_articulo', 'HC-PC1-0234')->first();

        //Crear una herramienta que ya esta registrado ejemplo PC1 deberia regresar PC2

        $data=[
            "tipo"=>"Herramientas",
            'nombre' => 'pinza de cr',
            'tipo_herramienta' => "Herramienta de corte",
            'dimension_herramienta' =>234,
        ];

        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
        //No es nulo
        $herramienta=Catalogo_articulo::where('id_articulo', 'HC-PC2-0234')->first();
        
        //Se selecciona null en tipo de herramienta null
        $data=[
            "tipo"=>"Herramientas",
            'nombre' => 'pinza de cr',
            'tipo_herramienta' => null,
            'dimension_herramienta' =>234,
        ];

        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['tipo_herramienta' => 'Seleccione el tipo de herramienta que desea registrar.']); 
        $response->assertRedirect(); 

        //Validacion de articulo duplicado

        $data=[
            "tipo"=>"Herramientas",
            'nombre' => 'pinza de chofer',
            'tipo_herramienta' => "Herramienta de corte",
            'dimension_herramienta' =>234,
        ];
    
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('error');

    }
    public function test_create_catalogo_maquinaria():void{
       
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

        //Crear una maquinaria en el catalogo
        $data=[
            "tipo"=>"Maquinaria",
            'nombre' => 'Maquina Inyectora',
            'seccion'=>"03"
        ];

        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
       
        $maquinaria=Catalogo_articulo::where('id_articulo',"03MI")->first();
        $this->assertNotNull($maquinaria);
        

        //Crear una maquinaria con nombre diferente pero con codigo similar
        $data=[
            "tipo"=>"Maquinaria",
            'nombre' => 'Maquina Inspectora',
            'seccion'=>"03"
        ];

          $response = $this->post(route('inventario.store'), $data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('inventario.index'));

          $maquinaria=Catalogo_articulo::where('id_articulo',"03MI1")->first();
          $this->assertNotNull($maquinaria);



        //Validacion de articulo duplicado
          $data=[
            "tipo"=>"Maquinaria",
            'nombre' => 'Maquina Inspectora',
            'seccion'=>"03"
        ];

          $response = $this->post(route('inventario.store'), $data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('inventario.index'));
          $response->assertSessionHas('error');


    }
    


    public function test_create_catalogo_insumo():void{
        
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

        //Crear un insumo en el catalogo
        $data=[
            "tipo"=>"Insumos",
            'nombre' => 'Aceite Industrial',
        ];

        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
       
        $insumo=Catalogo_articulo::where('id_articulo',"AI")->first();
        $this->assertNotNull($insumo);
        

        //Crear un insumo con nombre diferente pero con codigo similar
        $data=[
            "tipo"=>"Insumos",
            'nombre' => 'Aceite I',
        ];

          $response = $this->post(route('inventario.store'), $data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('inventario.index'));

          $insumo=Catalogo_articulo::where('id_articulo',"AI1")->first();
          $this->assertNotNull($insumo);



        //Validacion de articulo duplicado
        $data=[
            "tipo"=>"Insumos",
            'nombre' => 'Aceite Industrial',
        ];

          $response = $this->post(route('inventario.store'), $data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('inventario.index'));
          $response->assertSessionHas('error');





    }


    //Este valida el form principal donde se elige el tipo de articulo
    public function test_create_catalogo_general():void{
         
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
        
       
         $data=[
            "tipo"=>"default",
            'nombre' => 'Aceite Industrial',
        ];

          $response = $this->post(route('inventario.store'), $data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('inventario.index'));
    }






    public function test_delete_catalogo(): void

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

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

    

        $delete_correcto = $this->delete(route('inventario.destroy',"AI"))->assertRedirect(route('inventario.index'));
       
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('catalogo_articulo', ['id_articulo' =>"AI"]);

    }

   

}
